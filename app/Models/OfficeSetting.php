<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeSetting extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude', 'radius_m', 'work_start_time'];

    protected function casts(): array
    {
        return [
            'latitude'  => 'float',
            'longitude' => 'float',
            'radius_m'  => 'integer',
        ];
    }

    /** Hanya ada satu kantor di aplikasi ini. */
    public static function current(): ?self
    {
        return static::first();
    }
}
