<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LeaveRequestController extends Controller
{
    // Daftar pengajuan milik sendiri
    public function index(Request $request)
    {
        $leaves = $request->user()->leaveRequests()
            ->with(['leaveType', 'approver'])
            ->latest()
            ->paginate(10);

        return view('leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('leave.create', ['types' => LeaveType::orderBy('name')->get()]);
    }

    // CREATE: ajukan cuti
    public function store(Request $request)
    {
        $data = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            // boleh mundur maksimal 7 hari (mis. izin sakit yang diajukan belakangan)
            'start_date'    => ['required', 'date', 'after_or_equal:' . today()->subDays(7)->toDateString()],
            'end_date'      => ['required', 'date', 'after_or_equal:start_date'],
            'reason'        => ['required', 'string', 'max:500'],
        ]);

        $user  = $request->user();
        $type  = LeaveType::findOrFail($data['leave_type_id']);
        $leave = $user->leaveRequests()->make($data); // belum disimpan, dipakai untuk menghitung hari

        if ($leave->days < 1) {
            return back()->withInput()->with('error', 'Rentang tanggal hanya berisi akhir pekan.');
        }

        if ($type->deductsBalance() && $leave->days > $user->leave_balance) {
            return back()->withInput()->with('error',
                "Saldo cuti tidak cukup. Sisa {$user->leave_balance} hari, diajukan {$leave->days} hari.");
        }

        // Cegah tanggal bertumpuk dengan pengajuan lain yang masih aktif
        $overlap = $user->leaveRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->where('start_date', '<=', $data['end_date'])
            ->where('end_date', '>=', $data['start_date'])
            ->exists();

        if ($overlap) {
            return back()->withInput()->with('error', 'Tanggal tersebut bertumpuk dengan pengajuan cuti lain.');
        }

        $leave->save();

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    // DELETE: batalkan pengajuan yang masih pending
    public function destroy(LeaveRequest $leaveRequest)
    {
        Gate::authorize('cancel', $leaveRequest);

        $leaveRequest->delete();

        return back()->with('success', 'Pengajuan cuti dibatalkan.');
    }

    // Halaman persetujuan (manager/admin)
    public function approvals(Request $request)
    {
        $user = $request->user();

        $pending = LeaveRequest::with(['user', 'leaveType'])
            ->where('status', 'pending')
            ->where('user_id', '!=', $user->id);

        if ($user->isManager()) {
            $pending->whereHas('user', fn ($q) => $q->where('manager_id', $user->id));
        }

        $decided = LeaveRequest::with(['user', 'leaveType'])
            ->where('approved_by', $user->id)
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('leave.approvals', [
            'pending' => $pending->orderBy('start_date')->get(),
            'decided' => $decided,
        ]);
    }

    // UPDATE: setujui, potong saldo bila Cuti Tahunan
    public function approve(LeaveRequest $leaveRequest)
    {
        Gate::authorize('decide', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $employee = $leaveRequest->user;
        $days     = $leaveRequest->days;
        $deducts  = $leaveRequest->leaveType->deductsBalance();

        if ($deducts && $days > $employee->leave_balance) {
            return back()->with('error', 'Saldo cuti karyawan tidak cukup untuk disetujui.');
        }

        // Dua perubahan (status dan saldo) harus berhasil bersamaan, jadi dibungkus transaksi
        DB::transaction(function () use ($leaveRequest, $employee, $days, $deducts) {
            $leaveRequest->status      = 'approved';
            $leaveRequest->approved_by = auth()->id();
            $leaveRequest->save();

            if ($deducts) {
                $employee->decrement('leave_balance', $days);
            }
        });

        return back()->with('success', 'Pengajuan cuti disetujui.');
    }

    // UPDATE: tolak
    public function reject(LeaveRequest $leaveRequest)
    {
        Gate::authorize('decide', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $leaveRequest->status      = 'rejected';
        $leaveRequest->approved_by = auth()->id();
        $leaveRequest->save();

        return back()->with('success', 'Pengajuan cuti ditolak.');
    }
}