<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\QueryException;

class EmployeeController extends Controller
{
    // READ: daftar karyawan, 10 per halaman
    public function index()
    {
        // with() = eager loading, mencegah masalah N+1 query
        $employees = User::with(['department', 'manager'])->orderBy('name')->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create', $this->formData());
    }

    // CREATE
    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        $user = new User(collect($data)->except('role')->all());
        $user->role = $data['role']; // role di-set manual karena tidak ada di $fillable
        $user->save();

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(User $employee)
    {
        return view('employees.edit', $this->formData($employee));
    }

    // UPDATE
    public function update(EmployeeRequest $request, User $employee)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']); // password kosong = tidak diganti
        }

        $employee->fill(collect($data)->except('role')->all());
        $employee->role = $data['role'];
        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // DELETE
    public function destroy(User $employee)
    {
        if ($employee->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        try {
            $employee->delete();
        } catch (QueryException $e) {
            // Terjadi bila data masih terikat foreign key (mis. manager yang pernah menulis evaluasi)
            return back()->with('error', 'Karyawan tidak bisa dihapus karena masih terkait dengan data lain.');
        }

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    // Data untuk dropdown di form
    private function formData(?User $employee = null): array
    {
        return [
            'employee'    => $employee,
            'departments' => Department::orderBy('name')->get(),
            'managers'    => User::where('role', 'manager')->orderBy('name')->get(),
        ];
    }
}