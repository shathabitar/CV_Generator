<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PerformanceService
{
    /**
     * Monitor and log slow queries
     */
    public static function monitorSlowQueries(): void
    {
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                if ($query->time > 100) { // Log queries taking more than 100ms
                    Log::warning('Slow Query Detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms'
                    ]);
                }
            });
        }
    }

    /**
     * Cache frequently accessed data
     */
    public static function cacheSkills(): void
    {
        Cache::remember('all_skills', 3600, function () {
            return \App\Models\Skill::all()->groupBy('type');
        });
    }

    /**
     * Clear user-specific caches
     */
    public static function clearUserCache(int $userId): void
    {
        Cache::forget("user_cv_data_$userId");
    }
}