<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FileSecurityService
{
    /**
     * Validate file security
     */
    public function validateFile(UploadedFile $file): bool
    {
        $this->validateFileSize($file);
        $this->validateFileExtension($file);
        $this->validateMimeType($file);
        $this->scanFileContent($file);

        return true;
    }

    /**
     * Validate file size
     */
    private function validateFileSize(UploadedFile $file): void
    {
        $maxSize = config('security.files.max_size');

        if ($file->getSize() > $maxSize) {
            throw ValidationException::withMessages([
                'file' => ['File size exceeds maximum allowed size of ' . $this->formatBytes($maxSize)]
            ]);
        }
    }

    /**
     * Validate file extension
     */
    private function validateFileExtension(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = config('security.files.allowed_extensions');
        $blockedExtensions = config('security.files.blocked_extensions');

        if (in_array($extension, $blockedExtensions)) {
            throw ValidationException::withMessages([
                'file' => ['File type is not allowed for security reasons.']
            ]);
        }

        if (!in_array($extension, $allowedExtensions)) {
            throw ValidationException::withMessages([
                'file' => ['File type is not supported.']
            ]);
        }
    }

    /**
     * Validate MIME type
     */
    private function validateMimeType(UploadedFile $file): void
    {
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        // Basic MIME type validation
        $allowedMimeTypes = [
            'pdf' => ['application/pdf'],
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'txt' => ['text/plain'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'zip' => ['application/zip'],
        ];

        if (isset($allowedMimeTypes[$extension])) {
            if (!in_array($mimeType, $allowedMimeTypes[$extension])) {
                throw ValidationException::withMessages([
                    'file' => ['File type does not match its content.']
                ]);
            }
        }
    }

    /**
     * Scan file content for malicious patterns
     */
    private function scanFileContent(UploadedFile $file): void
    {
        $content = file_get_contents($file->getRealPath());

        // Check for suspicious patterns
        $maliciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/eval\s*\(/i',
            '/base64_decode\s*\(/i',
            '/shell_exec\s*\(/i',
            '/system\s*\(/i',
            '/exec\s*\(/i',
            '/passthru\s*\(/i',
            '/file_get_contents\s*\(/i',
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                Log::warning('Malicious file upload attempt', [
                    'file' => $file->getClientOriginalName(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                throw ValidationException::withMessages([
                    'file' => ['File contains suspicious content and cannot be uploaded.']
                ]);
            }
        }
    }

    /**
     * Generate secure filename
     */
    public function generateSecureFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $hash = hash('sha256', $file->getClientOriginalName() . time() . uniqid());

        return substr($hash, 0, 32) . '.' . $extension;
    }

    /**
     * Encrypt file content
     */
    public function encryptFile(string $filePath): string
    {
        if (!config('security.files.encrypt_files')) {
            return $filePath;
        }

        $content = file_get_contents($filePath);
        $encrypted = encrypt($content);

        $encryptedPath = $filePath . '.enc';
        file_put_contents($encryptedPath, $encrypted);

        // Remove original file
        unlink($filePath);

        return $encryptedPath;
    }

    /**
     * Decrypt file content
     */
    public function decryptFile(string $encryptedPath): string
    {
        if (!str_ends_with($encryptedPath, '.enc')) {
            return $encryptedPath;
        }

        $encryptedContent = file_get_contents($encryptedPath);
        $decryptedContent = decrypt($encryptedContent);

        $tempPath = sys_get_temp_dir() . '/' . uniqid() . '_decrypted';
        file_put_contents($tempPath, $decryptedContent);

        return $tempPath;
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
