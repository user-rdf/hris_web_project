<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    // READ: daftar semua departemen beserta jumlah karyawannya
    public function index()
    {
        $departments = Department::withCount('users')->orderBy('name')->get();

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    // CREATE: simpan departemen baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:departments,name'],
        ]);

        Department::create($data);

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    // UPDATE: ubah nama departemen
    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            // unique, tetapi abaikan baris departemen yang sedang diedit
            'name' => ['required', 'string', 'max:100', Rule::unique('departments', 'name')->ignore($department->id)],
        ]);

        $department->update($data);

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    // DELETE: hapus departemen, tetapi ditolak jika masih punya karyawan
    public function destroy(Department $department)
    {
        if ($department->users()->exists()) {
            return back()->with('error', 'Departemen tidak bisa dihapus karena masih memiliki karyawan.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil dihapus.');
    }
}