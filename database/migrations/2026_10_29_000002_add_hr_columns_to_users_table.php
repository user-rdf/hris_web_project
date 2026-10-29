<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jika departemen dihapus, karyawan tidak ikut terhapus (department_id jadi NULL)
            $table->foreignId('department_id')->nullable()->after('id')
                ->constrained('departments')->nullOnDelete();

            $table->enum('role', ['admin', 'manager', 'employee'])->default('employee')->after('password');

            // Self-reference: atasan langsung seorang karyawan
            $table->foreignId('manager_id')->nullable()->after('role')
                ->constrained('users')->nullOnDelete();

            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('allowance', 12, 2)->default(0);
            $table->unsignedTinyInteger('leave_balance')->default(12); // jatah cuti per tahun
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropConstrainedForeignId('manager_id');
            $table->dropColumn(['role', 'basic_salary', 'allowance', 'leave_balance']);
        });
    }
};
