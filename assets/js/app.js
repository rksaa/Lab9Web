// Tampilkan konfirmasi sebelum form dengan atribut data-konfirmasi dikirim.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-konfirmasi]').forEach(function (form) {
        form.addEventListener('submit', function (ev) {
            if (!confirm(form.getAttribute('data-konfirmasi'))) {
                ev.preventDefault();
            }
        });
    });
});
