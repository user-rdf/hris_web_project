<?php

return [
    'required'       => ':attribute wajib diisi.',
    'string'         => ':attribute harus berupa teks.',
    'email'          => ':attribute harus berupa alamat email yang valid.',
    'unique'         => ':attribute sudah digunakan.',
    'exists'         => ':attribute yang dipilih tidak valid.',
    'in'             => ':attribute yang dipilih tidak valid.',
    'confirmed'      => 'Konfirmasi :attribute tidak cocok.',
    'numeric'        => ':attribute harus berupa angka.',
    'integer'        => ':attribute harus berupa bilangan bulat.',
    'date'           => ':attribute bukan tanggal yang valid.',
    'date_format'    => 'Format :attribute tidak sesuai (:format).',
    'regex'          => 'Format :attribute tidak valid.',
    'after_or_equal' => ':attribute harus sama dengan atau setelah :date.',
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'string'  => ':attribute minimal :min karakter.',
    ],
    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'string'  => ':attribute maksimal :max karakter.',
    ],
    'between' => [
        'numeric' => ':attribute harus di antara :min dan :max.',
    ],

    'attributes' => [
        'name' => 'Nama', 'email' => 'Email', 'password' => 'Password',
        'department_id' => 'Departemen', 'role' => 'Role', 'manager_id' => 'Atasan',
        'basic_salary' => 'Gaji pokok', 'allowance' => 'Tunjangan', 'leave_balance' => 'Jatah cuti',
        'leave_type_id' => 'Jenis cuti', 'start_date' => 'Tanggal mulai', 'end_date' => 'Tanggal selesai',
        'reason' => 'Alasan', 'user_id' => 'Karyawan', 'period' => 'Periode',
        'score' => 'Skor', 'notes' => 'Catatan', 'latitude' => 'Latitude', 'longitude' => 'Longitude',
        'radius_m' => 'Radius', 'work_start_time' => 'Jam masuk',
    ],
];