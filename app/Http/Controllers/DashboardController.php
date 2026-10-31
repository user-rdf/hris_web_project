<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user       = $request->user();
        $today      = today()->toDateString();
        $lastPeriod = now()->subMonthNoOverflow()->format('Y-m');

        // Kartu statistik berbeda untuk tiap role
        if ($user->isAdmin()) {
            $stats = [
                'total_employees' => User::count(),
                'present_today'   => Attendance::where('date', $today)->whereIn('status', ['present', 'late'])->count(),
                'pending_leaves'  => LeaveRequest::where('status', 'pending')->count(),
                'last_payroll'    => (float) Payroll::where('period', $lastPeriod)->sum('net_salary'),
            ];
        } elseif ($user->isManager()) {
            $teamIds = $user->subordinates()->pluck('id');
            $stats = [
                'team_size'      => $teamIds->count(),
                'present_today'  => Attendance::whereIn('user_id', $teamIds)->where('date', $today)
                                        ->whereIn('status', ['present', 'late'])->count(),
                'pending_leaves' => LeaveRequest::whereIn('user_id', $teamIds)->where('status', 'pending')->count(),
                'leave_balance'  => $user->leave_balance,
            ];
        } else {
            $stats = [
                'attendance_today' => $user->attendances()->where('date', $today)->first(),
                'leave_balance'    => $user->leave_balance,
                'pending_leaves'   => $user->leaveRequests()->where('status', 'pending')->count(),
                'last_payroll'     => $user->payrolls()->where('period', $lastPeriod)->value('net_salary'),
            ];
        }

        // Rekap kehadiran bulan ini memakai Query Builder (agregasi GROUP BY)
        $summary = DB::table('attendances')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('date', [now()->startOfMonth()->toDateString(), $today])
            ->when(! $user->isAdmin(), fn ($q) => $q->where('user_id', $user->id))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard', compact('stats', 'summary'));
    }
}