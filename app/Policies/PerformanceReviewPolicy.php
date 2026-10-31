<?php

namespace App\Policies;

use App\Models\PerformanceReview;
use App\Models\User;

class PerformanceReviewPolicy
{
    /** Boleh mengubah/menghapus penilaian ini? */
    public function manage(User $user, PerformanceReview $review): bool
    {
        // Tidak boleh mengelola penilaian atas dirinya sendiri
        if ($user->id === $review->user_id) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // Manager hanya untuk bawahan langsungnya
        return $user->isManager() && $review->employee->manager_id === $user->id;
    }
}