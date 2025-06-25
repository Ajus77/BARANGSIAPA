document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-kategori');
    const namaInput = form.querySelector('input[name="nama"]');
    const deskripsiInput = form.querySelector('input[name="deskripsi"]');

    form.addEventListener('submit', function (e) {
        const nama = namaInput.value.trim();
        const deskripsi = deskripsiInput.value.trim();

        if (!nama || !deskripsi) {
            alert("Semua kolom wajib diisi.");
            e.preventDefault();
            return;
        }

        const jumlahKata = deskripsi.split(/\s+/).filter(Boolean).length;
        if (jumlahKata < 2) {
            alert("Deskripsi harus minimal 2 kata.");
            e.preventDefault();
        }
    });
});
