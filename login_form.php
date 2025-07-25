<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.js"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#3B82F6',
                    secondary: '#A78BFA',
                    highlight: '#FAE546',
                    dark: '#0F0F0F',
                    light: '#FFFFF3',
                }
            }
        }
    }
</script>
<style>
    :root {
        --primary: #3B82F6;
        --secondary: #A78BFA;
        --highlight: #FAE546;
        --dark: #0F0F0F;
        --light: #FFFFF3;
        --card-bg: #FFFFFF0D;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: var(--light);
        min-height: 100vh;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        backdrop-filter: blur(40px);
        z-index: 10000;
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
        padding: 20px;
        box-sizing: border-box;
    }

    .modal-content {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        padding: 25px;
        border-radius: 20px;
        max-width: 450px;
        width: 100%;
        position: relative;
        transform: scale(0.9);
        animation: modalSlideIn 0.3s ease forwards;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        margin: 0 auto;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }

    .cta-button {
        background: var(--highlight);
        color: var(--dark);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .cta-button::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        transition: all 0.6s;
        opacity: 0;
    }

    .cta-button:hover::before {
        animation: shimmer 0.6s ease-in-out;
    }

    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(250, 229, 70, 0.4);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes modalSlideIn {
        from { 
            transform: scale(0.9) translateY(-20px);
            opacity: 0;
        }
        to { 
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); opacity: 0; }
    }

    /* Custom SweetAlert2 styling */
    .swal2-popup {
        background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
        color: var(--light) !important;
        border-radius: 20px !important;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5) !important;
    }

    .swal2-title {
        color: var(--light) !important;
        font-weight: 600 !important;
    }

    .swal2-html-container {
        color: var(--light) !important;
    }

    .swal2-confirm {
        background: var(--highlight) !important;
        color: var(--dark) !important;
        border: none !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 12px 30px !important;
        transition: all 0.3s ease !important;
    }

    .swal2-confirm:hover {
        background: #f4d321 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(250, 229, 70, 0.4) !important;
    }

    .swal2-cancel {
        background: rgba(255, 255, 255, 0.2) !important;
        color: var(--light) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 12px 30px !important;
        transition: all 0.3s ease !important;
    }

    .swal2-cancel:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        transform: translateY(-2px) !important;
    }

    .swal2-icon.swal2-success [class^=swal2-success-line] {
        background-color: var(--highlight) !important;
    }

    .swal2-icon.swal2-success .swal2-success-ring {
        border-color: var(--highlight) !important;
    }

    .swal2-icon.swal2-error [class^=swal2-x-mark-line] {
        background-color: #ff6b6b !important;
    }
</style>

<body class="modal">
    <form class="space-y-4 modal-content" id="loginForm">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-white mb-2">Selamat Datang!</h2>
            <p class="text-gray-300 text-sm">Silakan masuk ke akun Anda</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 text-white">Nama Lengkap</label>
            <input type="text" name="nama" id="nama"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                placeholder="Masukkan nama lengkap Anda" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 text-white">Email</label>
            <input type="email" name="email" id="email"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                placeholder="email@example.com" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 text-white">Password</label>
            <input type="password" name="password" id="password"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                placeholder="Masukkan password Anda" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2 text-white">Login Sebagai</label>
            <select name="peran" id="peran"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                required>
                <option value="" class="text-black">Pilih role Anda</option>
                <option value="pelajar" class="text-black">Pelajar</option>
                <option value="donatur" class="text-black">Donatur</option>
                <option value="relawan" class="text-black">Relawan</option>
            </select>
        </div>

        <button type="submit" class="w-full cta-button py-3 rounded-lg font-semibold text-base mt-6">
            <span class="relative z-10">Login</span>
        </button>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-300">Belum punya akun?
                <a href="#" id="registerLink"
                    class="text-yellow-400 hover:text-yellow-300 underline bg-transparent border-none cursor-pointer">Daftar
                    di sini</a>
            </p>
        </div>
    </form>

    <script>
        // SweetAlert configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Function to show dashboard preview (simulates next page)
        function showDashboardPreview(userData) {
            const currentTime = new Date().toLocaleString('id-ID');
            
            Swal.fire({
                title: 'Dashboard Preview',
                html: `
                    <div class="bg-white bg-opacity-10 p-4 rounded-lg text-left mb-4">
                        <h3 class="text-lg font-semibold text-yellow-400 mb-3">Informasi Pengguna</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Nama:</span> ${userData.nama}</p>
                            <p><span class="font-medium">Email:</span> ${userData.email}</p>
                            <p><span class="font-medium">Role:</span> ${userData.peran.charAt(0).toUpperCase() + userData.peran.slice(1)}</p>
                            <p><span class="font-medium">ID Pengguna:</span> #${userData.id}</p>
                            <p><span class="font-medium">Login:</span> ${currentTime}</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-black p-3 rounded-lg text-sm">
                        <strong>🎉 Selamat datang, ${userData.nama}!</strong><br>
                        Anda berhasil masuk sebagai <strong>${userData.peran}</strong>
                    </div>
                `,
                width: 500,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Logout',
                customClass: {
                    popup: 'dashboard-preview'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Toast.fire({
                        icon: 'success',
                        title: `Dashboard ${userData.peran} dimuat!`
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Logout confirmation
                    Swal.fire({
                        title: 'Konfirmasi Logout',
                        text: 'Apakah Anda yakin ingin keluar?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal'
                    }).then((logoutResult) => {
                        if (logoutResult.isConfirmed) {
                            // Clear user data
                            window.userData = null;
                            
                            // Reset form
                            document.getElementById('loginForm').reset();
                            
                            Toast.fire({
                                icon: 'info',
                                title: 'Berhasil logout dari sistem'
                            });
                        }
                    });
                }
            });
        }

        // Form validation and submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nama = document.getElementById('nama').value.trim();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const peran = document.getElementById('peran').value;

            // Basic validation
            if (!nama || !email || !password || !peran) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Mohon lengkapi semua field yang diperlukan.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Name validation
            if (nama.length < 2) {
                Swal.fire({
                    title: 'Nama Tidak Valid!',
                    text: 'Nama harus terdiri dari minimal 2 karakter.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Email format validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire({
                    title: 'Email Tidak Valid!',
                    text: 'Mohon masukkan format email yang benar.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memverifikasi akun Anda',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create FormData object for submission
            const formData = new FormData();
            formData.append('nama', nama);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('peran', peran);

            // For demonstration - replace with actual fetch to login_proses.php
            /*
            fetch('login_proses.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store user data for the next page
                    const userData = {
                        nama: data.nama,
                        email: data.email,
                        peran: data.peran,
                        id: data.user_id,
                        loginTime: new Date().toISOString()
                    };
                    
                    Swal.fire({
                        title: 'Login Berhasil!',
                        html: `
                            <div class="text-left">
                                <p class="mb-2"><strong>Nama:</strong> ${data.nama}</p>
                                <p class="mb-2"><strong>Role:</strong> ${data.peran.charAt(0).toUpperCase() + data.peran.slice(1)}</p>
                                <p class="text-sm text-gray-300">Mengarahkan ke halaman ${data.peran}...</p>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Lanjutkan',
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        // Redirect based on role
                        const userParams = `?user=${encodeURIComponent(data.nama)}&email=${encodeURIComponent(data.email)}&id=${data.user_id}`;
                        
                        switch(data.peran) {
                            case 'pelajar':
                                window.location.href = `student.php${userParams}`;
                                break;
                            case 'donatur':
                                window.location.href = `donatur.php${userParams}`;
                                break;
                            case 'relawan':
                                window.location.href = `volunteer.php${userParams}`;
                                break;
                            default:
                                window.location.href = `dashboard.php${userParams}`;
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Login Gagal!',
                        text: data.message || 'Email atau password yang Anda masukkan salah.',
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
            */

            // Simulate login process (replace with actual fetch above)
            setTimeout(() => {
                const isSuccess = Math.random() > 0.3; // 70% success rate for demo
                
                if (isSuccess) {
                    // Create user data object
                    const userData = {
                        nama: nama,
                        email: email,
                        peran: peran,
                        loginTime: new Date().toISOString(),
                        id: Math.floor(Math.random() * 10000) // Simulate user ID
                    };
                    
                    // Store user data (in real app, this would come from server response)
                    window.userData = userData; // For demo purposes
                    
                    Swal.fire({
                        title: 'Login Berhasil!',
                        html: `
                            <div class="text-left">
                                <p class="mb-2"><strong>Nama:</strong> ${nama}</p>
                                <p class="mb-2"><strong>Role:</strong> ${peran.charAt(0).toUpperCase() + peran.slice(1)}</p>
                                <p class="text-sm text-gray-300">Selamat datang di sistem!</p>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Lanjutkan ke Dashboard',
                        timer: 3000,
                        timerProgressBar: true
                    }).then((result) => {
                        // Redirect to role-specific pages
                        let redirectUrl = '';
                        const userParams = `?user=${encodeURIComponent(nama)}&email=${encodeURIComponent(email)}&id=${userData.id}`;
                        
                        switch(peran) {
                            case 'pelajar':
                                redirectUrl = `student.php${userParams}`;
                                break;
                            case 'donatur':
                                redirectUrl = `donatur.php${userParams}`;
                                break;
                            case 'relawan':
                                redirectUrl = `volunteer.php${userParams}`;
                                break;
                            default:
                                redirectUrl = `dashboard.php${userParams}`;
                        }
                        
                        // Redirect to appropriate page
                        window.location.href = redirectUrl;
                        
                        // For demo purposes (remove in production), show preview
                        // showDashboardPreview(userData);
                    });
                } else {
                    Swal.fire({
                        title: 'Login Gagal!',
                        text: 'Email atau password yang Anda masukkan salah.',
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi'
                    });
                }
            }, 2000);
        });

        // Register link handler
        document.getElementById('registerLink').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Daftar Akun Baru',
                text: 'Anda akan diarahkan ke halaman pendaftaran.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to register page
                    // window.location.href = 'register_form.php';
                    
                    // For demo purposes
                    Toast.fire({
                        icon: 'info',
                        title: 'Mengarahkan ke halaman pendaftaran...'
                    });
                }
            });
        });

        // Show welcome message on page load
        window.addEventListener('load', function() {
            setTimeout(() => {
                Toast.fire({
                    icon: 'info',
                    title: 'Silakan masuk ke akun Anda'
                });
            }, 500);
        });

        // Additional validation on input change
        document.getElementById('nama').addEventListener('blur', function() {
            const nama = this.value.trim();
            
            if (nama && nama.length < 2) {
                this.style.borderColor = '#ff6b6b';
                Toast.fire({
                    icon: 'warning',
                    title: 'Nama minimal 2 karakter'
                });
            } else if (nama && nama.length >= 2) {
                this.style.borderColor = '#4ade80';
            }
        });

        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#ff6b6b';
                Toast.fire({
                    icon: 'warning',
                    title: 'Format email tidak valid'
                });
            } else if (email) {
                this.style.borderColor = '#4ade80';
            }
        });

        // Password strength indicator (optional)
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            
            if (password.length > 0 && password.length < 6) {
                this.style.borderColor = '#ff6b6b';
            } else if (password.length >= 6) {
                this.style.borderColor = '#4ade80';
            }
        });
    </script>
</body>