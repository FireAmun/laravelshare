<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Only allow admin users
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->is_admin) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $yesterday = now()->subDay();
        $lastWeek = now()->subWeek();
        $lastMonth = now()->subMonth();

        // Get comprehensive statistics
        $stats = [
            // User Statistics
            'totalUsers' => User::count(),
            'activeUsers' => User::where('last_login_at', '>=', $lastWeek)->count(),
            'newUsersToday' => User::whereDate('created_at', today())->count(),
            'adminUsers' => User::where('is_admin', true)->count(),

            // File Statistics
            'totalFiles' => File::count(),
            'filesToday' => File::whereDate('created_at', today())->count(),
            'filesThisWeek' => File::where('created_at', '>=', $lastWeek)->count(),
            'expiredFiles' => File::where('expires_at', '<', now())->count(),

            // Security Statistics
            'securityEvents' => $this->getSecurityEventsCount($yesterday),
            'failedLogins' => $this->getFailedLoginsCount($yesterday),
            'downloadsToday' => $this->getDownloadsCount(today()),
            'uploadsToday' => $this->getUploadsCount(today()),
        ];

        // Get storage usage
        $storageStats = $this->getStorageStatistics();

        // Get recent activities
        $recentUsers = User::latest()->limit(10)->get();
        $recentFiles = File::with('user')->latest()->limit(10)->get();
        $recentSecurityEvents = $this->getRecentSecurityEvents();
        $recentActivity = $this->getRecentActivity();

        // Get charts data
        $chartsData = [
            'userRegistrations' => $this->getUserRegistrationsChart(),
            'fileUploads' => $this->getFileUploadsChart(),
            'downloads' => $this->getDownloadsChart(),
            'storageUsage' => $storageStats,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'storageStats',
            'recentUsers',
            'recentFiles',
            'recentSecurityEvents',
            'recentActivity',
            'chartsData'
        ));
    }

    public function users()
    {
        $users = User::withCount(['files'])
            ->with(['files' => function($query) {
                $query->latest()->limit(3);
            }])
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function files()
    {
        $files = File::with('user')
            ->latest()
            ->paginate(20);

        $storageStats = $this->getStorageStatistics();

        return view('admin.files', compact('files', 'storageStats'));
    }

    public function deleteFile($uuid)
    {
        $file = File::where('uuid', $uuid)->firstOrFail();

        // Delete physical file
        if (Storage::disk('public')->exists($file->storage_path)) {
            Storage::disk('public')->delete($file->storage_path);
        }

        // Log the action
        $this->logSecurityEvent('file_deleted_by_admin', [
            'file_uuid' => $file->uuid,
            'original_name' => $file->original_name,
            'admin_id' => auth()->id(),
        ]);

        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $this->logSecurityEvent('user_status_changed', [
            'user_id' => $user->id,
            'new_status' => $user->is_active ? 'active' : 'inactive',
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function makeAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->is_admin = true;
        $user->save();

        $this->logSecurityEvent('user_made_admin', [
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'User promoted to admin successfully.');
    }

    public function removeAdmin($id)
    {
        $user = User::findOrFail($id);

        // Prevent removing the last admin
        if (User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot remove the last admin user.');
        }

        $user->is_admin = false;
        $user->save();

        $this->logSecurityEvent('admin_removed', [
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'Admin privileges removed successfully.');
    }

    public function cleanupExpiredFiles()
    {
        $expiredFiles = File::where('expires_at', '<', now())->get();
        $deletedCount = 0;

        foreach ($expiredFiles as $file) {
            if (Storage::disk('public')->exists($file->storage_path)) {
                Storage::disk('public')->delete($file->storage_path);
            }
            $file->delete();
            $deletedCount++;
        }

        $this->logSecurityEvent('expired_files_cleanup', [
            'deleted_count' => $deletedCount,
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', "Cleaned up {$deletedCount} expired files.");
    }

    public function systemSettings()
    {
        $settings = [
            'max_file_size' => config('filesystems.max_file_size', '5MB'),
            'allowed_extensions' => config('filesystems.allowed_extensions', []),
            'max_downloads' => config('app.max_downloads', 100),
            'default_expiry_days' => config('app.default_expiry_days', 7),
        ];

        return view('admin.settings', compact('settings'));
    }

    // Helper Methods
    private function getSecurityEventsCount($since)
    {
        try {
            return DB::table('security_logs')
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getFailedLoginsCount($since)
    {
        try {
            return DB::table('activity_logs')
                ->where('action', 'login_failed')
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDownloadsCount($since)
    {
        try {
            return DB::table('activity_logs')
                ->where('action', 'file_download')
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getUploadsCount($since)
    {
        try {
            return DB::table('activity_logs')
                ->where('action', 'file_upload')
                ->where('created_at', '>=', $since)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getStorageStatistics()
    {
        $files = File::all();
        $totalSize = $files->sum('size');
        $fileTypes = $files->groupBy('mime_type')->map->count();

        return [
            'totalFiles' => $files->count(),
            'totalSize' => $totalSize,
            'totalSizeFormatted' => $this->formatBytes($totalSize),
            'fileTypes' => $fileTypes,
            'averageFileSize' => $files->count() > 0 ? $totalSize / $files->count() : 0,
        ];
    }

    private function getRecentSecurityEvents()
    {
        try {
            return DB::table('security_logs')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getRecentActivity()
    {
        try {
            return DB::table('activity_logs')
                ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
                ->select('activity_logs.*', 'users.name as user_name')
                ->orderBy('activity_logs.created_at', 'desc')
                ->limit(20)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getUserRegistrationsChart()
    {
        $data = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();        // If no data, create sample data for the last 7 days
        if ($data->isEmpty()) {
            $labels = [];
            $chartData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('M j');
                $chartData[] = rand(0, 5); // Random sample data
            }

            return [
                'labels' => $labels,
                'data' => $chartData,
            ];
        }

        return [
            'labels' => $data->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('M j');
            })->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }

    private function getFileUploadsChart()
    {
        $data = File::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();        // If no data, create sample data for the last 7 days
        if ($data->isEmpty()) {
            $labels = [];
            $chartData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('M j');
                $chartData[] = rand(0, 3); // Random sample data
            }

            return [
                'labels' => $labels,
                'data' => $chartData,
            ];
        }

        return [
            'labels' => $data->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('M j');
            })->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }

    private function getDownloadsChart()
    {
        try {
            $data = DB::table('activity_logs')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('action', 'file_download')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'labels' => $data->pluck('date')->toArray(),
                'data' => $data->pluck('count')->toArray(),
            ];
        } catch (\Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    private function logSecurityEvent($action, $data = [])
    {
        try {
            DB::table('security_logs')->insert([
                'action' => $action,
                'data' => json_encode($data),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log to Laravel log if security_logs table doesn't exist
            Log::info("Security Event: {$action}", $data);
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
