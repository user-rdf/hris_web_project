<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
    :root {
        --bs-primary: #4f46e5; --bs-primary-rgb: 79, 70, 229;
        --bs-primary-bg-subtle: #e0e7ff; --bs-primary-text-emphasis: #3730a3;
        --bs-link-color: #4f46e5; --bs-link-color-rgb: 79, 70, 229;
        --bs-link-hover-color: #4338ca; --bs-link-hover-color-rgb: 67, 56, 202;
        --bs-body-font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
        --bs-body-bg: #f4f6fb; --bs-body-color: #1e293b;
        --sidebar-w: 260px;
    }

    h3 { font-size: 1.4rem; font-weight: 700; }
    h5 { font-weight: 600; }

    /* Tombol, form, kartu, tabel */
    .btn { border-radius: 10px; font-weight: 500; }
    .btn-primary {
        --bs-btn-bg: #4f46e5; --bs-btn-border-color: #4f46e5;
        --bs-btn-hover-bg: #4338ca; --bs-btn-hover-border-color: #4338ca;
        --bs-btn-active-bg: #3730a3; --bs-btn-active-border-color: #3730a3;
        --bs-btn-disabled-bg: #4f46e5; --bs-btn-disabled-border-color: #4f46e5;
        --bs-btn-focus-shadow-rgb: 79, 70, 229;
    }
    .btn-outline-primary {
        --bs-btn-color: #4f46e5; --bs-btn-border-color: #4f46e5;
        --bs-btn-hover-bg: #4f46e5; --bs-btn-hover-border-color: #4f46e5;
        --bs-btn-active-bg: #4338ca; --bs-btn-active-border-color: #4338ca;
    }
    .form-label { font-weight: 500; font-size: .875rem; color: #475569; }
    .form-control, .form-select { border-radius: 10px; border-color: #dbe1ea; padding: .6rem .85rem; }
    .form-control:focus, .form-select:focus { border-color: #818cf8; box-shadow: 0 0 0 .2rem rgba(79, 70, 229, .15); }
    .card { border: 0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, .05), 0 1px 3px rgba(16, 24, 40, .06); }
    .badge { font-weight: 500; border-radius: 8px; padding: .4em .65em; }
    .table { --bs-table-striped-bg: #f8fafc; }
    .table > :not(caption) > * > * { padding: .85rem 1rem; }
    .table thead th {
        font-size: .72rem; text-transform: uppercase; letter-spacing: .05em;
        color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; white-space: nowrap;
    }

    /* Sidebar */
    .sidebar {
        position: fixed; top: 0; bottom: 0; left: 0; width: var(--sidebar-w); z-index: 1040;
        background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
        display: flex; flex-direction: column; overflow-y: auto; transition: transform .25s ease;
    }
    .sidebar .brand {
        display: flex; align-items: center; gap: .65rem; padding: 1.4rem 1.5rem;
        color: #fff; font-weight: 700; font-size: 1.25rem; text-decoration: none;
    }
    .sidebar .brand i { background: rgba(255, 255, 255, .15); border-radius: 10px; padding: .35rem .5rem; }
    .sidebar .nav-label { font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; color: #818cf8; padding: 1rem 1.5rem .4rem; }
    .sidebar .nav-link {
        display: flex; align-items: center; gap: .75rem; color: #c7d2fe;
        padding: .65rem 1rem; margin: .1rem .75rem; border-radius: 10px; font-weight: 500;
    }
    .sidebar .nav-link:hover { background: rgba(255, 255, 255, .08); color: #fff; }
    .sidebar .nav-link.active { background: #fff; color: #3730a3; }
    .sidebar .nav-link i { font-size: 1.1rem; }
    .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, .5); z-index: 1035; }

    .main { margin-left: var(--sidebar-w); min-height: 100vh; }
    .topbar { background: #fff; border-bottom: 1px solid #e8ecf3; padding: .7rem 1.5rem; position: sticky; top: 0; z-index: 1020; }
    .content { padding: 1.75rem 1.5rem 3rem; max-width: 1200px; }
    .avatar {
        width: 36px; height: 36px; border-radius: 50%; background: #e0e7ff; color: #3730a3;
        display: inline-flex; align-items: center; justify-content: center; font-weight: 600;
    }
    .stat-card .icon {
        width: 48px; height: 48px; border-radius: 12px; font-size: 1.4rem;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    @media (max-width: 991.98px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.show { transform: none; }
        .sidebar-backdrop.show { display: block; }
        .main { margin-left: 0; }
    }

    /* Halaman login dan registrasi */
    .auth-wrap { display: flex; min-height: 100vh; }
    .auth-hero { flex: 1; flex-direction: column; justify-content: center; padding: 3rem; color: #fff; background: linear-gradient(135deg, #1e1b4b, #4f46e5); }
    .auth-hero h1 { font-size: 2rem; font-weight: 700; line-height: 1.3; max-width: 30rem; }
    .auth-hero li { display: flex; align-items: center; gap: .6rem; margin: .7rem 0; color: #c7d2fe; }
    .auth-form { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; background: #fff; }
    .auth-card { width: 100%; max-width: 400px; }

    /* SweetAlert2 */
    .swal2-popup { font-family: inherit; border-radius: 16px; }
    .swal2-toast { border-radius: 12px !important; }
</style>