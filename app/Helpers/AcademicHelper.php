<?php

namespace App\Helpers;

use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Cache;

class AcademicHelper
{
    /**
     * Get the currently active Academic Year ID.
     */
    public static function activeYearId()
    {
        return Cache::remember('active_tahun_ajaran_id', 3600, function () {
            $active = TahunAjaran::where('is_active', true)->first();
            return $active ? $active->id : null;
        });
    }

    /**
     * Get the currently active Academic Year Name (e.g. "2024/2025").
     */
    public static function activeYearName()
    {
        return Cache::remember('active_tahun_ajaran_name', 3600, function () {
            $active = TahunAjaran::where('is_active', true)->first();
            return $active ? $active->nama : date('Y') . '/' . (date('Y') + 1);
        });
    }

    /**
     * Get the currently active Academic Year Object.
     */
    public static function activeYear()
    {
        return Cache::remember('active_tahun_ajaran', 3600, function () {
            return TahunAjaran::where('is_active', true)->first();
        });
    }
}
