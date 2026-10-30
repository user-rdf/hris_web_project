<?php

namespace App\Services;

class GeoService
{
    /**
     * Rumus Haversine: jarak dua titik di permukaan bumi (dalam meter).
     * Bumi bulat, jadi jarak tidak bisa dihitung dengan Pythagoras biasa.
     */
    public function distanceInMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}