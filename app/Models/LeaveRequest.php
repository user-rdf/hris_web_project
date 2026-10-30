<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    // 'status' dan 'approved_by' tidak di-fillable: hanya diubah lewat aksi approve/reject di controller
    protected $fillable = ['user_id', 'leave_type_id', 'start_date', 'end_date', 'reason'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** Jumlah hari kerja (Senin-Jumat) dalam rentang cuti, termasuk hari mulai dan akhir. */
    public function getDaysAttribute(): int
    {
        $days = 0;

        for ($day = $this->start_date->copy(); $day->lte($this->end_date); $day->addDay()) {
            if (! $day->isWeekend()) {
                $days++;
            }
        }

        return $days;
    }
}
