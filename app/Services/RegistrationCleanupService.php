<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationCleanupService
{
    /**
     * Cleanup expired pending registrations
     */
    public function cleanupExpiredRegistrations($expiryMinutes = 15)
    {
        $deletedCount = 0;
        $ponpesDeletedCount = 0;
        
        try {
            DB::beginTransaction();
            
            // Ambil semua user pending yang expired
            $expiredUsers = User::expiredPending($expiryMinutes)->get();
            
            foreach ($expiredUsers as $user) {
                // Log audit
                $this->logAudit(
                    $user->id_user,
                    $user->ponpes_id,
                    $user->email,
                    'expired',
                    'Auto-cleanup after ' . $expiryMinutes . ' minutes'
                );
                
                // Hapus user
                $user->delete();
                $deletedCount++;
                
                // Cek apakah ponpes baru dan tidak ada user lain
                $otherUsers = User::where('ponpes_id', $user->ponpes_id)->count();
                
                if ($otherUsers === 0) {
                    // Cek kapan ponpes dibuat
                    $ponpes = DB::table('ponpes')->where('id_ponpes', $user->ponpes_id)->first();
                    
                    if ($ponpes && $ponpes->created_at > now()->subHours(24)) {
                        // Hapus subscription terlebih dahulu
                        DB::table('subscriptions')->where('ponpes_id', $user->ponpes_id)->delete();
                        
                        // Hapus ponpes
                        DB::table('ponpes')->where('id_ponpes', $user->ponpes_id)->delete();
                        
                        $ponpesDeletedCount++;
                        
                        Log::info("Deleted new ponpes: {$user->ponpes_id}");
                    }
                }
            }
            
            DB::commit();
            
            Log::info("Cleanup completed: {$deletedCount} users, {$ponpesDeletedCount} ponpes deleted");
            
            return [
                'users_deleted' => $deletedCount,
                'ponpes_deleted' => $ponpesDeletedCount,
                'status' => 'success'
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cleanup failed: ' . $e->getMessage());
            
            return [
                'users_deleted' => 0,
                'ponpes_deleted' => 0,
                'status' => 'failed',
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Cleanup failed OTP attempts (max 3 attempts)
     */
    public function cleanupFailedAttempts($email)
    {
        try {
            $user = User::where('email', $email)->first();
            
            if (!$user || $user->status !== 'pending') {
                return false;
            }
            
            // Update attempts count
            $attempts = $user->registration_attempts ?? 0;
            $attempts++;
            
            $user->update([
                'registration_attempts' => $attempts
            ]);
            
            // Jika sudah 3x salah, hapus
            if ($attempts >= 3) {
                $this->logAudit(
                    $user->id_user,
                    $user->ponpes_id,
                    $email,
                    'failed',
                    'Max OTP attempts exceeded (3 attempts)'
                );
                
                return $this->deleteRegistration($user);
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed attempts cleanup error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete registration data
     */
    private function deleteRegistration(User $user)
    {
        try {
            DB::beginTransaction();
            
            $ponpesId = $user->ponpes_id;
            $userId = $user->id_user;
            
            // Hapus user
            $user->delete();
            
            // Cek apakah ponpes baru dan tidak ada user lain
            $otherUsers = User::where('ponpes_id', $ponpesId)->count();
            
            if ($otherUsers === 0) {
                // Hapus subscription
                DB::table('subscriptions')->where('ponpes_id', $ponpesId)->delete();
                
                // Hapus ponpes jika dibuat dalam 24 jam terakhir
                DB::table('ponpes')
                    ->where('id_ponpes', $ponpesId)
                    ->where('created_at', '>', now()->subHours(24))
                    ->delete();
            }
            
            DB::commit();
            
            Log::info("Registration deleted after failed attempts: {$userId}");
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete registration error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log audit trail
     */
    private function logAudit($userId, $ponpesId, $email, $status, $reason = null)
    {
        try {
            DB::table('registration_audits')->insert([
                'user_id' => $userId,
                'ponpes_id' => $ponpesId,
                'email' => $email,
                'status' => $status,
                'reason' => $reason,
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Audit log error: ' . $e->getMessage());
        }
    }
}