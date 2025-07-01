  document.addEventListener('DOMContentLoaded', function() {

            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('.section');

            document.getElementById('tambah-barang').classList.remove('hidden');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('data-target');
                    // const targetId = daftar-barang
                    
                    sections.forEach(section => {
                        section.classList.add('hidden');
                    });
                    
                    document.getElementById(targetId).classList.remove('hidden');
                    // daftar-barang.classlist.remove('hidden');
                });
            });
        });
