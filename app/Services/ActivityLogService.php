<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Record a new activity log.
     *
     * @param string $type The action type (e.g. 'siswa_update', 'tahun_pelajaran_active')
     * @param string $description Human readable description of the action
     * @param array $properties Extra metadata or changes (old vs new)
     * @return Activity|null
     */
    public static function log(string $type, string $description, array $properties = [])
    {
        if (!Auth::check()) {
            return null;
        }

        return Activity::create([
            'user_id' => Auth::id(),
            'type' => $type,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
