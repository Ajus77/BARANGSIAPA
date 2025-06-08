let currentUKM = null;
        const ukms = {
            "UKM1": { password: "ukm1pass", role: "ukm" },
            "UKM2": { password: "ukm2pass", role: "ukm" },
            "UKM3": { password: "ukm3pass", role: "ukm" }
        };

        function login() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            if (ukms[username] && ukms[username].password === password) {
                currentUKM = username;
                document.getElementById("loginContainer").classList.remove("active");
                document.getElementById("mainContainer").classList.add("active");
                document.getElementById("currentUKM").textContent = currentUKM;
                loadBarang();
                loadPeminjaman();
                loadBarangTersedia();
                setActiveNav(document.querySelector('.nav-items a'));
            } else {
                alert("Nama UKM atau password salah!");
            }
        }

        function logout() {
            currentUKM = null;
            document.getElementById("mainContainer").classList.remove("active");
            document.getElementById("loginContainer").classList.add("active");
            document.getElementById("username").value = "";
            document.getElementById("password").value = "";
        }

        function setActiveNav(element) {
            const navLinks = document.querySelectorAll('.nav-items a');
            navLinks.forEach(link => link.classList.remove('active'));
            element.classList.add('active');
        }

        function tambahBarang() {
            const namaBarang = document.getElementById("namaBarang").value;
            const kondisiBarang = document.getElementById("kondisiBarang").value;

            if (namaBarang && kondisiBarang) {
                const barang = {
                    id: Date.now(),
                    namaBarang,
                    kondisiBarang,
                    pemilik: currentUKM,
                    status: "Tersedia"
                };
                let barangList = JSON.parse(localStorage.getItem("barang")) || [];
                barangList.push(barang);
                localStorage.setItem("barang", JSON.stringify(barangList));
                loadBarang();
                loadBarangTersedia();
            } else {
                alert("Hanya bisa menghapus barang milik sendiri yang tersedia!");
            }
        }

        function kembalikanBarang(id) {
            let peminjamanList = JSON.parse(localStorage.getItem("peminjaman")) || [];
            const peminjaman = peminjamanList.find(p => p.id === id);
            if (peminjaman && peminjaman.pemilik === currentUKM && peminjaman.status === "Sedang Dipinjam") {
                let barangList = JSON.parse(localStorage.getItem("barang")) || [];
                const barang = barangList.find(b => b.namaBarang === peminjaman.namaBarang && b.pemilik === currentUKM);
                if (barang) {
                    barang.status = "Tersedia";
                    localStorage.setItem("barang", JSON.stringify(barangList));
                }
                peminjaman.status = "Sudah Dikembalikan";
                localStorage.setItem("peminjaman", JSON.stringify(peminjamanList));
                loadBarang();
                loadPeminjaman();
                loadBarangTersedia();
            }
        }

        function resetBarangForm() {
            document.getElementById("namaBarang").value = "";
            document.getElementById("kondisiBarang").value = "";
        }

        function resetPinjamForm() {
            document.getElementById("barangPinjam").value = "";
            document.getElementById("tanggalPinjam").value = "";
            document.getElementById("tanggalKembali").value = "";
        }

        // Load data saat halaman dimuat
        if (currentUKM) {
            loadBarang();
            loadPeminjaman();
            loadBarangTersedia();
        }