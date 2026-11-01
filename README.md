# KelolaKita

**KelolaKita** adalah Sistem Informasi Sumber Daya Manusia (HRIS) berbasis web untuk mengelola absensi berbasis lokasi GPS, pengajuan cuti, evaluasi kinerja, dan perhitungan gaji (payroll) otomatis.

Proyek ini dibuat untuk **UAS Web Application Development, Cakrawala University**.

- **Live application:** https://kelolakita.cleverapps.io
- **Repository:** https://github.com/user-rdf/hris_web_project.git
- **Nama / NIM:** Radif Mafazi / 25110300039

## Teknologi

| Bagian | Teknologi |
|---|---|
| Framework backend | Laravel (arsitektur MVC) |
| Database | MySQL (relasional, 8 tabel) |
| ORM | Eloquent ORM (Query Builder dipakai untuk rekap dashboard) |
| Frontend | Blade, Bootstrap 5, Bootstrap Icons, SweetAlert2 (via CDN) |
| Autentikasi | Manual: `Auth` facade, controller, middleware, session |
| Hosting | Clever Cloud (PHP + MySQL add-on) |

## Fitur

- **Autentikasi:** registrasi, login, logout, manajemen session.
- **Absensi GPS:** check-in dan check-out, validasi jarak ke kantor dengan rumus Haversine, status tepat waktu atau terlambat.
- **Cuti:** pengajuan, validasi saldo dan tanggal, persetujuan atau penolakan oleh atasan, saldo berkurang otomatis.
- **Evaluasi kinerja:** manager menilai bawahan per periode (skor 1-5 dan catatan).
- **Payroll otomatis:** perhitungan gaji bulanan dari data absensi dan cuti, slip gaji yang bisa dicetak.
- **Manajemen data (admin):** CRUD karyawan, CRUD departemen, pengaturan lokasi kantor.
- **Dashboard** dengan statistik sesuai role.

## Hak akses (role)

| Fitur | Admin HR | Manager | Karyawan |
|---|:---:|:---:|:---:|
| Dashboard, absensi, ajukan cuti | Ya | Ya | Ya |
| Lihat evaluasi | Semua | Bawahan dan miliknya | Miliknya |
| Beri evaluasi | Semua orang (selain diri sendiri) | Bawahan langsung | Tidak |
| Setujui / tolak cuti | Semua (selain miliknya) | Bawahan langsung | Tidak |
| CRUD karyawan dan departemen | Ya | Tidak | Tidak |
| Pengaturan kantor | Ya | Tidak | Tidak |
| Generate payroll, lihat semua slip | Ya | Tidak | Tidak |
| Lihat slip gaji | Semua | Miliknya | Miliknya |

Pembatasan dijaga oleh `RoleMiddleware` (akses halaman) dan Policy (akses per data).

## Aturan bisnis

- Hanya hari kerja (Senin-Jumat) yang dihitung sebagai hari cuti.
- Saldo cuti hanya berkurang untuk jenis **Cuti Tahunan**.
- Hari alpa = hari kerja tanpa kehadiran dan tanpa cuti berbayar.
- Potongan terlambat: Rp 50.000 per hari. Potongan alpa dan izin tanpa gaji: gaji pokok / 22 per hari.
- Gaji bersih = gaji pokok + tunjangan - potongan terlambat - potongan alpa (minimal 0).
- Payroll hanya bisa dibuat untuk bulan yang sudah berakhir.

## Akun demo

Semua akun memakai password: `password`

| Role | Email |
|---|---|
| Admin HR | `admin@hris.test` |
| Manager IT | `manager.it@hris.test` |
| Manager Marketing | `manager.mkt@hris.test` |
| Karyawan | `andi@hris.test`, `dewi@hris.test`, `maya@hris.test`, dan lainnya |

## Menjalankan di komputer lokal

Kebutuhan: PHP 8.3 atau lebih baru, Composer, MySQL, Git. Cara paling mudah di Windows: [Laragon](https://laragon.org).

```bash
# 1. Unduh proyek
git clone https://github.com/user-rdf/hris_web_project.git
cd hris-app

# 2. Pasang dependensi
composer install

# 3. Salin konfigurasi dan buat kunci aplikasi
cp .env.example .env        # Windows CMD: copy .env.example .env
php artisan key:generate

# 4. Buat database kosong
mysql -u root -e "CREATE DATABASE hris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Sesuaikan bagian `DB_` di `.env` bila perlu (bawaan: user `root`, tanpa password, database `hris_db`).

```bash
# 5a. Buat tabel dan isi data dummy (disarankan)
php artisan migrate:fresh --seed

# 5b. ATAU impor file SQL yang disertakan
mysql -u root hris_db < database/kelolakita.sql

# 6. Jalankan
php artisan serve
```

Buka http://127.0.0.1:8000 lalu masuk dengan akun demo di atas.

> Fitur absensi GPS membutuhkan koneksi aman. Gunakan `127.0.0.1` / `localhost` atau alamat HTTPS. Setelah login sebagai admin, buka **Pengaturan Kantor** dan klik **Gunakan lokasi saya sekarang** agar koordinat kantor sesuai lokasi Anda.

## Struktur proyek

```
app/
  Http/Controllers/   AuthController, DashboardController, AttendanceController,
                      LeaveRequestController, PerformanceReviewController,
                      EmployeeController, DepartmentController,
                      OfficeSettingController, PayrollController
  Http/Middleware/    RoleMiddleware
  Http/Requests/      EmployeeRequest
  Models/             User, Department, Attendance, LeaveType, LeaveRequest,
                      PerformanceReview, Payroll, OfficeSetting
  Policies/           LeaveRequestPolicy, PerformanceReviewPolicy, PayrollPolicy
  Services/           PayrollService, GeoService
database/
  migrations/         8 tabel + kolom tambahan
  seeders/            data dummy (departemen, user, absensi, cuti, evaluasi)
  kelolakita.sql      ekspor database
lang/id/              terjemahan pesan validasi
resources/views/      layouts, auth, dashboard, attendance, leave, reviews,
                      payroll, employees, departments, office, errors
routes/web.php        definisi routing
```

## Keamanan

- **CSRF:** token `@csrf` pada semua form, middleware CSRF bawaan Laravel.
- **XSS:** output Blade memakai `{{ }}` (otomatis di-escape).
- **SQL Injection:** seluruh query lewat Eloquent / Query Builder (parameterized).
- **Validasi input server-side:** `validate()` dan Form Request.
- **Password:** di-hash (cast `hashed`, bcrypt).
- **Session:** ID session diregenerasi saat login, session dihapus dan token CSRF diganti saat logout.
- **Mass assignment:** kolom `role`, `status`, `approved_by`, dan `reviewer_id` tidak masuk `$fillable`.
- **Otorisasi:** middleware role dan Policy mencegah akses ke data milik orang lain.

## Deployment (Clever Cloud)

Ringkasan langkah:

1. Hubungkan repository GitHub ke aplikasi bertipe **PHP** di Clever Cloud.
2. Buat add-on **MySQL** dan hubungkan ke aplikasi.
3. Isi environment variables: `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY`, `APP_URL`, `APP_TIMEZONE`, `APP_LOCALE`, `DB_*`, `CC_WEBROOT=/public`, dan `CC_PRE_RUN_HOOK=php artisan migrate --force`.
4. Deploy pertama memakai `php artisan migrate:fresh --force --seed` pada `CC_PRE_RUN_HOOK`, lalu **diganti** menjadi `php artisan migrate --force`.
5. Aplikasi tersedia di https://kelolakita.cleverapps.io.

## Lisensi

Proyek akademik untuk keperluan penilaian UAS.
