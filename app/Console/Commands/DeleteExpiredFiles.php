<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:delete-expired {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired files and their database records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Scanning for expired files...');

        // Find expired files
        $expiredFiles = File::where(function ($query) {
            $query->where('expires_at', '<', now())
                  ->orWhereRaw('downloads >= max_downloads AND max_downloads IS NOT NULL');
        })->get();

        if ($expiredFiles->isEmpty()) {
            $this->info('No expired files found.');
            return 0;
        }

        $this->info("Found {$expiredFiles->count()} expired files:");

        $deletedCount = 0;
        $errorCount = 0;

        foreach ($expiredFiles as $file) {
            $reason = '';
            if ($file->expires_at && $file->expires_at < now()) {
                $reason = "expired on {$file->expires_at->format('Y-m-d')}";
            } elseif ($file->max_downloads && $file->downloads >= $file->max_downloads) {
                $reason = "reached download limit ({$file->downloads}/{$file->max_downloads})";
            }

            $this->line("- {$file->original_name} ({$reason})");

            if (!$dryRun) {
                try {
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
                    $deletedCount++;

                } catch (\Exception $e) {
                    $this->error("Failed to delete {$file->original_name}: {$e->getMessage()}");
                    $errorCount++;
                }
            }
        }

        if ($dryRun) {
            $this->warn('DRY RUN: No files were actually deleted. Run without --dry-run to delete these files.');
        } else {
            $this->info("Successfully deleted {$deletedCount} files.");
            if ($errorCount > 0) {
                $this->warn("Failed to delete {$errorCount} files.");
            }
        }

        return 0;
    }
}
