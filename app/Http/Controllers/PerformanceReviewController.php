<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PerformanceReviewController extends Controller
{
    // Format periode YYYY-MM, bulan 01-12
    private const PERIOD_REGEX = '/^\d{4}-(0[1-9]|1[0-2])$/';

    // READ: karyawan melihat milik sendiri, manager melihat bawahan + miliknya, admin melihat semua
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = PerformanceReview::with(['employee', 'reviewer']);

        if ($user->isManager()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('employee', fn ($e) => $e->where('manager_id', $user->id));
            });
        } elseif (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $reviews = $query->orderByDesc('period')->orderBy('user_id')->paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.create', ['employees' => $this->reviewable()]);
    }

    // CREATE
    public function store(Request $request)
    {
        $employees = $this->reviewable();

        $data = $request->validate([
            // user_id harus salah satu karyawan yang memang boleh dinilai oleh pengguna ini
            'user_id' => ['required', Rule::in($employees->pluck('id')->all())],
            'period'  => ['required', 'regex:' . self::PERIOD_REGEX],
            'score'   => ['required', 'integer', 'between:1,5'],
            'notes'   => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['period'] > now()->format('Y-m')) {
            return back()->withInput()->withErrors(['period' => 'Periode tidak boleh di masa depan.']);
        }

        $exists = PerformanceReview::where('user_id', $data['user_id'])
            ->where('period', $data['period'])->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['period' => 'Karyawan ini sudah dinilai untuk periode tersebut.']);
        }

        $review = new PerformanceReview($data);
        $review->reviewer_id = $request->user()->id; // dari sesi login, bukan dari form
        $review->save();

        return redirect()->route('performance-reviews.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    public function edit(PerformanceReview $performance_review)
    {
        Gate::authorize('manage', $performance_review);

        return view('reviews.edit', ['review' => $performance_review->load('employee')]);
    }

    // UPDATE: hanya skor dan catatan yang boleh diubah
    public function update(Request $request, PerformanceReview $performance_review)
    {
        Gate::authorize('manage', $performance_review);

        $data = $request->validate([
            'score' => ['required', 'integer', 'between:1,5'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $performance_review->update($data);

        return redirect()->route('performance-reviews.index')->with('success', 'Penilaian diperbarui.');
    }

    // DELETE
    public function destroy(PerformanceReview $performance_review)
    {
        Gate::authorize('manage', $performance_review);

        $performance_review->delete();

        return back()->with('success', 'Penilaian dihapus.');
    }

    // Daftar orang yang boleh dinilai oleh pengguna yang sedang login
    private function reviewable(): \Illuminate\Support\Collection
    {
        $user  = auth()->user();
        $query = User::where('id', '!=', $user->id)->orderBy('name');

        if ($user->isManager()) {
            $query->where('manager_id', $user->id);
        }

        return $query->get();
    }
}