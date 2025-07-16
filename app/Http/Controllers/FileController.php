<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\FileSecurityService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    private FileSecurityService $fileSecurityService;
    private ActivityLogService $activityLogService;

    public function __construct(FileSecurityService $fileSecurityService, ActivityLogService $activityLogService)
    {
        $this->fileSecurityService = $fileSecurityService;
        $this->activityLogService = $activityLogService;
    }
    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        // Rate limiting
        $key = 'file_upload:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, config('security.rate_limiting.uploads_per_hour'))) {
            $this->activityLogService->logSecurityEvent('rate_limit_exceeded', [
                'action' => 'file_upload',
                'limit' => config('security.rate_limiting.uploads_per_hour')
            ]);

            return back()->withErrors(['file' => 'Too many upload attempts. Please try again later.']);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:5120', // Max 5MB - Conservative for free hosting
            'password' => 'nullable|string|min:4',
            'expires_in_days' => 'nullable|integer|min:1|max:30',
            'max_downloads' => 'nullable|integer|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $uploadedFile = $request->file('file');

        // Security validation
        try {
            $this->fileSecurityService->validateFile($uploadedFile);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->activityLogService->logSecurityEvent('malicious_file_upload', [
                'filename' => $uploadedFile->getClientOriginalName(),
                'mime_type' => $uploadedFile->getMimeType(),
                'errors' => $e->errors()
            ]);

            return back()->withErrors($e->errors())->withInput();
        }

        RateLimiter::hit($key);

        $uuid = \Illuminate\Support\Str::uuid();

        // Generate secure filename
        $secureFilename = $this->fileSecurityService->generateSecureFilename($uploadedFile);

        // Store file
        $storagePath = "uploads/{$uuid}/{$secureFilename}";
        $uploadedFile->storeAs("uploads/{$uuid}", $secureFilename, 'public');

        // Encrypt file if enabled
        $fullStoragePath = storage_path("app/public/uploads/{$uuid}/{$secureFilename}");
        $encryptedPath = $this->fileSecurityService->encryptFile($fullStoragePath);

        // Update storage path if file was encrypted
        if (str_ends_with($encryptedPath, '.enc')) {
            $storagePath .= '.enc';
        }

        // Calculate expiration date
        $expiresAt = null;
        if ($request->expires_in_days) {
            $expiresAt = now()->addDays($request->expires_in_days);
        }

        // Create file record
        $file = File::create([
            'uuid' => $uuid,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'storage_path' => $storagePath,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'password' => $request->password,
            'max_downloads' => $request->max_downloads,
            'expires_at' => $expiresAt,
            'user_id' => auth()->id(), // Associate with authenticated user
        ]);

        // Log activity
        $this->activityLogService->log('file_upload', [
            'file_uuid' => $uuid,
            'filename' => $uploadedFile->getClientOriginalName(),
            'size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getMimeType()
        ]);

        $downloadUrl = route('file.download', $file->uuid);

        return view('upload-success', compact('file', 'downloadUrl'));
    }

    public function show($uuid)
    {
        // Debug: Log the request details
        Log::info('Download show method called', [
            'uuid' => $uuid,
            'user_agent' => request()->header('User-Agent'),
            'method' => request()->method(),
            'url' => request()->fullUrl()
        ]);

        $file = File::where('uuid', $uuid)->firstOrFail();

        if ($file->isExpired()) {
            abort(404, 'File has expired or reached download limit.');
        }

        if ($file->hasPassword()) {
            Log::info('File has password, showing password form');
            return view('download-password', compact('file'));
        }

        Log::info('File has no password, starting download');
        return $this->downloadFile($file);
    }

    public function download(Request $request, $uuid)
    {
        // Rate limiting for downloads
        $key = 'file_download:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, config('security.rate_limiting.downloads_per_hour'))) {
            $this->activityLogService->logSecurityEvent('rate_limit_exceeded', [
                'action' => 'file_download',
                'limit' => config('security.rate_limiting.downloads_per_hour')
            ]);

            abort(429, 'Too many download attempts. Please try again later.');
        }

        $file = File::where('uuid', $uuid)->firstOrFail();

        if ($file->isExpired()) {
            abort(404, 'File has expired or reached download limit.');
        }

        if ($file->hasPassword()) {
            $request->validate([
                'password' => 'required|string',
            ]);

            if (!$file->checkPassword($request->password)) {
                $this->activityLogService->log('file_download_failed', [
                    'file_uuid' => $uuid,
                    'reason' => 'invalid_password'
                ]);

                return back()->withErrors(['password' => 'Invalid password.']);
            }
        }

        RateLimiter::hit($key);

        return $this->downloadFile($file);
    }

    private function downloadFile(File $file)
    {
        Log::info('downloadFile method called', [
            'file_uuid' => $file->uuid,
            'storage_path' => $file->storage_path,
            'original_name' => $file->original_name,
            'user_agent' => request()->header('User-Agent')
        ]);

        $storagePath = $file->storage_path;

        // Decrypt file if encrypted
        if (str_ends_with($storagePath, '.enc')) {
            $fullPath = storage_path("app/public/{$storagePath}");
            $decryptedPath = $this->fileSecurityService->decryptFile($fullPath);

            // Log download activity
            $this->activityLogService->log('file_download', [
                'file_uuid' => $file->uuid,
                'filename' => $file->original_name,
                'download_count' => $file->downloads + 1
            ]);

            // Increment download count
            $file->increment('downloads');

            return response()->download($decryptedPath, $file->original_name)->deleteFileAfterSend();
        }

        if (!Storage::disk('public')->exists($storagePath)) {
            abort(404, 'File not found.');
        }

        // Log download activity
        $this->activityLogService->log('file_download', [
            'file_uuid' => $file->uuid,
            'filename' => $file->original_name,
            'download_count' => $file->downloads + 1
        ]);

        // Increment download count
        $file->increment('downloads');

        // Get the full file path
        $fullPath = storage_path('app/public/' . $storagePath);
        
        // Mobile-friendly download with proper headers
        return $this->createMobileFriendlyDownload($fullPath, $file->original_name, $file->mime_type);
    }

    /**
     * Create a mobile-friendly download response
     */
    private function createMobileFriendlyDownload($filePath, $fileName, $mimeType)
    {
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        $fileSize = filesize($filePath);
        $fileName = basename($fileName);
        
        // Clean the filename for mobile compatibility
        $fileName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $fileName);
        
        // Detect mobile user agent
        $userAgent = request()->header('User-Agent', '');
        $isMobile = preg_match('/Mobile|Android|iPhone|iPad/', $userAgent);
        
        $headers = [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
        
        // Additional mobile-specific headers
        if ($isMobile) {
            $headers['X-Content-Type-Options'] = 'nosniff';
            $headers['X-Download-Options'] = 'noopen';
        }

        // Use response()->file() for better mobile compatibility
        return response()->file($filePath, $headers);
    }

    public function dashboard()
    {
        $files = File::where('user_id', auth()->id())
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        $stats = [
            'total_files' => File::where('user_id', auth()->id())->count(),
            'total_downloads' => File::where('user_id', auth()->id())->sum('downloads'),
            'active_files' => File::where('user_id', auth()->id())
                                  ->where(function($query) {
                                      $query->where('expires_at', '>', now())
                                            ->orWhereNull('expires_at');
                                  })
                                  ->where(function($query) {
                                      $query->whereRaw('downloads < max_downloads')
                                            ->orWhereNull('max_downloads');
                                  })->count(),
            'storage_used' => File::where('user_id', auth()->id())->sum('size'),
        ];

        return view('dashboard', compact('files', 'stats'));
    }

    public function destroy($uuid)
    {
        $file = File::where('uuid', $uuid)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        // Delete file from storage
        if (Storage::disk('public')->exists($file->storage_path)) {
            Storage::disk('public')->delete($file->storage_path);

            // Try to delete the directory if it's empty
            $directory = dirname($file->storage_path);
            $files = Storage::disk('public')->files($directory);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
        }

        // Delete database record
        $file->delete();

        return redirect()->route('dashboard')->with('status', 'File deleted successfully.');
    }
}
