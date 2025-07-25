<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Donatur - Platform Donasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.js"></script>
</head>
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
        padding: 10px 25px !important;
    }

    .swal2-confirm:hover {
        background: #F5D842 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(250, 229, 70, 0.4) !important;
    }

    .swal2-cancel {
        background: transparent !important;
        color: var(--light) !important;
        border: 2px solid var(--light) !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 10px 25px !important;
    }

    .swal2-cancel:hover {
        background: var(--light) !important;
        color: var(--dark) !important;
    }

    .input-field {
        transition: all 0.3s ease;
    }

    .input-field:focus {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
    }

    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .form-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        background: linear-gradient(45deg, var(--light), var(--highlight));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-header p {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }

    .password-strength {
        margin-top: 5px;
        font-size: 12px;
    }

    .strength-weak { color: #EF4444; }
    .strength-medium { color: #F59E0B; }
    .strength-strong { color: #10B981; }

    .checkbox-container {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 15px 0;
    }

    .checkbox-container input[type="checkbox"] {
        margin-top: 2px;
        accent-color: var(--highlight);
    }

    .terms-text {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.4;
    }

    .terms-link {
        color: var(--highlight);
        text-decoration: underline;
        cursor: pointer;
    }

    .terms-link:hover {
        color: #F5D842;
    }
</style>

<body class="modal">
    <form class="space-y-4 modal-content" id="registrationForm" method="post" enctype="multipart/form-data" action="register_proses.php">
        <div class="form-header">
            <h2>Bergabung Sebagai Donatur</h2>
            <p>Mari berbagi kebaikan untuk sesama</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
            <input type="text" name="name" id="name"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field"
                placeholder="Masukkan nama lengkap Anda" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email <span class="text-red-400">*</span></label>
            <input type="email" name="email" id="email"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field"
                placeholder="email@example.com" required>
            <div id="emailStatus" class="text-xs mt-1"></div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nomor Telepon <span class="text-red-400">*</span></label>
            <input type="text" name="phone" id="phone"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field"
                placeholder="08xxxxxxxxxx" required>
            <div class="text-xs text-gray-300 mt-1">Format: 08xxxxxxxxxx (10-13 digit)</div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Password <span class="text-red-400">*</span></label>
            <input type="password" name="password" id="password"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field"
                placeholder="Masukkan password Anda" required>
            <div id="passwordStrength" class="password-strength"></div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Konfirmasi Password <span class="text-red-400">*</span></label>
            <input type="password" name="confirm_password" id="confirm_password"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field"
                placeholder="Ulangi password Anda" required>
            <div id="passwordMatch" class="text-xs mt-1"></div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Alamat <span class="text-gray-400">(Opsional)</span></label>
            <textarea name="address" id="address"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field"
                placeholder="Masukkan alamat lengkap Anda" rows="3"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Jenis Kelamin</label>
            <select name="gender" id="gender"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field">
                <option value="" class="text-black bg-white">Pilih jenis kelamin</option>
                <option value="L" class="text-black bg-white">Laki-laki</option>
                <option value="P" class="text-black bg-white">Perempuan</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Tanggal Lahir</label>
            <input type="date" name="birth_date" id="birth_date"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white text-sm focus:outline-none focus:border-opacity-60 focus:bg-opacity-30 input-field">
        </div>

        <!-- Hidden field untuk peran -->
        <input type="hidden" name="peran" value="donatur">

        <div class="checkbox-container">
            <input type="checkbox" id="terms" name="terms" required class="mt-1">
            <label for="terms" class="terms-text">
                Saya menyetujui <span class="terms-link" onclick="showTerms()">Syarat dan Ketentuan</span> 
                serta <span class="terms-link" onclick="showPrivacy()">Kebijakan Privasi</span> 
                yang berlaku di platform ini.
            </label>
        </div>

        <div class="checkbox-container">
            <input type="checkbox" id="newsletter" name="newsletter">
            <label for="newsletter" class="terms-text">
                Saya ingin menerima informasi terbaru tentang program donasi dan kegiatan kemanusiaan.
            </label>
        </div>

        <button type="submit" class="w-full cta-button py-3 rounded-lg font-semibold text-base mt-6 transition-all duration-300">
            Daftar Sebagai Donatur
        </button>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-300">Sudah punya akun?
                <a href="login_form.php" class="text-yellow-400 hover:text-yellow-300 underline transition-colors duration-300">
                    Login di sini
                </a>
            </p>
        </div>
    </form>

    <script>
        // Form validation and submission handling
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                return;
            }

            // Show confirmation dialog
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p><strong>Nama:</strong> ${document.getElementById('name').value}</p>
                        <p><strong>Email:</strong> ${document.getElementById('email').value}</p>
                        <p><strong>Telepon:</strong> ${document.getElementById('phone').value}</p>
                        <p style="margin-top: 15px; font-size: 14px; color: rgba(0, 0, 0, 0.8);">
                            Pastikan data yang Anda masukkan sudah benar.
                        </p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Daftar Sekarang',
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true,
                backdrop: 'rgba(0,0,0,0.8)',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form normally to register_proses.php
                    this.submit();
                }
            });
        });

        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;

            // Validate name
            if (name.length < 2) {
                showError('Nama harus terdiri dari minimal 2 karakter');
                return false;
            }

            // Validate email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('Format email tidak valid');
                return false;
            }

            // Validate phone
            const phoneRegex = /^08[0-9]{8,11}$/;
            if (!phoneRegex.test(phone)) {
                showError('Nomor telepon harus dimulai dengan 08 dan terdiri dari 10-13 digit');
                return false;
            }

            // Validate password
            if (password.length < 6) {
                showError('Password harus terdiri dari minimal 6 karakter');
                return false;
            }

            // Validate password confirmation
            if (password !== confirmPassword) {
                showError('Konfirmasi password tidak sesuai');
                return false;
            }

            // Validate terms
            if (!terms) {
                showError('Anda harus menyetujui Syarat dan Ketentuan');
                return false;
            }

            return true;
        }

        function showError(message) {
            Swal.fire({
                title: 'Error Validasi',
                text: message,
                icon: 'error',
                confirmButtonText: 'OK',
                backdrop: 'rgba(0,0,0,0.8)'
            });
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }

            let strength = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) strength++;
            else feedback.push('minimal 8 karakter');

            // Uppercase check
            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('huruf besar');

            // Lowercase check
            if (/[a-z]/.test(password)) strength++;
            else feedback.push('huruf kecil');

            // Number check
            if (/[0-9]/.test(password)) strength++;
            else feedback.push('angka');

            // Special character check
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('karakter khusus');

            let strengthText = '';
            let strengthClass = '';

            if (strength < 2) {
                strengthText = 'Lemah';
                strengthClass = 'strength-weak';
            } else if (strength < 4) {
                strengthText = 'Sedang';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'Kuat';
                strengthClass = 'strength-strong';
            }

            strengthDiv.innerHTML = `<span class="${strengthClass}">Kekuatan Password: ${strengthText}</span>`;
            
            if (feedback.length > 0 && strength < 4) {
                strengthDiv.innerHTML += `<br><span style="color: rgba(255,255,255,0.7);">Tambahkan: ${feedback.join(', ')}</span>`;
            }
        });

        // Password confirmation checker
        document.getElementById('confirm_password').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = e.target.value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirmPassword.length === 0) {
                matchDiv.innerHTML = '';
                return;
            }

            if (password === confirmPassword) {
                matchDiv.innerHTML = '<span style="color: #10B981;">✓ Password cocok</span>';
            } else {
                matchDiv.innerHTML = '<span style="color: #EF4444;">✗ Password tidak cocok</span>';
            }
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 13) {
                value = value.substring(0, 13); // Limit to 13 digits
            }
            e.target.value = value;
        });

        // Real-time validation feedback
        document.getElementById('email').addEventListener('blur', function(e) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const statusDiv = document.getElementById('emailStatus');
            
            if (e.target.value && !emailRegex.test(e.target.value)) {
                e.target.style.borderColor = '#EF4444';
                statusDiv.innerHTML = '<span style="color: #EF4444;">✗ Format email tidak valid</span>';
            } else if (e.target.value) {
                e.target.style.borderColor = '#10B981';
                statusDiv.innerHTML = '<span style="color: #10B981;">✓ Format email valid</span>';
            } else {
                e.target.style.borderColor = '';
                statusDiv.innerHTML = '';
            }
        });

        document.getElementById('phone').addEventListener('blur', function(e) {
            const phoneRegex = /^08[0-9]{8,11}$/;
            if (e.target.value && !phoneRegex.test(e.target.value)) {
                e.target.style.borderColor = '#EF4444';
            } else if (e.target.value) {
                e.target.style.borderColor = '#10B981';
            } else {
                e.target.style.borderColor = '';
            }
        });

        // Terms and Privacy functions
        function showTerms() {
            Swal.fire({
                title: 'Syarat dan Ketentuan',
                html: `
                    <div style="text-align: left; max-height: 400px; overflow-y: auto; padding: 10px;">
                        <h4>1. Ketentuan Umum</h4>
                        <p>• Platform ini menghubungkan donatur dengan penerima bantuan</p>
                        <p>• Semua donasi bersifat sukarela dan tidak dapat dikembalikan</p>
                        <p>• Donatur bertanggung jawab atas keputusan donasi yang dibuat</p>
                        
                        <h4>2. Kewajiban Donatur</h4>
                        <p>• Memberikan informasi yang akurat dan benar</p>
                        <p>• Menggunakan platform sesuai dengan tujuan yang dimaksud</p>
                        <p>• Menghormati privasi penerima bantuan</p>
                        
                        <h4>3. Kebijakan Donasi</h4>
                        <p>• Minimum donasi Rp 10.000</p>
                        <p>• Donasi akan disalurkan sesuai dengan program yang dipilih</p>
                        <p>• Laporan penggunaan dana dapat diakses melalui dashboard</p>
                    </div>
                `,
                confirmButtonText: 'Mengerti',
                backdrop: 'rgba(0,0,0,0.8)',
                width: '600px'
            });
        }

        function showPrivacy() {
            Swal.fire({
                title: 'Kebijakan Privasi',
                html: `
                    <div style="text-align: left; max-height: 400px; overflow-y: auto; padding: 10px;">
                        <h4>1. Pengumpulan Data</h4>
                        <p>• Data personal: nama, email, nomor telepon</p>
                        <p>• Data donasi: jumlah, tanggal, program yang dipilih</p>
                        <p>• Data aktivitas: log aktivitas di platform</p>
                        
                        <h4>2. Penggunaan Data</h4>
                        <p>• Memproses dan melacak donasi</p>
                        <p>• Mengirim laporan dan update program</p>
                        <p>• Meningkatkan layanan platform</p>
                        
                        <h4>3. Perlindungan Data</h4>
                        <p>• Data dienkripsi dan disimpan dengan aman</p>
                        <p>• Tidak dibagikan kepada pihak ketiga tanpa izin</p>
                        <p>• Dapat dihapus atas permintaan pengguna</p>
                    </div>
                `,
                confirmButtonText: 'Mengerti',
                backdrop: 'rgba(0,0,0,0.8)',
                width: '600px'
            });
        }

        // Check for success/error messages from URL parameters
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            if (urlParams.get('success') === 'true') {
                Swal.fire({
                    title: 'Berhasil!',
                    html: `
                        <div style="margin: 20px 0;">
                            <p>Akun donatur Anda berhasil dibuat!</p>
                            <p style="margin-top: 15px; font-size: 14px; color: rgba(255,255,255,0.8);">
                                Sekarang Anda dapat login dan mulai berbagi kebaikan dengan sesama.
                            </p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Login Sekarang',
                    backdrop: 'rgba(0,0,0,0.8)',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login_form.php?registered=success&role=donatur';
                    }
                });
            } else if (urlParams.get('error')) {
                const errorMsg = urlParams.get('error');
                let message;
                
                switch(errorMsg) {
                    case 'email_exists':
                        message = 'Email sudah terdaftar. Silakan gunakan email lain atau login jika Anda sudah memiliki akun.';
                        break;
                    case 'phone_exists':
                        message = 'Nomor telepon sudah terdaftar. Silakan gunakan nomor lain atau login jika Anda sudah memiliki akun.';
                        break;
                    case 'password_mismatch':
                        message = 'Konfirmasi password tidak sesuai. Silakan periksa kembali password Anda.';
                        break;
                    case 'invalid_data':
                        message = 'Data yang dimasukkan tidak valid. Silakan periksa kembali semua field.';
                        break;
                    case 'terms_not_accepted':
                        message = 'Anda harus menyetujui Syarat dan Ketentuan untuk dapat mendaftar.';
                        break;
                    default:
                        message = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
                }
                
                Swal.fire({
                    title: 'Gagal Mendaftar',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    backdrop: 'rgba(0,0,0,0.8)'
                });
                
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // Set maximum date for birth date (18 years ago)
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            const maxDateString = maxDate.toISOString().split('T')[0];
            document.getElementById('birth_date').setAttribute('max', maxDateString);
        });
    </script>
</body>
</html>