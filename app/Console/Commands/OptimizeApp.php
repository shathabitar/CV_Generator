<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OptimizeApp extends Command
{
    protected $signature = 'app:optimize';
    protected $description = 'Optimize the CV Generator application';

    public function handle(): int
    {
        $this->info('🚀 Optimizing CV Generator Application...');

        // Clear all caches
        $this->info('📦 Clearing caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        // Optimize for production
        if (app()->environment('production')) {
            $this->info('⚡ Optimizing for production...');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        }

        // Create storage link if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            $this->info('🔗 Creating storage link...');
            Artisan::call('storage:link');
        }

        $this->info('✅ Application optimized successfully!');
        
        return self::SUCCESS;
    }
}