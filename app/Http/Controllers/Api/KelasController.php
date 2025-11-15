<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('kelas')->select('*');

            // Search functionality
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nama_kelas', 'like', "%{$searchTerm}%")
                      ->orWhere('tingkat', 'like', "%{$searchTerm}%");
                });
            }

            // Filter by tingkat
            if ($request->has('tingkat') && $request->tingkat) {
                $query->where('tingkat', $request->tingkat);
            }

            // Sorting
            $sortField = $request->get('sort_field', 'nama_kelas');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortField, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $kelas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil diambil',
                'data' => $kelas->items(),
                'meta' => [
                    'current_page' => $kelas->currentPage(),
                    'last_page' => $kelas->lastPage(),
                    'per_page' => $kelas->perPage(),
                    'total' => $kelas->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_kelas' => 'required|string|max:50',
                'tingkat' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $idKelas = DB::table('kelas')->insertGetId([
                'ponpes_id' => null,
                'nama_kelas' => $request->nama_kelas,
                'tingkat' => $request->tingkat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $kelas = DB::table('kelas')->where('id_kelas', $idKelas)->first();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan',
                'data' => $kelas
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $kelas = DB::table('kelas')->where('id_kelas', $id)->first();

            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil diambil',
                'data' => $kelas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Cek apakah kelas exists
            $kelas = DB::table('kelas')->where('id_kelas', $id)->first();
            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nama_kelas' => 'sometimes|required|string|max:50',
                'tingkat' => 'sometimes|required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];
            if ($request->has('nama_kelas')) {
                $updateData['nama_kelas'] = $request->nama_kelas;
            }
            if ($request->has('tingkat')) {
                $updateData['tingkat'] = $request->tingkat;
            }
            $updateData['updated_at'] = now();

            DB::table('kelas')->where('id_kelas', $id)->update($updateData);

            $kelasUpdated = DB::table('kelas')->where('id_kelas', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil diperbarui',
                'data' => $kelasUpdated
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Cek apakah kelas exists
            $kelas = DB::table('kelas')->where('id_kelas', $id)->first();
            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }

            // Cek apakah ada santri yang masih terdaftar di kelas ini
            $santriCount = DB::table('santri')->where('id_kelas', $id)->count();
            if ($santriCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus kelas. Masih ada ' . $santriCount . ' santri yang terdaftar di kelas ini.'
                ], 422);
            }

            DB::table('kelas')->where('id_kelas', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all santri in specific kelas
     */
    public function getSantri($id)
    {
        try {
            // Cek apakah kelas exists
            $kelas = DB::table('kelas')->where('id_kelas', $id)->first();
            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }

            $santri = DB::table('santri')
                ->where('id_kelas', $id)
                ->select('id_santri', 'nama_santri', 'nisn', 'jenis_kelamin', 'tanggal_lahir')
                ->orderBy('nama_santri')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data santri di kelas ' . $kelas->nama_kelas . ' berhasil diambil',
                'data' => [
                    'kelas' => $kelas,
                    'santri' => $santri,
                    'total_santri' => $santri->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data santri',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}