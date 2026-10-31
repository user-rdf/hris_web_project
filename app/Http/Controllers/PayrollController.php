<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PayrollController extends Controller
{
    private const PERIOD_REGEX = '/^\d{4}-(0[1-9]|1[0-2])$/';

    // Admin: payroll semua karyawan per periode. Selain admin: slip milik sendiri.
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $default = now()->subMonthNoOverflow()->format('Y-m');
            $period  = (string) $request->query('period', $default);

            if (! preg_match(self::PERIOD_REGEX, $period)) {
                $period = $default;
            }

            $payrolls = Payroll::with('user.department')
                ->where('period', $period)
                ->get()
                ->sortBy('user.name');

            return view('payroll.index', compact('payrolls', 'period'));
        }

        $payrolls = $user->payrolls()->orderByDesc('period')->get();

        return view('payroll.mine', compact('payrolls'));
    }

    public function generate(Request $request, PayrollService $service)
    {
        $data = $request->validate([
            'period' => ['required', 'regex:' . self::PERIOD_REGEX],
        ]);

        // Bulan berjalan belum selesai, datanya belum lengkap
        if ($data['period'] >= now()->format('Y-m')) {
            return back()->with('error', 'Payroll hanya bisa dibuat untuk bulan yang sudah berakhir.');
        }

        try {
            $count = $service->generate($data['period']);
        } catch (\Throwable $e) {
            report($e); // dicatat di storage/logs/laravel.log
            return back()->with('error', 'Gagal membuat payroll. Silakan coba lagi.');
        }

        return redirect()
            ->route('payrolls.index', ['period' => $data['period']])
            ->with('success', "Payroll {$data['period']} berhasil dibuat untuk {$count} karyawan.");
    }

    // Slip gaji
    public function show(Payroll $payroll)
    {
        Gate::authorize('view', $payroll);

        $payroll->load('user.department');

        return view('payroll.show', compact('payroll'));
    }
}