document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editModal');
    const closeModal = document.querySelector('.close-modal');
    let currentEditingId = null;

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const row = this.closest('tr');

            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = row.cells[0].textContent.trim();
            document.getElementById('editKondisi').value = row.cells[1].textContent.trim();

            const kategoriText = row.cells[2].textContent.trim();
            let kategoriId = null;
            document.querySelectorAll('#editKategori option').forEach(option => {
                if (option.textContent === kategoriText) {
                    kategoriId = option.value;
                }
            });
            
            if (kategoriId) {
                document.getElementById('editKategori').value = kategoriId;
            }
            
            currentEditingId = id;
            modal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const data = {
            id: document.getElementById('editId').value,
            nama_barang: document.getElementById('editNama').value,
            kondisi: document.getElementById('editKondisi').value,
            kategori_id: document.getElementById('editKategori').value
        };
        
        fetch('update_barang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Perubahan berhasil disimpan');
                location.reload();
            } else {
                alert('Gagal menyimpan perubahan: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                const id = this.getAttribute('data-id');
                
                fetch('hapus_barang.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('tr').remove();
                        alert('Barang berhasil dihapus');
                    } else {
                        alert('Gagal menghapus barang: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                });
            }
        });
    });
});