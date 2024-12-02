<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use ZipArchive;

class BackupDatabaseAndFiles
{
    public function __construct()
    {
        // Initialize any required dependencies
    }

    public function handle()
    {
        try {
            // Set paths and filenames
            $backupPath = storage_path('backups');
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $dbBackupFile = "{$backupPath}/database_backup_{$timestamp}.sql";
            $htmlBackupFile = "{$backupPath}/html_backup_{$timestamp}.zip";
            $s3Folder = "backups/{$timestamp}";

            // Ensure the backup directory exists
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // 1. Backup Database
            $this->backupDatabase($dbBackupFile);

            // 2. Backup HTML Folder (Assuming /var/www/html is your HTML directory)
            // $this->backupHtmlFolder($htmlBackupFile, '/var/www/html');

            // 3. Upload to S3
            $this->uploadToS3($dbBackupFile, "{$s3Folder}/database_backup.sql");
            // $this->uploadToS3($htmlBackupFile, "{$s3Folder}/html_backup.zip");

            // 4. Delete Temporary Files
            File::delete($dbBackupFile);
            // File::delete($htmlBackupFile);

            Log::info('Backup completed successfully.');
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
        }
    }

    private function backupDatabase($dbBackupFile)
    {
        $dbConfig = config('database.connections.mysql');
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['database']),
            escapeshellarg($dbBackupFile)
        );

        $result = exec($command);
        if (!file_exists($dbBackupFile)) {
            throw new \Exception("Database backup failed. Command: $command");
        }
    }

    private function backupHtmlFolder($zipFilePath, $sourceFolder)
    {
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($sourceFolder),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($sourceFolder) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        } else {
            throw new \Exception("Failed to create zip file for HTML backup.");
        }
    }

    private function uploadToS3($localPath, $s3Path)
    {
        if (file_put_contents($s3Path, fopen($localPath, 'r+'))) {
            Log::info("File uploaded to S3: {$s3Path}");
        } else {
            throw new \Exception("Failed to upload file to S3: {$s3Path}");
        }
    }
}
