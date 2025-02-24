<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    protected $appends = ['formatted_description']; // Tambahkan ini

    public function getFormattedDescriptionAttribute()
    {
        $description = $this->attributes['description'] ?? null;

        if (!$description) {
            return null;
        }

        return ucfirst(str_replace(
            ['created', 'updated', 'deleted'],
            ['dibuat', 'diperbarui', 'dihapus'],
            $description
        ));
    }
}
