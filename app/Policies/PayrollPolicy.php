<?php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;

class PayrollPolicy
{
    /** Slip gaji hanya boleh dilihat admin atau pemiliknya. */
    public function view(User $user, Payroll $payroll): bool
    {
        return $user->isAdmin() || $user->id === $payroll->user_id;
    }
}