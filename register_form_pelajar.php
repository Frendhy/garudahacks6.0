<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelajar - Platform Donasi</title>
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
        font-family: 'Poppins', sans-serif !important;
        font-weight: 700 !important;
    }

    .swal2-html-container {
        color: var(--light) !important;
        font-family: 'Poppins', sans-serif !important;
    }

    .swal2-input, .swal2-select {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 10px !important;
        color: var(--light) !important;
        font-family: 'Poppins', sans-serif !important;
        padding: 12px !important;
        font-size: 14px !important;
    }

    .swal2-input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .swal2-input:focus, .swal2-select:focus {
        border-color: var(--highlight) !important;
        box-shadow: 0 0 0 3px rgba(250, 229, 70, 0.3) !important;
        outline: none !important;
        transform: translateY(-2px);
    }

    .swal2-select option {
        background: var(--dark) !important;
        color: var(--light) !important;
    }

    .swal2-confirm {
        background: var(--highlight) !important;
        color: var(--dark) !important;
        border: none !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        padding: 12px 25px !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 14px !important;
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
        font-family: 'Poppins', sans-serif !important;
        font-size: 14px !important;
    }

    .swal2-cancel:hover {
        background: var(--light) !important;
        color: var(--dark) !important;
    }

    .swal2-icon.swal2-success {
        border-color: var(--highlight) !important;
        color: var(--highlight) !important;
    }

    .swal2-icon.swal2-error {
        border-color: #EF4444 !important;
        color: #EF4444 !important;
    }

    .swal2-validation-message {
        background: rgba(239, 68, 68, 0.2) !important;
        color: #EF4444 !important;
        border: 1px solid #EF4444 !important;
        border-radius: 8px !important;
        font-family: 'Poppins', sans-serif !important;
    }

    .input-field {
        transition: all 0.3s ease;
    }

    .input-field:focus {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
    }
</style>

<body class="modal">
    <form class="space-y-4 modal-content" id="studentRegistrationForm" method="post" enctype="multipart/form-data" action="register_proses.php">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 8px; background: linear-gradient(45deg, var(--light), var(--highlight)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                Daftar Sebagai Pelajar
            </h2>
            <p style="color: rgba(255, 255, 255, 0.8); font-size: 14px;">
                Bergabunglah dengan komunitas pelajar kami
            </p>
        </div>

        <div>
            <label class="text-sm font-medium mb-2">Nama Lengkap</label>
            <input type="text" name="name" id="name"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm input-field"
                placeholder="Masukkan nama lengkap Anda" required>
        </div>

        <div>
            <label class="text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" id="email"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm input-field"
                placeholder="email@example.com" required>
        </div>

        <div>
            <label class="text-sm font-medium mb-2">Nomor Telepon</label>
            <input type="text" name="phone" id="phone"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm input-field"
                placeholder="08xxxxxxxxxx" required>
        </div>

        <div>
            <label class="text-sm font-medium mb-2">Password</label>
            <input type="password" name="password" id="password"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm input-field"
                placeholder="Masukkan password Anda" required>
        </div>

        <div>
            <label class="text-sm font-medium mb-2">Peran</label>
            <select name="peran" id="peran"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white text-sm input-field"
                required>
                <option value="pelajar" class="text-black">Pelajar</option>
            </select>
        </div>

        <button type="submit" class="w-full cta-button py-3 rounded-lg font-semibold text-base mt-6">
            Buat Akun & Lanjutkan
        </button>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-300">Sudah punya akun?
                <a href="login_form.php" class="text-yellow-400 hover:text-yellow-300 underline bg-transparent border-none cursor-pointer">Login di sini</a>
            </p>
        </div>
    </form>

    <script>
        // Form submission with SweetAlert
        document.getElementById('studentRegistrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                password: document.getElementById('password').value,
                peran: document.getElementById('peran').value
            };

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Konfirmasi Pendaftaran Pelajar',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <div style="background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                            <p style="margin-bottom: 10px;"><strong>📚 Nama Lengkap:</strong><br>${formData.name}</p>
                            <p style="margin-bottom: 10px;"><strong>📧 Email:</strong><br>${formData.email}</p>
                            <p style="margin-bottom: 10px;"><strong>📱 Nomor Telepon:</strong><br>${formData.phone}</p>
                            <p><strong>🎓 Peran:</strong><br>Pelajar</p>
                        </div>
                        <p style="margin-top: 15px; font-size: 14px; color: rgba(255,255,255,0.8); text-align: center;">
                            Pastikan data yang Anda masukkan sudah benar sebelum melanjutkan.
                        </p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Daftar Sekarang',
                cancelButtonText: 'Periksa Kembali',
                reverseButtons: true,
                backdrop: 'rgba(0,0,0,0.8)',
                allowOutsideClick: false,
                width: '500px',
                preConfirm: () => {
                    // Validate form data before submission
                    if (!validateFormData(formData)) {
                        return false;
                    }
                    return formData;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    submitForm(formData);
                }
            });
        });

        // Form validation function
        function validateFormData(data) {
            // Name validation
            if (!data.name || data.name.length < 2) {
                showValidationError('Nama harus terdiri dari minimal 2 karakter');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!data.email || !emailRegex.test(data.email)) {
                showValidationError('Format email tidak valid');
                return false;
            }

            // Phone validation
            const phoneRegex = /^08[0-9]{8,11}$/;
            if (!data.phone || !phoneRegex.test(data.phone)) {
                showValidationError('Nomor telepon harus dimulai dengan 08 dan terdiri dari 10-13 digit');
                return false;
            }

            // Password validation
            if (!data.password || data.password.length < 6) {
                showValidationError('Password harus terdiri dari minimal 6 karakter');
                return false;
            }

            return true;
        }

        // Show validation error
        function showValidationError(message) {
            Swal.showValidationMessage(message);
        }

        // Submit form function
        function submitForm(data) {
            // Show loading
            Swal.fire({
                title: 'Mendaftarkan Akun...',
                html: `
                    <div style="margin: 20px 0;">
                        <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
                            <div style="width: 40px; height: 40px; border: 4px solid rgba(250, 229, 70, 0.3); border-radius: 50%; border-top-color: var(--highlight); animation: spin 1s ease-in-out infinite;"></div>
                        </div>
                        <p style="color: rgba(255,255,255,0.8);">Mohon tunggu sebentar...</p>
                    </div>
                    <style>
                        @keyframes spin {
                            to { transform: rotate(360deg); }
                        }
                    </style>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                backdrop: 'rgba(0,0,0,0.9)'
            });

            // Simulate form submission (replace with actual form submission)
            setTimeout(() => {
                // Simulate random success/error for demo
                const success = Math.random() > 0.3; // 70% success rate for demo

                if (success) {
                    showSuccessAlert(data);
                } else {
                    showErrorAlert();
                }
            }, 2000);

            // For actual implementation, uncomment this and remove the simulation above:
            /*
            // Create FormData object
            const formDataObj = new FormData();
            formDataObj.append('name', data.name);
            formDataObj.append('email', data.email);
            formDataObj.append('phone', data.phone);
            formDataObj.append('password', data.password);
            formDataObj.append('peran', data.peran);

            // Submit form
            fetch('register_proses.php', {
                method: 'POST',
                body: formDataObj
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showSuccessAlert(data);
                } else {
                    showErrorAlert(result.message);
                }
            })
            .catch(error => {
                showErrorAlert('Terjadi kesalahan saat menghubungi server');
            });
            */
        }

        // Show success alert
        function showSuccessAlert(data) {
            Swal.fire({
                title: 'Pendaftaran Berhasil! 🎉',
                html: `
                    <div style="margin: 25px 0;">
                        <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10B981; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                            <p style="margin-bottom: 15px; font-size: 16px;">
                                Selamat datang di komunitas pelajar, <strong>${data.name}</strong>!
                            </p>
                            <p style="margin-bottom: 10px; color: rgba(255,255,255,0.9);">
                                ✅ Akun pelajar berhasil dibuat
                            </p>
                            <p style="margin-bottom: 10px; color: rgba(255,255,255,0.9);">
                                📧 Email konfirmasi telah dikirim ke ${data.email}
                            </p>
                            <p style="color: rgba(255,255,255,0.9);">
                                🔐 Anda dapat login menggunakan email dan password yang telah dibuat
                            </p>
                        </div>
                        <p style="font-size: 14px; color: rgba(255,255,255,0.8); text-align: center;">
                            Klik tombol di bawah untuk melanjutkan ke halaman login
                        </p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Login Sekarang',
                backdrop: 'rgba(0,0,0,0.8)',
                allowOutsideClick: false,
                width: '550px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to login page
                    window.location.href = 'login_form.php?registered=success&role=pelajar';
                }
            });
        }

        // Show error alert
        function showErrorAlert(message = 'Terjadi kesalahan saat mendaftar') {
            Swal.fire({
                title: 'Pendaftaran Gagal',
                html: `
                    <div style="margin: 20px 0;">
                        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #EF4444; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                            <p style="margin-bottom: 15px; font-size: 16px;">
                                Maaf, terjadi kesalahan saat mendaftarkan akun Anda.
                            </p>
                            <p style="color: rgba(255,255,255,0.9); margin-bottom: 10px;">
                                ${message}
                            </p>
                        </div>
                        <p style="font-size: 14px; color: rgba(255,255,255,0.8); text-align: center;">
                            Silakan periksa kembali data Anda dan coba lagi
                        </p>
                    </div>
                `,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Coba Lagi',
                cancelButtonText: 'Batal',
                backdrop: 'rgba(0,0,0,0.8)',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset form or show registration form again
                    document.getElementById('studentRegistrationForm').reset();
                }
            });
        }

        // Real-time validation for form fields
        document.getElementById('name').addEventListener('blur', function(e) {
            const name = e.target.value.trim();
            if (name && name.length < 2) {
                e.target.style.borderColor = '#EF4444';
                e.target.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.3)';
            } else if (name) {
                e.target.style.borderColor = '#10B981';
                e.target.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.3)';
            } else {
                e.target.style.borderColor = '';
                e.target.style.boxShadow = '';
            }
        });

        document.getElementById('email').addEventListener('blur', function(e) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const email = e.target.value.trim();
            if (email && !emailRegex.test(email)) {
                e.target.style.borderColor = '#EF4444';
                e.target.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.3)';
            } else if (email) {
                e.target.style.borderColor = '#10B981';
                e.target.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.3)';
            } else {
                e.target.style.borderColor = '';
                e.target.style.boxShadow = '';
            }
        });

        document.getElementById('phone').addEventListener('input', function(e) {
            // Remove non-digits and limit to 13 characters
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 13) {
                value = value.substring(0, 13);
            }
            e.target.value = value;
        });

        document.getElementById('phone').addEventListener('blur', function(e) {
            const phoneRegex = /^08[0-9]{8,11}$/;
            const phone = e.target.value.trim();
            if (phone && !phoneRegex.test(phone)) {
                e.target.style.borderColor = '#EF4444';
                e.target.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.3)';
            } else if (phone) {
                e.target.style.borderColor = '#10B981';
                e.target.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.3)';
            } else {
                e.target.style.borderColor = '';
                e.target.style.boxShadow = '';
            }
        });

        document.getElementById('password').addEventListener('blur', function(e) {
            const password = e.target.value;
            if (password && password.length < 6) {
                e.target.style.borderColor = '#EF4444';
                e.target.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.3)';
            } else if (password) {
                e.target.style.borderColor = '#10B981';
                e.target.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.3)';
            } else {
                e.target.style.borderColor = '';
                e.target.style.boxShadow = '';
            }
        });

        // Handle URL parameters for success/error messages
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            if (urlParams.get('success') === 'true') {
                const name = urlParams.get('name') || 'Pelajar';
                showSuccessAlert({ name: name });
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
                    case 'invalid_data':
                        message = 'Data yang dimasukkan tidak valid. Silakan periksa kembali semua field.';
                        break;
                    case 'password_too_short':
                        message = 'Password harus terdiri dari minimal 6 karakter.';
                        break;
                    default:
                        message = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
                }
                
                showErrorAlert(message);
                
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        // Clear validation styling on focus
        document.querySelectorAll('.input-field').forEach(field => {
            field.addEventListener('focus', function() {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            });
        });
    </script>
</body>
</html>