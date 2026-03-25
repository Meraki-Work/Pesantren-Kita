<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

    class RiwayatController extends Controller
    {
        public function index(Request $request)
        {
            // Get authenticated user
            $user = Auth::user();

            // Get filter parameters
            $month = $request->get('month');
            $year = $request->get('year');
            $status = $request->get('status');

            // Base query for absensi - menggunakan kolom yang benar (asumsi user_id)
            $absensiQuery = Absensi::where('user_id', $user->id);

            // Apply filters
            if ($month) {
                $absensiQuery->whereMonth('tanggal', $month);
            }

            if ($year) {
                $absensiQuery->whereYear('tanggal', $year);
            }

            if ($status) {
                $absensiQuery->where('status', $status);
            }

            // Order by date descending
            $absensiQuery->orderBy('tanggal', 'desc');

            // Get paginated results
            $absensi = $absensiQuery->paginate(10);

            // Format absensi data for display
            $formattedAbsensi = $absensi->map(function ($item) {
                return (object)[
                    'id_absensi' => $item->id,
                    'tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'hari' => Carbon::parse($item->tanggal)->translatedFormat('l'),
                    'jam' => $item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '-',
                    'jam_keluar' => $item->jam_keluar ? Carbon::parse($item->jam_keluar)->format('H:i') : '-',
                    'status' => $item->status,
                    'keterangan' => $item->keterangan ?? '-',
                    'is_auto_alfa' => $item->is_auto_alfa ?? false,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at
                ];
            });

            // Calculate statistics - menggunakan kolom yang benar
            $totalAbsensi = Absensi::where('user_id', $user->id)->count();

            // Count by status (all time)
            $hadir = Absensi::where('user_id', $user->id)
                ->where('status', 'Hadir')
                ->count();

            $izin = Absensi::where('user_id', $user->id)
                ->where('status', 'Izin')
                ->count();

            $sakit = Absensi::where('user_id', $user->id)
                ->where('status', 'Sakit')
                ->count();

            $alpa = Absensi::where('user_id', $user->id)
                ->where('status', 'Alpa')
                ->count();

            // Current month statistics - menggunakan kolom yang benar
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $hadirBulanIni = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->where('status', 'Hadir')
                ->count();

            $izinBulanIni = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->where('status', 'Izin')
                ->count();

            $sakitBulanIni = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->where('status', 'Sakit')
                ->count();

            $alpaBulanIni = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->where('status', 'Alpa')
                ->count();

            // Calculate working days in current month (excluding Sundays)
            $hariKerjaBulanIni = $this->getWorkingDaysInMonth($currentYear, $currentMonth);

            // Calculate attendance percentage
            $totalKehadiranBulanIni = $hadirBulanIni + $izinBulanIni + $sakitBulanIni;
            $persentaseKehadiran = $hariKerjaBulanIni > 0
                ? round(($totalKehadiranBulanIni / $hariKerjaBulanIni) * 100)
                : 0;

            // Get system logs for this user - PERBAIKAN: ganti nama tabel
            $systemLogs = SystemLog::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Log user activity (viewing history)
            $this->logActivity(
                'VIEW_HISTORY',
                'Melihat halaman riwayat absensi',
                $request,
                [
                    'month' => $month,
                    'year' => $year,
                    'status' => $status,
                    'total_absensi' => $totalAbsensi
                ]
            );

            // PERBAIKAN: Ganti 'absensi_system_logs' menjadi 'systemLogs'
            return view('pages.riwayat', compact(
                'absensi',
                'formattedAbsensi',
                'totalAbsensi',
                'hadir',
                'izin',
                'sakit',
                'alpa',
                'hadirBulanIni',
                'izinBulanIni',
                'sakitBulanIni',
                'alpaBulanIni',
                'hariKerjaBulanIni',
                'persentaseKehadiran',
                'systemLogs'  // Perbaikan: ini harusnya 'systemLogs'
            ));
        }

        /**
         * Get detailed information for a specific absensi
         */
        public function show($id)
        {
            try {
                // Ambil data absensi berdasarkan id dan user_id yang sedang login
                $absensi = Absensi::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                // Log activity
                $this->logActivity(
                    'VIEW_DETAIL',
                    'Melihat detail absensi',
                    request(),
                    ['absensi_id' => $id, 'tanggal' => $absensi->tanggal]
                );

                return response()->json([
                    'success' => true,
                    'data' => [
                        'id_absensi' => $absensi->id,
                        'tanggal' => $absensi->tanggal,
                        'tanggal_formatted' => Carbon::parse($absensi->tanggal)->translatedFormat('d F Y'),
                        'hari' => Carbon::parse($absensi->tanggal)->translatedFormat('l'),
                        'jam' => $absensi->jam_masuk ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '-',
                        'jam_keluar' => $absensi->jam_keluar ? Carbon::parse($absensi->jam_keluar)->format('H:i') : '-',
                        'status' => $absensi->status,
                        'keterangan' => $absensi->keterangan ?? '-',
                        'is_auto_alfa' => $absensi->is_auto_alfa ?? false,
                        'created_at_formatted' => Carbon::parse($absensi->created_at)->translatedFormat('d F Y H:i:s'),
                        'updated_at_formatted' => Carbon::parse($absensi->updated_at)->translatedFormat('d F Y H:i:s')
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }
        }

        /**
         * Update auto alfa status
         */
        public function update(Request $request, $id)
        {
            try {
                $request->validate([
                    'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
                    'keterangan' => 'nullable|string|max:500'
                ]);

                // Ambil data absensi berdasarkan id dan user_id yang sedang login
                $absensi = Absensi::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                // Check if it's auto alfa and today's date
                if (!$absensi->is_auto_alfa) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hanya absensi dengan status Auto Alfa yang dapat diupdate'
                    ], 400);
                }

                $oldStatus = $absensi->status;
                $oldKeterangan = $absensi->keterangan;

                // Update the absensi
                $absensi->status = $request->status;
                $absensi->keterangan = $request->keterangan;
                $absensi->is_auto_alfa = false;
                $absensi->jam_masuk = Carbon::now()->format('H:i:s');
                $absensi->save();

                // Log activity
                $this->logActivity(
                    'UPDATE_ABSENSI',
                    'Mengupdate status absensi dari ' . $oldStatus . ' menjadi ' . $request->status,
                    $request,
                    [
                        'absensi_id' => $id,
                        'old_status' => $oldStatus,
                        'new_status' => $request->status,
                        'old_keterangan' => $oldKeterangan,
                        'new_keterangan' => $request->keterangan
                    ]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Status absensi berhasil diperbarui'
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        }

        /**
         * Checkout function (update jam_keluar)
         */
        public function checkout($id)
        {
            try {
                // Ambil data absensi berdasarkan id dan user_id yang sedang login
                $absensi = Absensi::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                // Check if it's today
                if (!Carbon::parse($absensi->tanggal)->isToday()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Checkout hanya dapat dilakukan untuk hari ini'
                    ], 400);
                }

                // Check if already checked out
                if ($absensi->jam_keluar) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah melakukan checkout hari ini'
                    ], 400);
                }

                $oldJamKeluar = $absensi->jam_keluar;

                // Update checkout time
                $absensi->jam_keluar = Carbon::now()->format('H:i:s');
                $absensi->save();

                // Log activity
                $this->logActivity(
                    'CHECKOUT',
                    'Melakukan checkout pada jam ' . Carbon::now()->format('H:i:s'),
                    request(),
                    [
                        'absensi_id' => $id,
                        'checkout_time' => Carbon::now()->format('H:i:s'),
                        'jam_masuk' => $absensi->jam_masuk
                    ]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Checkout berhasil dilakukan'
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        }

        /**
         * Delete absensi record (admin only)
         */
        public function destroy($id)
        {
            try {
                // Check if user is admin or super
                if (!in_array(Auth::user()->role, ['Admin', 'Super'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki izin untuk menghapus data'
                    ], 403);
                }

                // Ambil data absensi berdasarkan id dan user_id yang sedang login
                $absensi = Absensi::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                // Prevent deletion of today's data
                if (Carbon::parse($absensi->tanggal)->isToday()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus data absensi hari ini'
                    ], 400);
                }

                $absensiData = $absensi->toArray();
                $absensi->delete();

                // Log activity
                $this->logActivity(
                    'DELETE_ABSENSI',
                    'Menghapus data absensi tanggal ' . Carbon::parse($absensiData['tanggal'])->translatedFormat('d F Y'),
                    request(),
                    ['deleted_data' => $absensiData]
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Data absensi berhasil dihapus'
                ]);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
        }

        /**
         * Export to Excel/CSV
         */
        public function export(Request $request)
        {
            $user = Auth::user();

            // Get filters
            $month = $request->get('month');
            $year = $request->get('year');
            $status = $request->get('status');

            // Ambil semua data absensi berdasarkan user_id yang sedang login
            $absensiQuery = Absensi::where('user_id', $user->id);

            if ($month) {
                $absensiQuery->whereMonth('tanggal', $month);
            }

            if ($year) {
                $absensiQuery->whereYear('tanggal', $year);
            }

            if ($status) {
                $absensiQuery->where('status', $status);
            }

            $absensi = $absensiQuery->orderBy('tanggal', 'desc')->get();

            // Log activity
            $this->logActivity(
                'EXPORT_DATA',
                'Mengekspor data absensi ke CSV',
                $request,
                [
                    'total_data' => $absensi->count(),
                    'month' => $month,
                    'year' => $year,
                    'status' => $status
                ]
            );

            // Prepare CSV data
            $filename = 'riwayat_absensi_' . $user->username . '_' . Carbon::now()->format('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($absensi) {
                $file = fopen('php://output', 'w');

                // Add UTF-8 BOM for Indonesian characters
                fputs($file, "\xEF\xBB\xBF");

                // Headers
                fputcsv($file, [
                    'Tanggal',
                    'Hari',
                    'Jam Masuk',
                    'Jam Keluar',
                    'Status',
                    'Keterangan',
                    'Auto Alfa',
                    'Dibuat Pada'
                ]);

                // Data rows
                foreach ($absensi as $item) {
                    fputcsv($file, [
                        Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                        Carbon::parse($item->tanggal)->translatedFormat('l'),
                        $item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '-',
                        $item->jam_keluar ? Carbon::parse($item->jam_keluar)->format('H:i') : '-',
                        $item->status,
                        $item->keterangan ?? '-',
                        $item->is_auto_alfa ? 'Ya' : 'Tidak',
                        Carbon::parse($item->created_at)->translatedFormat('d F Y H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        /**
         * Get statistics for chart
         */
        public function statistics()
        {
            $user = Auth::user();
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Ambil statistik berdasarkan user_id yang sedang login
            $statistics = [
                'hadir' => Absensi::where('user_id', $user->id)
                    ->whereMonth('tanggal', $currentMonth)
                    ->whereYear('tanggal', $currentYear)
                    ->where('status', 'Hadir')
                    ->count(),
                'izin' => Absensi::where('user_id', $user->id)
                    ->whereMonth('tanggal', $currentMonth)
                    ->whereYear('tanggal', $currentYear)
                    ->where('status', 'Izin')
                    ->count(),
                'sakit' => Absensi::where('user_id', $user->id)
                    ->whereMonth('tanggal', $currentMonth)
                    ->whereYear('tanggal', $currentYear)
                    ->where('status', 'Sakit')
                    ->count(),
                'alpa' => Absensi::where('user_id', $user->id)
                    ->whereMonth('tanggal', $currentMonth)
                    ->whereYear('tanggal', $currentYear)
                    ->where('status', 'Alpa')
                    ->count()
            ];

            return response()->json($statistics);
        }

        /**
         * Get system logs for current user
         */
        public function getLogs(Request $request)
        {
            $limit = $request->get('limit', 50);

            $logs = SystemLog::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'description' => $log->description,
                        'ip_address' => $log->ip_address,
                        'user_agent' => $log->user_agent,
                        'data' => is_string($log->data) ? json_decode($log->data, true) : $log->data,
                        'created_at' => Carbon::parse($log->created_at)->translatedFormat('d F Y H:i:s')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        }

        /**
         * Helper function to log user activity
         */
        private function logActivity($action, $description, $request, $data = null)
        {
            try {
                SystemLog::create([
                    'user_id' => Auth::id(),
                    'username' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => $action,
                    'description' => $description,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'data' => $data ? json_encode($data) : null,
                    'created_at' => Carbon::now()
                ]);
            } catch (\Exception $e) {
                // Silent fail - don't let logging errors break the main functionality
                Log::error('Failed to log activity: ' . $e->getMessage());
            }
        }

        /**
         * Helper function to get working days in a month (excluding Sundays)
         */
        private function getWorkingDaysInMonth($year, $month)
        {
            $date = Carbon::create($year, $month, 1);
            $daysInMonth = $date->daysInMonth;
            $workingDays = 0;

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $currentDate = Carbon::create($year, $month, $i);
                // Exclude Sundays (day of week 0 = Sunday)
                if ($currentDate->dayOfWeek != Carbon::SUNDAY) {
                    $workingDays++;
                }
            }

            return $workingDays;
        }
    }
