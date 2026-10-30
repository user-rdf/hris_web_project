<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    /** Boleh menyetujui/menolak? */
    public function decide(User $user, LeaveRequest $leaveRequest): bool
    {
        // Tidak boleh memutuskan pengajuan milik sendiri
        if ($user->id === $leaveRequest->user_id) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // Manager hanya untuk bawahan langsungnya
        return $user->isManager() && $leaveRequest->user->manager_id === $user->id;
    }

    /** Boleh membatalkan? Hanya pemilik, dan hanya selama masih pending. */
    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->user_id && $leaveRequest->status === 'pending';
    }
}