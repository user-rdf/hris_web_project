<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    // Akses sudah dijaga oleh middleware role:admin di route
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Saat edit, route punya parameter "employee" (user yang diedit). Saat tambah, nilainya null.
        $employee = $this->route('employee');

        return [
            'name'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee)],
            // Tambah: password wajib. Edit: boleh kosong (artinya tidak diganti).
            'password'      => [$employee ? 'nullable' : 'required', 'string', 'min:8'],
            'department_id' => ['required', 'exists:departments,id'],
            'role'          => ['required', Rule::in(['admin', 'manager', 'employee'])],
            'manager_id'    => ['nullable', 'exists:users,id'],
            'basic_salary'  => ['required', 'numeric', 'min:0'],
            'allowance'     => ['required', 'numeric', 'min:0'],
            'leave_balance' => ['required', 'integer', 'min:0', 'max:255'],
        ];
    }

    
}