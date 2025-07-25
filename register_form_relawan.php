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
        to { transform: scale(1); }
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); opacity: 0; }
    }

    /* Custom SweetAlert2 Styling */
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

    .swal2-content {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .swal2-confirm {
        background: var(--highlight) !important;
        color: var(--dark) !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 12px 24px !important;
        transition: all 0.3s ease !important;
    }

    .swal2-confirm:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px rgba(250, 229, 70, 0.4) !important;
    }

    .swal2-cancel {
        background: rgba(255, 255, 255, 0.2) !important;
        color: var(--light) !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 12px 24px !important;
    }

    .swal2-icon.swal2-success {
        border-color: var(--highlight) !important;
        color: var(--highlight) !important;
    }

    .swal2-icon.swal2-error {
        border-color: #ef4444 !important;
        color: #ef4444 !important;
    }

    .swal2-icon.swal2-warning {
        border-color: #f59e0b !important;
        color: #f59e0b !important;
    }

    .input-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
    }

    .input-success {
        border-color: var(--highlight) !important;
        box-shadow: 0 0 0 2px rgba(250, 229, 70, 0.2) !important;
    }
</style>

<body class="modal">
    <form class="space-y-4 modal-content" id="registrationForm" method="post" enctype="multipart/form-data" action="register_proses.php">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-white">Daftar Relawan</h2>
            <p class="text-gray-300 text-sm mt-2">Bergabunglah dengan komunitas relawan kami</p>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
            <input type="text" name="name" id="name"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm transition-all duration-300"
                placeholder="Masukkan nama lengkap Anda" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="email"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm transition-all duration-300"
                placeholder="email@example.com" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Nomor Telepon</label>
            <input type="text" name="phone" id="phone"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm transition-all duration-300"
                placeholder="08xxxxxxxxxx" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Password</label>
            <input type="password" name="password" id="password"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm transition-all duration-300"
                placeholder="Masukkan password Anda" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Konfirmasi Password</label>
            <input type="password" name="confirm_password" id="confirm_password"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm transition-all duration-300"
                placeholder="Konfirmasi password Anda" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Peran</label>
            <select name="peran" id="peran"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white text-sm transition-all duration-300"
                required>
                <option value="relawan" class="text-black">Relawan</option>
            </select>
        </div>

        <button type="submit" class="w-full cta-button py-3 rounded-lg font-semibold text-base mt-6" id="submitBtn">
            <span id="buttonText">Buat Akun & Lanjutkan</span>
            <span id="loadingText" class="hidden">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-300">Sudah punya akun?
                <a href="login_form.php"
                    class="text-yellow-400 hover:text-yellow-300 underline bg-transparent border-none cursor-pointer transition-colors duration-300">Login
                    di sini</a>
            </p>
        </div>
    </form>

    <script>
        // Initialize SweetAlert2 with custom configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: 'linear-gradient(135deg, var(--primary), var(--secondary))',
            color: 'var(--light)',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Form validation and submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form elements
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const submitBtn = document.getElementById('submitBtn');
            const buttonText = document.getElementById('buttonText');
            const loadingText = document.getElementById('loadingText');

            // Reset previous error states
            [name, email, phone, password, confirmPassword].forEach(input => {
                input.classList.remove('input-error', 'input-success');
            });

            // Validation
            let isValid = true;
            let errors = [];

            // Name validation
            if (name.value.trim().length < 2) {
                name.classList.add('input-error');
                errors.push('Nama lengkap minimal 2 karakter');
                isValid = false;
            } else {
                name.classList.add('input-success');
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                email.classList.add('input-error');
                errors.push('Format email tidak valid');
                isValid = false;
            } else {
                email.classList.add('input-success');
            }

            // Phone validation (Indonesian phone number)
            const phoneRegex = /^(08|628|\+628)[0-9]{8,12}$/;
            if (!phoneRegex.test(phone.value.replace(/\s+/g, ''))) {
                phone.classList.add('input-error');
                errors.push('Nomor telepon tidak valid (gunakan format: 08xxxxxxxxxx)');
                isValid = false;
            } else {
                phone.classList.add('input-success');
            }

            // Password validation
            if (password.value.length < 6) {
                password.classList.add('input-error');
                errors.push('Password minimal 6 karakter');
                isValid = false;
            } else {
                password.classList.add('input-success');
            }

            // Confirm password validation
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('input-error');
                errors.push('Konfirmasi password tidak cocok');
                isValid = false;
            } else {
                confirmPassword.classList.add('input-success');
            }

            // Show validation errors
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: errors.map(error => `• ${error}`).join('<br>'),
                    confirmButtonText: 'Perbaiki',
                    customClass: {
                        popup: 'swal2-popup',
                        title: 'swal2-title',
                        content: 'swal2-content',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return;
            }

            // Show loading state
            buttonText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            submitBtn.disabled = true;

            // Simulate form submission (replace with actual AJAX call)
            setTimeout(() => {
                // Reset button state
                buttonText.classList.remove('hidden');
                loadingText.classList.add('hidden');
                submitBtn.disabled = false;

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil!',
                    text: 'Akun relawan Anda telah berhasil dibuat. Selamat bergabung!',
                    confirmButtonText: 'Lanjutkan',
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'swal2-popup',
                        title: 'swal2-title',
                        content: 'swal2-content',
                        confirmButton: 'swal2-confirm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'volunteer.php';
                    }
                });
            }, 2000);
        });

        // Real-time validation feedback
        const inputs = ['name', 'email', 'phone', 'password', 'confirm_password'];
        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            input.addEventListener('blur', function() {
                this.classList.remove('input-error', 'input-success');
                
                // Simple validation feedback
                if (this.value.trim() !== '') {
                    if (inputId === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        this.classList.add(emailRegex.test(this.value) ? 'input-success' : 'input-error');
                    } else if (inputId === 'phone') {
                        const phoneRegex = /^(08|628|\+628)[0-9]{8,12}$/;
                        this.classList.add(phoneRegex.test(this.value.replace(/\s+/g, '')) ? 'input-success' : 'input-error');
                    } else if (inputId === 'confirm_password') {
                        const password = document.getElementById('password').value;
                        this.classList.add(this.value === password && this.value.length >= 6 ? 'input-success' : 'input-error');
                    } else {
                        this.classList.add(this.value.trim().length >= 2 ? 'input-success' : 'input-error');
                    }
                }
            });
        });

        // Show welcome message on page load
        window.addEventListener('load', function() {
            Toast.fire({
                icon: 'info',
                title: 'Selamat datang! Silakan lengkapi formulir pendaftaran.'
            });
        });

        // Handle network errors or PHP errors (for actual implementation)
        window.addEventListener('unhandledrejection', function(e) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Koneksi bermasalah. Silakan periksa internet Anda dan coba lagi.',
                confirmButtonText: 'OK'
            });
        });
    </script>
</body>