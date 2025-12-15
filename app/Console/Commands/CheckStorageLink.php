<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:check {--fix : Attempt to fix storage link issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check storage link status and optionally fix issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Storage Link Diagnostics ===');
        $this->newLine();
        
        // Check paths
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');
        
        $this->info('Public storage path: ' . $publicPath);
        $this->info('Storage app/public path: ' . $storagePath);
        $this->newLine();
        
        // Check if storage/app/public exists
        if (File::isDirectory($storagePath)) {
            $this->info('✅ storage/app/public directory exists');
            
            // List contents
            $files = File::files($storagePath);
            $dirs = File::directories($storagePath);
            $this->info('   - Files: ' . count($files));
            $this->info('   - Subdirectories: ' . count($dirs));
            
            foreach ($dirs as $dir) {
                $dirName = basename($dir);
                $fileCount = count(File::allFiles($dir));
                $this->info("   - /{$dirName}/: {$fileCount} files");
            }
        } else {
            $this->error('❌ storage/app/public directory does not exist');
        }
        
        $this->newLine();
        
        // Check if public/storage symlink exists
        if (is_link($publicPath)) {
            $target = readlink($publicPath);
            $this->info('✅ public/storage is a symlink');
            $this->info('   - Target: ' . $target);
            
            // Check if target exists
            if (File::isDirectory($publicPath)) {
                $this->info('✅ Symlink target is accessible');
            } else {
                $this->error('❌ Symlink target is NOT accessible');
            }
        } elseif (File::isDirectory($publicPath)) {
            $this->warn('⚠️  public/storage exists but is a regular directory (not a symlink)');
            $this->info('   This may work on some hosts but is not standard Laravel setup.');
            
            // Check if it has files
            $files = File::allFiles($publicPath);
            $this->info('   - Total files: ' . count($files));
        } else {
            $this->error('❌ public/storage does not exist');
            
            if ($this->option('fix')) {
                $this->info('Attempting to create storage link...');
                
                try {
                    $this->call('storage:link');
                    $this->info('✅ Storage link created successfully');
                } catch (\Exception $e) {
                    $this->error('Failed to create symlink: ' . $e->getMessage());
                    $this->warn('On shared hosting, you may need to:');
                    $this->warn('1. Create the symlink via cPanel File Manager');
                    $this->warn('2. Or copy files manually from storage/app/public to public/storage');
                }
            } else {
                $this->info('Run with --fix flag to attempt to fix this issue');
            }
        }
        
        $this->newLine();
        
        // Check jadwal directory specifically
        $jadwalPath = storage_path('app/public/jadwal');
        $publicJadwalPath = public_path('storage/jadwal');
        
        $this->info('=== Jadwal Directory Check ===');
        
        if (File::isDirectory($jadwalPath)) {
            $jadwalFiles = File::files($jadwalPath);
            $this->info('✅ storage/app/public/jadwal exists');
            $this->info('   - Files: ' . count($jadwalFiles));
            
            foreach ($jadwalFiles as $file) {
                $this->info('   - ' . $file->getFilename() . ' (' . number_format($file->getSize() / 1024, 2) . ' KB)');
            }
        } else {
            $this->warn('⚠️  storage/app/public/jadwal does not exist');
        }
        
        if (File::isDirectory($publicJadwalPath) || is_link(dirname($publicJadwalPath))) {
            $this->info('✅ public/storage/jadwal is accessible via symlink');
        } else {
            $this->error('❌ public/storage/jadwal is NOT accessible');
        }
        
        $this->newLine();
        $this->info('=== Recommendations ===');
        
        if (!is_link($publicPath) && !File::isDirectory($publicPath)) {
            $this->error('Storage link is missing. Please run one of the following:');
            $this->info('1. php artisan storage:link');
            $this->info('2. On CloudPanel/cPanel: Create symlink manually');
        } else {
            $this->info('Storage configuration appears to be correct.');
        }
        
        return Command::SUCCESS;
    }
}
