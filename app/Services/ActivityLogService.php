<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ActivityLogService
{
    /**
     * Log user activity
     */
    public function log(string $action, array $data = [], ?User $user = null): void
    {
        $user = $user ?: auth()->user();

        $logData = [
            'user_id' => $user?->id,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => json_encode($data),
            'created_at' => now(),
        ];

        DB::table('activity_logs')->insert($logData);

        // Also log to Laravel logs for critical actions
        if (in_array($action, ['file_upload', 'file_download', 'login_failed', 'password_changed'])) {
            Log::info("User activity: {$action}", $logData);
        }
    }

    /**
     * Log security event
     */
    public function logSecurityEvent(string $event, array $data = []): void
    {
        $logData = [
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => json_encode($data),
            'severity' => $this->getSeverity($event),
            'created_at' => now(),
        ];

        DB::table('security_logs')->insert($logData);

        Log::warning("Security event: {$event}", $logData);
    }

    /**
     * Get event severity
     */
    private function getSeverity(string $event): string
    {
        $highSeverity = [
            'malicious_file_upload',
            'rate_limit_exceeded',
            'brute_force_attempt',
            'suspicious_activity'
        ];

        return in_array($event, $highSeverity) ? 'high' : 'medium';
    }

    /**
     * Check for suspicious activity
     */
    public function checkSuspiciousActivity(User $user): bool
    {
        $recentAttempts = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->where('action', 'login_failed')
            ->where('created_at', '>', now()->subMinutes(15))
            ->count();

        if ($recentAttempts >= config('security.rate_limiting.login_attempts')) {
            $this->logSecurityEvent('brute_force_attempt', [
                'user_id' => $user->id,
                'attempts' => $recentAttempts
            ]);
            return true;
        }

        return false;
    }
}
