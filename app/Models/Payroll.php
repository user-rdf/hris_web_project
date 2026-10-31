<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = [
        'user_id', 'period', 'basic_salary', 'allowance', 'late_days', 'absent_days',
        'late_deduction', 'absent_deduction', 'net_salary',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary'     => 'decimal:2',
            'allowance'        => 'decimal:2',
            'late_deduction'   => 'decimal:2',
            'absent_deduction' => 'decimal:2',
            'net_salary'       => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
