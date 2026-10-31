<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function () {
        // 1. Validasi dilakukan di server, jadi pop-up validasi bawaan browser dimatikan
        document.querySelectorAll('form').forEach(function (f) { f.setAttribute('novalidate', ''); });

        // 2. Notifikasi toast
        var Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false,
            timer: 4500, timerProgressBar: true,
            didOpen: function (t) {
                t.addEventListener('mouseenter', Swal.stopTimer);
                t.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        window.notify = function (type, message) {
            Toast.fire({ icon: type, title: message });
        };

        function escapeHtml(text) {
            var d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        window.showErrors = function (messages) {
            var items = messages.slice(0, 5).map(function (m) {
                return '<li>' + escapeHtml(m) + '</li>';
            }).join('');

            Toast.fire({
                icon: 'error', title: 'Periksa kembali isian Anda',
                html: '<ul class="text-start small mb-0 ps-3">' + items + '</ul>', timer: 8000
            });
        };

        // 3. Dialog konfirmasi: form dengan atribut data-confirm="pesan"
        document.addEventListener('submit', function (e) {
            var form = e.target;
            var message = form.getAttribute('data-confirm');

            if (message) {
                e.preventDefault();
                var danger = /hapus|batalkan|tolak/i.test(message);

                Swal.fire({
                    title: 'Konfirmasi', text: message, icon: danger ? 'warning' : 'question',
                    showCancelButton: true, reverseButtons: true,
                    confirmButtonText: danger ? 'Ya, lanjutkan' : 'Ya', cancelButtonText: 'Batal',
                    confirmButtonColor: danger ? '#dc2626' : '#4f46e5'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.removeAttribute('data-confirm');
                        form.submit();
                    }
                });
                return;
            }

            // Cegah klik ganda pada tombol kirim
            setTimeout(function () {
                form.querySelectorAll('button:not([type="button"])').forEach(function (b) { b.disabled = true; });
            }, 0);
        });

        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                document.querySelectorAll('button:disabled').forEach(function (b) { b.disabled = false; });
            }
        });

        // 4. Sidebar di layar kecil
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('sidebar-backdrop');
        var toggle = document.getElementById('sidebar-toggle');

        function setSidebar(open) {
            sidebar.classList.toggle('show', open);
            backdrop.classList.toggle('show', open);
        }

        if (sidebar && toggle && backdrop) {
            toggle.addEventListener('click', function () { setSidebar(!sidebar.classList.contains('show')); });
            backdrop.addEventListener('click', function () { setSidebar(false); });
        }
    })();

    // 5. Pesan dari server (flash message dan error validasi)
    @if (session('success')) notify('success', @json(session('success'))); @endif
    @if (session('error')) notify('error', @json(session('error'))); @endif
    @if ($errors->any()) showErrors(@json($errors->all())); @endif
</script>