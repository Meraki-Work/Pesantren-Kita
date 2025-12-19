<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SantriController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Check if santri belongs to user's ponpes
     */
    private function checkSantriOwnership($santriId)
    {
        $userPonpesId = $this->getUserPonpesId();

        $santri = Santri::where('id_santri', $santriId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$santri) {
            Log::warning('Akses santri ditolak', [
                'user_id' => Auth::id(),
                'ponpes_id' => $userPonpesId,
                'santri_id' => $santriId,
                'ip' => request()->ip()
            ]);
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data ini.');
        }

        return $santri;
    }

    /**
     * Check if kelas belongs to user's ponpes
     */
    private function checkKelasOwnership($kelasId)
    {
        $userPonpesId = $this->getUserPonpesId();

        $kelas = Kelas::where('id_kelas', $kelasId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$kelas) {
            Log::warning('Akses kelas ditolak untuk santri', [
                'user_id' => Auth::id(),
                'ponpes_id' => $userPonpesId,
                'kelas_id' => $kelasId,
                'ip' => request()->ip()
            ]);
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses kelas ini.');
        }

        return $kelas;
    }

    // =====================================================================
    // METHOD 1: UNTUK HALAMAN BIODATA SANTRI (santri.blade.php)
    // =====================================================================
    public function index(Request $request)
    {
        try {
            Log::info('Mengakses halaman biodata santri', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'kelas_filter' => $request->input('kelas')
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Ambil semua kelas untuk dropdown (hanya milik ponpes user)
            $kelas = Kelas::where('ponpes_id', $userPonpesId)->get();

            // Filter kelas yang dipilih
            $selectedKelas = $request->input('kelas');

            // Validasi bahwa kelas yang dipilih milik ponpes user
            if ($selectedKelas) {
                $this->checkKelasOwnership($selectedKelas);
            }

            // Ambil data santri (hanya milik ponpes user)
            $querySantri = Santri::with('kelas')->where('ponpes_id', $userPonpesId);

            if ($selectedKelas) {
                $querySantri->where('id_kelas', $selectedKelas);
            }

            $santri = $querySantri->get();

            // Ambil data pencapaian untuk sidebar info (hitung saja, tidak perlu detail)
            $pencapaianCount = DB::table('pencapaian as p')
                ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
                ->where('s.ponpes_id', $userPonpesId)
                ->when($selectedKelas, fn($q) => $q->where('s.id_kelas', $selectedKelas))
                ->count();

            // Ambil daftar kompetensi unik untuk sidebar
            $kompetensi = DB::table('pencapaian as p')
                ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
                ->where('s.ponpes_id', $userPonpesId)
                ->when($selectedKelas, fn($q) => $q->where('s.id_kelas', $selectedKelas))
                ->select('p.judul')
                ->distinct()
                ->pluck('judul')
                ->filter()
                ->values();

            // Kolom & rows untuk Bio (format tanggal)
            $columnsbio = [
                'Kelas',
                'Nama',
                'NISN',
                'NIK',
                'Tahun Masuk',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Nama Ayah',
                'Nama Ibu'
            ];

            $rowsbio = $santri->map(function ($s) {
                // Handle tahun_masuk dengan aman
                $tahunMasuk = '-';
                if ($s->tahun_masuk) {
                    if ($s->tahun_masuk instanceof \Carbon\Carbon) {
                        $tahunMasuk = $s->tahun_masuk->format('Y');
                    } else if (is_numeric($s->tahun_masuk)) {
                        $tahunMasuk = $s->tahun_masuk;
                    } else {
                        $tahunMasuk = $s->tahun_masuk;
                    }
                }

                return [
                    'id' => $s->id_santri,
                    'data' => [
                        $s->kelas->nama_kelas ?? $s->id_kelas ?? '-',
                        $s->nama ?? '-',
                        $s->nisn ?? '-',
                        $s->nik ?? '-',
                        $tahunMasuk,
                        $s->jenis_kelamin ?? '-',
                        $s->tanggal_lahir ? Carbon::parse($s->tanggal_lahir)->format('d M Y') : '-',
                        $s->nama_ayah ?? '-',
                        $s->nama_ibu ?? '-',
                    ]
                ];
            })->toArray();

            Log::info('Berhasil memuat data biodata santri', [
                'user_id' => Auth::id(),
                'total_santri' => $santri->count(),
                'total_pencapaian' => $pencapaianCount,
                'kelas_filter' => $selectedKelas
            ]);

            return view('pages.santri', compact(
                'kelas',
                'selectedKelas',
                'kompetensi',
                'columnsbio',
                'rowsbio',
                'santri'
            ));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk index santri', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('santri.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada santri index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data santri.');
        }
    }

    // =====================================================================
    // METHOD 2: UNTUK HALAMAN KOMPETENSI SANTRI (kompetensi.blade.php)
    // =====================================================================
    public function kompetensi(Request $request)
    {
        try {
            Log::info('Mengakses halaman kompetensi santri', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'kelas_filter' => $request->input('kelas')
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Ambil semua kelas untuk dropdown filter (hanya milik ponpes user)
            $kelas = Kelas::where('ponpes_id', $userPonpesId)->get();

            // Filter kelas yang dipilih
            $selectedKelas = $request->input('kelas');

            // Validasi bahwa kelas yang dipilih milik ponpes user
            if ($selectedKelas) {
                $this->checkKelasOwnership($selectedKelas);
            }

            // Ambil data pencapaian dengan detail lengkap - hanya data ponpes user
            $queryPencapaian = DB::table('pencapaian as p')
                ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
                ->join('kelas as k', 'k.id_kelas', '=', 's.id_kelas')
                ->where('s.ponpes_id', $userPonpesId)
                ->select(
                    'p.id_pencapaian',
                    's.id_santri',
                    's.nama as nama_santri',
                    'k.nama_kelas',
                    'p.judul',
                    'p.deskripsi',
                    'p.tipe',
                    'p.tanggal',
                    'p.skor'
                );

            // Terapkan filter kelas jika ada
            if ($selectedKelas) {
                $queryPencapaian->where('k.id_kelas', $selectedKelas);
            }

            $pencapaian = $queryPencapaian
                ->orderBy('p.tanggal', 'desc')
                ->orderBy('s.nama')
                ->get();

            // Hitung statistik untuk dashboard
            $statistics = [
                'total_santri' => Santri::where('ponpes_id', $userPonpesId)
                    ->when($selectedKelas, fn($q) => $q->where('id_kelas', $selectedKelas))
                    ->count(),
                'total_pencapaian' => $pencapaian->count(),
                'rata_rata_skor' => $pencapaian->avg('skor') ? round($pencapaian->avg('skor'), 1) : 0,
                'santri_berprestasi' => $pencapaian->unique('nama_santri')->count(),
            ];

            // Distribusi tipe pencapaian untuk chart
            $distribusiTipe = $pencapaian->groupBy('tipe')->map(function ($group, $tipe) use ($pencapaian) {
                return [
                    'count' => $group->count(),
                    'percentage' => $pencapaian->count() > 0 ? round(($group->count() / $pencapaian->count()) * 100, 1) : 0
                ];
            });

            Log::info('Berhasil memuat data kompetensi santri', [
                'user_id' => Auth::id(),
                'total_pencapaian' => $pencapaian->count(),
                'kelas_filter' => $selectedKelas,
                'rata_rata_skor' => $statistics['rata_rata_skor']
            ]);

            return view('pages.kompetensi', compact(
                'kelas',
                'selectedKelas',
                'pencapaian',
                'statistics',
                'distribusiTipe'
            ));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk kompetensi santri', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('santri.kompetensi')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada kompetensi santri', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data kompetensi.');
        }
    }

    // =====================================================================
    // METHOD 3: API UNTUK CHART DATA (digunakan oleh kompetensi.blade.php)
    // =====================================================================
    public function getChartData(Request $request)
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            // Ambil distribusi tipe pencapaian untuk chart
            $distribusi = DB::table('pencapaian as p')
                ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
                ->where('s.ponpes_id', $userPonpesId)
                ->select('p.tipe', DB::raw('COUNT(*) as count'))
                ->groupBy('p.tipe')
                ->orderBy('count', 'desc')
                ->get();

            // Jika tidak ada data, kembalikan data default
            if ($distribusi->isEmpty()) {
                return response()->json([
                    'labels' => ['Tidak ada data'],
                    'data' => [1],
                    'colors' => ['#e5e7eb']
                ]);
            }

            // Warna untuk chart
            $colorPalette = [
                '#3b82f6', // blue-500
                '#10b981', // emerald-500
                '#8b5cf6', // violet-500
                '#f59e0b', // amber-500
                '#ef4444', // red-500
                '#06b6d4', // cyan-500
                '#84cc16', // lime-500
                '#f97316', // orange-500
            ];

            // Format data untuk chart
            $labels = $distribusi->pluck('tipe')->toArray();
            $data = $distribusi->pluck('count')->toArray();
            $colors = array_slice($colorPalette, 0, count($labels));

            return response()->json([
                'labels' => $labels,
                'data' => $data,
                'colors' => $colors
            ]);

        } catch (\Exception $e) {
            Log::error('Error pada getChartData', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'labels' => ['Error'],
                'data' => [1],
                'colors' => ['#ef4444']
            ], 500);
        }
    }

    // =====================================================================
    // METHOD 4: CRUD SANTRI (Shared Methods)
    // =====================================================================
    public function create()
    {
        try {
            Log::info('Mengakses form create santri', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Hanya ambil kelas yang milik ponpes user
            $kelas = Kelas::where('ponpes_id', $userPonpesId)->get();

            Log::debug('Data untuk form create santri', [
                'kelas_count' => $kelas->count()
            ]);

            return view('pages.santri-create', compact('kelas'));
        } catch (\Exception $e) {
            Log::error('Error pada santri create form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('santri.index')->with('error', 'Terjadi kesalahan saat memuat form tambah data.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Memproses store santri', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Validasi bahwa kelas yang dipilih milik ponpes user
            if ($request->id_kelas) {
                $this->checkKelasOwnership($request->id_kelas);
            }

            // 🔥 PERBAIKAN: Hapus unique validation untuk nama, biarkan nama bisa sama
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:100', // HANYA required dan string, TANPA unique
                'nisn' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('santri')->where(function ($query) use ($userPonpesId) {
                        return $query->where('ponpes_id', $userPonpesId);
                    })
                ],
                'nik' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('santri')->where(function ($query) use ($userPonpesId) {
                        return $query->where('ponpes_id', $userPonpesId);
                    })
                ],
                'id_kelas' => 'nullable|integer|exists:kelas,id_kelas',
                'tahun_masuk' => 'required|digits:4',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'tanggal_lahir' => 'required|date',
                'nama_ayah' => 'nullable|string|max:100',
                'nama_ibu' => 'nullable|string|max:100',
            ], [
                'nisn.unique' => 'NISN :input sudah digunakan oleh santri lain di ponpes ini',
                'nik.unique' => 'NIK :input sudah digunakan oleh santri lain di ponpes ini',
                'nisn.required' => 'NISN wajib diisi',
                'nik.required' => 'NIK wajib diisi',
            ]);

            // 🔥 PERBAIKAN: Cek duplikat secara manual untuk memberikan feedback yang lebih baik
            $existingNisn = Santri::where('nisn', $request->nisn)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            $existingNik = Santri::where('nik', $request->nik)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            if ($existingNisn && $existingNik) {
                Log::warning('NISN dan NIK sudah digunakan', [
                    'user_id' => Auth::id(),
                    'nisn' => $request->nisn,
                    'nik' => $request->nik
                ]);
                return redirect()->back()
                    ->with('error', 'NISN <strong>' . $request->nisn . '</strong> dan NIK <strong>' . $request->nik . '</strong> sudah digunakan oleh santri lain di ponpes ini.')
                    ->withInput();
            }

            if ($existingNisn) {
                Log::warning('NISN sudah digunakan', [
                    'user_id' => Auth::id(),
                    'nisn' => $request->nisn
                ]);
                return redirect()->back()
                    ->with('error', 'NISN <strong>' . $request->nisn . '</strong> sudah digunakan oleh santri lain di ponpes ini.')
                    ->withInput();
            }

            if ($existingNik) {
                Log::warning('NIK sudah digunakan', [
                    'user_id' => Auth::id(),
                    'nik' => $request->nik
                ]);
                return redirect()->back()
                    ->with('error', 'NIK <strong>' . $request->nik . '</strong> sudah digunakan oleh santri lain di ponpes ini.')
                    ->withInput();
            }

            if ($validator->fails()) {
                Log::warning('Validasi gagal pada store santri', [
                    'user_id' => Auth::id(),
                    'errors' => $validator->errors()->toArray()
                ]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Format data sesuai kolom di database
            $data = [
                'ponpes_id' => $userPonpesId,
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'id_kelas' => $request->id_kelas,
                'tahun_masuk' => $request->tahun_masuk,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
            ];

            // Gunakan Eloquent untuk konsistensi
            $santri = Santri::create($data);

            Log::info('Berhasil membuat santri baru', [
                'user_id' => Auth::id(),
                'santri_id' => $santri->id_santri,
                'nama' => $santri->nama,
                'nisn' => $santri->nisn
            ]);

            return redirect()->route('santri.index')
                ->with('success', 'Data santri <strong>' . $santri->nama . '</strong> berhasil ditambahkan!');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk store santri', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('santri.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada santri store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah data santri.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            Log::info('Mengakses form edit santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data santri
            $santri = $this->checkSantriOwnership($id);

            // 🔥 PERBAIKAN: Format tanggal_lahir untuk input type="date"
            if ($santri->tanggal_lahir) {
                // Jika tanggal_lahir adalah Carbon instance, format ke Y-m-d
                if ($santri->tanggal_lahir instanceof \Carbon\Carbon) {
                    $santri->tanggal_lahir_formatted = $santri->tanggal_lahir->format('Y-m-d');
                } else {
                    // Jika string, coba parse dan format
                    try {
                        $santri->tanggal_lahir_formatted = \Carbon\Carbon::parse($santri->tanggal_lahir)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $santri->tanggal_lahir_formatted = $santri->tanggal_lahir;
                    }
                }
            } else {
                $santri->tanggal_lahir_formatted = null;
            }

            // Hanya ambil kelas yang milik ponpes user
            $kelas = Kelas::where('ponpes_id', $userPonpesId)->get();

            Log::debug('Data untuk form edit santri', [
                'santri_id' => $santri->id_santri,
                'nama' => $santri->nama,
                'tanggal_lahir_original' => $santri->tanggal_lahir,
                'tanggal_lahir_formatted' => $santri->tanggal_lahir_formatted,
                'kelas_count' => $kelas->count()
            ]);

            return view('pages.action.edit_santri', compact('santri', 'kelas'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk edit santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('santri.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            Log::warning('Santri tidak ditemukan untuk edit', [
                'user_id' => Auth::id(),
                'santri_id' => $id
            ]);
            return redirect()->route('santri.index')->with('error', 'Data santri tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada santri edit', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('santri.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Memproses update santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'input_data' => $request->except(['_token', '_method'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data santri
            $santri = $this->checkSantriOwnership($id);

            // Validasi bahwa kelas yang dipilih milik ponpes user
            if ($request->id_kelas) {
                $this->checkKelasOwnership($request->id_kelas);
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:100', // 🔥 TANPA unique validation
                'nisn' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:santri,nisn,' . $id . ',id_santri,ponpes_id,' . $userPonpesId
                ],
                'nik' => [
                    'nullable',
                    'string',
                    'max:20',
                    'unique:santri,nik,' . $id . ',id_santri,ponpes_id,' . $userPonpesId
                ],
                'id_kelas' => 'nullable|exists:kelas,id_kelas',
                'status_ujian' => 'nullable|in:Lulus,Belum Lulus',
                'tahun_masuk' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
                'tanggal_lahir' => 'nullable|date',
                'nama_ayah' => 'nullable|string|max:100',
                'nama_ibu' => 'nullable|string|max:100',
                'alamat' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                Log::warning('Validasi gagal pada update santri', [
                    'user_id' => Auth::id(),
                    'santri_id' => $id,
                    'errors' => $validator->errors()->toArray()
                ]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $validator->validated();

            // Handle tahun_masuk
            if (empty($data['tahun_masuk'])) {
                $data['tahun_masuk'] = null;
            }

            // Handle tanggal_lahir
            if (empty($data['tanggal_lahir'])) {
                $data['tanggal_lahir'] = null;
            }

            // Ensure null for empty values
            $data['nik'] = empty($data['nik']) ? null : $data['nik'];
            $data['id_kelas'] = empty($data['id_kelas']) ? null : $data['id_kelas'];

            $santri->update($data);

            Log::info('Berhasil update santri', [
                'user_id' => Auth::id(),
                'santri_id' => $santri->id_santri,
                'nama' => $santri->nama,
                'nisn' => $santri->nisn
            ]);

            return redirect()->route('santri.index')
                ->with('success', 'Data santri ' . $santri->nama . ' berhasil diperbarui');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk update santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('santri.index')->with('error', $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi exception pada update santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            Log::warning('Santri tidak ditemukan untuk update', [
                'user_id' => Auth::id(),
                'santri_id' => $id
            ]);
            return redirect()->route('santri.index')->with('error', 'Data santri tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada santri update', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data santri.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Memproses delete santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id
            ]);

            // Cek kepemilikan data santri
            $santri = $this->checkSantriOwnership($id);

            $santriData = [
                'id_santri' => $santri->id_santri,
                'nama' => $santri->nama,
                'nisn' => $santri->nisn
            ];

            $santri->delete();

            Log::info('Berhasil delete santri', [
                'user_id' => Auth::id(),
                'deleted_santri' => $santriData
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Santri berhasil dihapus'
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk delete santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        } catch (ModelNotFoundException $e) {
            Log::warning('Santri tidak ditemukan untuk delete', [
                'user_id' => Auth::id(),
                'santri_id' => $id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data santri tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error pada santri delete', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data santri.'
            ], 500);
        }
    }

    /**
     * Check if NISN or NIK is unique for real-time validation
     */
    public function checkUnique(Request $request)
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            $request->validate([
                'field' => 'required|in:nisn,nik',
                'value' => 'required|string|max:20'
            ]);

            $exists = Santri::where($request->field, $request->value)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            return response()->json([
                'available' => !$exists,
                'message' => $exists ? "{$request->field} \"{$request->value}\" sudah digunakan oleh santri lain" : "{$request->field} tersedia"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'message' => 'Terjadi kesalahan saat validasi'
            ], 500);
        }
    }

    /**
     * Get santri data for API (optional)
     */
    public function getSantriByPonpes()
    {
        try {
            Log::debug('Mengambil data santri untuk API', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $santri = Santri::with('kelas')
                ->where('ponpes_id', $userPonpesId)
                ->select('id_santri', 'nama', 'nisn', 'nik', 'id_kelas', 'tahun_masuk', 'jenis_kelamin')
                ->orderBy('nama', 'asc')
                ->paginate(10);

            Log::debug('Data santri API berhasil diambil', [
                'user_id' => Auth::id(),
                'data_count' => $santri->count()
            ]);

            return response()->json([
                'success' => true,
                'data' => $santri
            ]);
        } catch (\Exception $e) {
            Log::error('Error pada getSantriByPonpes API', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data santri'
            ], 500);
        }
    }

    /**
     * API: Get kompetensi by santri ID (untuk modal detail di halaman kompetensi)
     */
    public function getKompetensiBySantri($id)
    {
        try {
            Log::debug('Mengambil data kompetensi untuk santri', [
                'user_id' => Auth::id(),
                'santri_id' => $id
            ]);

            // Cek kepemilikan data santri
            $santri = $this->checkSantriOwnership($id);

            // Ambil data kompetensi santri
            $kompetensi = DB::table('pencapaian as p')
                ->where('p.id_santri', $id)
                ->select('p.judul', 'p.deskripsi', 'p.tipe', 'p.tanggal', 'p.skor')
                ->orderBy('p.tanggal', 'desc')
                ->get();

            Log::debug('Data kompetensi santri berhasil diambil', [
                'santri_id' => $id,
                'data_count' => $kompetensi->count()
            ]);

            return response()->json($kompetensi);
        } catch (\Exception $e) {
            Log::error('Error pada getKompetensiBySantri API', [
                'user_id' => Auth::id(),
                'santri_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([], 500);
        }
    }
}