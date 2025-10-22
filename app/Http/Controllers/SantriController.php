<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Kela;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kelas untuk dropdown
        $kelas = Kela::all();

        // NOTE: gunakan 'kelas' sebagai query param (sesuai dropdown)
        $selectedKelas = $request->input('kelas'); // <- pastikan di Blade onchange kirim ?kelas=ID

        // Ambil data santri (filter per kelas jika dipilih)
        $querySantri = Santri::with('kela'); // eager load relasi kela
        if ($selectedKelas) {
            $querySantri->where('id_kelas', $selectedKelas);
        }
        $santri = $querySantri->get();

        // Ambil data pencapaian bergabung dengan santri dan kelas - TAMBAHKAN id_santri
        $pencapaian = DB::table('pencapaian as p')
            ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
            ->join('kelas as k', 'k.id_kelas', '=', 's.id_kelas')
            ->when($selectedKelas, fn($q) => $q->where('k.id_kelas', $selectedKelas))
            ->select(
                'p.id_pencapaian',
                's.id_santri', // <- TAMBAHKAN INI
                's.nama as nama_santri',
                'k.nama_kelas',
                'p.judul',
                'p.deskripsi',
                'p.tipe',
                'p.tanggal',
                'p.skor'
            )
            ->orderBy('s.id_santri')
            ->orderBy('p.tanggal')
            ->get();

        // Ambil daftar kompetensi unik dari kolom "judul" pencapaian
        $kompetensi = $pencapaian->pluck('judul')->unique()->filter()->values();

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
                // Jika tahun_masuk adalah Carbon object
                if ($s->tahun_masuk instanceof \Carbon\Carbon) {
                    $tahunMasuk = $s->tahun_masuk->format('Y');
                }
                // Jika tahun_masuk adalah string atau integer
                else if (is_numeric($s->tahun_masuk)) {
                    $tahunMasuk = $s->tahun_masuk;
                } else {
                    $tahunMasuk = $s->tahun_masuk;
                }
            }

            return [
                'id' => $s->id_santri, // TAMBAHKAN ID UNTUK AKSI
                'data' => [
                    $s->kela->nama_kelas ?? $s->id_kelas ?? '-',
                    $s->nama ?? '-',
                    $s->nisn ?? '-',
                    $s->nik ?? '-',
                    $tahunMasuk, // Gunakan variabel yang sudah di-handle
                    $s->jenis_kelamin ?? '-',
                    $s->tanggal_lahir ? Carbon::parse($s->tanggal_lahir)->format('d M Y') : '-',
                    $s->nama_ayah ?? '-',
                    $s->nama_ibu ?? '-',
                ]
            ];
        })->toArray();

        // Hitung statistik untuk dashboard
        $statistics = [
            'total_santri' => $santri->count(),
            'total_pencapaian' => $pencapaian->count(),
            'rata_rata_skor' => $pencapaian->avg('skor') ? round($pencapaian->avg('skor'), 1) : 0,
            'santri_berprestasi' => $pencapaian->unique('nama_santri')->count(),
        ];

        // Distribusi tipe pencapaian
        $distribusiTipe = $pencapaian->groupBy('tipe')->map(function ($group, $tipe) use ($pencapaian) {
            return [
                'count' => $group->count(),
                'percentage' => $pencapaian->count() > 0 ? round(($group->count() / $pencapaian->count()) * 100, 1) : 0
            ];
        });

        return view('pages.santri', compact(
            'kelas',
            'selectedKelas',
            'kompetensi',
            'columnsbio',
            'rowsbio',
            'pencapaian',
            'statistics',
            'distribusiTipe',
            'santri'
        ));
    }
    public function create()
    {
        $kelas = Kela::all();
        return view('pages.santri-create', compact('kelas'));
    }

    public function store(Request $request)
    {
        // Validasi input biar tetap aman
        $request->validate([
            'nama' => 'required|string|max:100',
            'nisn' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'id_kelas' => 'nullable|integer|exists:kelas,id_kelas',
            'tahun_masuk' => 'required|digits:4',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'nama_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:100',
        ]);

        // Format data sesuai kolom di database
        $data = [
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'id_kelas' => $request->id_kelas,
            'tahun_masuk' => $request->tahun_masuk, // pastikan hanya 4 digit
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir, // YYYY-MM-DD
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
        ];

        // Jalankan query manual (setara persis dengan contohmu)
        DB::insert(
            'INSERT INTO santri
            (nama, nisn, nik, id_kelas, tahun_masuk, jenis_kelamin, tanggal_lahir, nama_ayah, nama_ibu)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['nama'],
                $data['nisn'],
                $data['nik'],
                $data['id_kelas'],
                $data['tahun_masuk'],
                $data['jenis_kelamin'],
                $data['tanggal_lahir'],
                $data['nama_ayah'],
                $data['nama_ibu'],
            ]
        );

        return redirect()->back()->with('success', 'Data santri berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $santri = Santri::findOrFail($id);
        $kelas = Kela::all(); // Sesuaikan dengan model Kelas Anda

        return view('pages.action.santri_edit', compact('santri', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'nisn' => 'required|string|max:20',
            'nik' => 'nullable|string|max:20',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'status_ujian' => 'nullable|in:Lulus,Belum Lulus',
            'tahun_masuk' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ]);

        // Manual unique validation untuk NISN
        if ($request->nisn !== $santri->nisn) {
            $exists = Santri::where('nisn', $request->nisn)
                ->where('id_santri', '!=', $id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('nisn', 'NISN sudah digunakan oleh santri lain.');
            }
        }

        // Manual unique validation untuk NIK (hanya jika diisi dan berbeda)
        if (!empty($request->nik) && $request->nik !== $santri->nik) {
            $exists = Santri::where('nik', $request->nik)
                ->where('id_santri', '!=', $id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('nik', 'NIK sudah digunakan oleh santri lain.');
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle tahun_masuk - JANGAN diubah ke Carbon, biarkan sebagai integer
        // Karena di model sudah ada cast ke datetime, biarkan Laravel yang handle
        if (empty($data['tahun_masuk'])) {
            $data['tahun_masuk'] = null;
        }

        // Handle tanggal_lahir - biarkan sebagai string, Laravel akan auto convert ke Carbon
        if (empty($data['tanggal_lahir'])) {
            $data['tanggal_lahir'] = null;
        }

        // Ensure null for empty values
        $data['nik'] = empty($data['nik']) ? null : $data['nik'];
        $data['id_kelas'] = empty($data['id_kelas']) ? null : $data['id_kelas'];

        // Debug data sebelum update
        logger('Data sebelum update:', [
            'tahun_masuk' => $data['tahun_masuk'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'original_tahun_masuk' => $santri->tahun_masuk,
            'original_tanggal_lahir' => $santri->tanggal_lahir
        ]);

        $santri->update($data);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri ' . $santri->nama . ' berhasil diperbarui');
    }
    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        $santri->delete();

        return response()->json(['success' => true, 'message' => 'Santri berhasil dihapus']);
    }
}
