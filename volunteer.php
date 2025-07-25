<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard - Equalizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--light);
            min-height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .input-field {
            width: 100%;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--highlight);
            box-shadow: 0 0 20px rgba(250, 229, 70, 0.3);
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .volunteer-button {
            background: linear-gradient(45deg, #8B5CF6, #A78BFA);
            transition: all 0.3s ease;
        }

        .volunteer-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);
        }

        /* Custom SweetAlert2 styling */
        .swal2-popup {
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }

        .swal2-title {
            color: white !important;
        }

        .swal2-html-container {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .swal2-confirm {
            background: linear-gradient(45deg, #3B82F6, #A78BFA) !important;
            border: none !important;
        }

        .swal2-cancel {
            background: linear-gradient(45deg, #EF4444, #F87171) !important;
            border: none !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="px-6 md:px-10 py-5 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="text-2xl font-bold">Equalizer</div>
            <span class="text-sm bg-purple-500 px-3 py-1 rounded-full">Volunteer</span>
        </div>
        <div class="flex items-center space-x-4">
            <span class="hidden md:inline text-sm" id="welcomeText">Welcome!</span>
            <button onclick="showLogoutConfirmation()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm transition-colors">
                Logout
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-6 md:px-10 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" id="volunteerName">Dashboard Relawan</h1>
            <p class="text-lg opacity-90">Kelola aktivitas mengajar dan dampak kontribusi Anda. Cek WhatsApp Anda secara berkala.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="card rounded-xl p-6 cursor-pointer" onclick="showStudentDetails()">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Siswa Diajar</h3>
                        <p class="text-2xl font-bold text-blue-400">28</p>
                    </div>
                    <div class="text-3xl">👥</div>
                </div>
            </div>
            <div class="card rounded-xl p-6 cursor-pointer" onclick="showRatingDetails()">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Rating</h3>
                        <p class="text-2xl font-bold text-yellow-400">4.8/5</p>
                    </div>
                    <div class="text-3xl">⭐</div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="card rounded-xl p-8">
            <h3 class="text-2xl font-bold mb-6">Profil Saya</h3>
            <form onsubmit="updateProfile(event)" class="space-y-4">
                <!-- Baris 1 -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Lengkap:</label>
                        <input type="text" id="fullName" value="Ahmad Rizki Pratama" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Status (Mahasiswa/Alumni & Institusi):</label>
                        <input type="text" id="status" value="Mahasiswa - Universitas Indonesia" class="input-field">
                    </div>
                </div>

                <!-- Baris 2 -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Jurusan:</label>
                        <input type="text" id="major" value="Teknik Informatika" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Mengajar Apa:</label>
                        <input type="text" id="subject" value="Matematika & Fisika" class="input-field">
                    </div>
                </div>

                <!-- Baris 3 -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Link WhatsApp:</label>
                        <input type="text" id="whatsapp" value="https://wa.me/081234567890" class="input-field" placeholder="https://wa.me/08xxxxx">
                    </div>
                    <div></div> <!-- Kolom kosong untuk menjaga grid seimbang -->
                </div>

                <button type="submit" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                    Update Profil
                </button>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <button onclick="showSchedule()" class="card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                <div class="text-4xl mb-3">📅</div>
                <h3 class="text-lg font-semibold">Jadwal Mengajar</h3>
                <p class="text-sm opacity-75">Lihat jadwal mengajar minggu ini</p>
            </button>
            
            <button onclick="showMessages()" class="card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                <div class="text-4xl mb-3">💬</div>
                <h3 class="text-lg font-semibold">Pesan Siswa</h3>
                <p class="text-sm opacity-75">3 pesan baru menunggu</p>
            </button>
            
            <button onclick="showAchievements()" class="card rounded-xl p-6 text-center hover:scale-105 transition-transform">
                <div class="text-4xl mb-3">🏆</div>
                <h3 class="text-lg font-semibold">Pencapaian</h3>
                <p class="text-sm opacity-75">Lihat progress mengajar Anda</p>
            </button>
        </div>
    </main>

    <script>
        // Initialize welcome message
        document.addEventListener('DOMContentLoaded', function() {
            showWelcomeMessage();
        });

        // Welcome message with SweetAlert
        function showWelcomeMessage() {
            Swal.fire({
                title: '🎉 Selamat Datang!',
                html: 'Halo <strong>Ahmad Rizki</strong>!<br>Semoga hari ini menyenangkan dalam mengajar.',
                icon: 'success',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        // Logout confirmation
        function showLogoutConfirmation() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Anda akan keluar dari dashboard volunteer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil Logout!',
                        text: 'Terima kasih atas kontribusi Anda hari ini',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        // Simulate logout redirect
                        window.location.href = 'index.php';
                    });
                }
            });
        }

        // Update profile form
        function updateProfile(event) {
            event.preventDefault();
            
            const formData = {
                name: document.getElementById('fullName').value,
                status: document.getElementById('status').value,
                major: document.getElementById('major').value,
                subject: document.getElementById('subject').value,
                whatsapp: document.getElementById('whatsapp').value
            };

            // Simulate loading
            Swal.fire({
                title: 'Memperbarui Profil...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate API call
            setTimeout(() => {
                Swal.fire({
                    title: 'Profil Berhasil Diperbarui!',
                    html: `
                        <div class="text-left">
                            <p><strong>Nama:</strong> ${formData.name}</p>
                            <p><strong>Status:</strong> ${formData.status}</p>
                            <p><strong>Mengajar:</strong> ${formData.subject}</p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Oke'
                });
            }, 1500);
        }

        // Student details
        function showStudentDetails() {
            Swal.fire({
                title: '👥 Detail Siswa',
                html: `
                    <div class="text-left space-y-2">
                        <p><strong>Total Siswa:</strong> 28 siswa</p>
                        <p><strong>Aktif Minggu Ini:</strong> 22 siswa</p>
                        <p><strong>Siswa Baru:</strong> 3 siswa</p>
                        <p><strong>Rata-rata Kehadiran:</strong> 85%</p>
                        <hr class="my-3">
                        <p class="text-sm opacity-75">Siswa paling aktif: Sarah (15 sesi)</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Tutup'
            });
        }

        // Rating details
        function showRatingDetails() {
            Swal.fire({
                title: '⭐ Detail Rating',
                html: `
                    <div class="text-left space-y-2">
                        <p><strong>Rating Rata-rata:</strong> 4.8/5</p>
                        <p><strong>Total Review:</strong> 45 review</p>
                        <p><strong>5 Bintang:</strong> 38 review (84%)</p>
                        <p><strong>4 Bintang:</strong> 6 review (13%)</p>
                        <p><strong>3 Bintang:</strong> 1 review (3%)</p>
                        <hr class="my-3">
                        <p class="text-sm opacity-75">"Penjelasan sangat mudah dipahami!" - Siti</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Tutup'
            });
        }

        // Schedule
        function showSchedule() {
            Swal.fire({
                title: '📅 Jadwal Mengajar',
                html: `
                    <div class="text-left space-y-2">
                        <div class="bg-blue-100 p-3 rounded text-black">
                            <p><strong>Hari Ini (Jumat)</strong></p>
                            <p>• 16:00 - Matematika dengan Andi</p>
                            <p>• 19:00 - Fisika dengan Sarah</p>
                        </div>
                        <div class="bg-gray-100 p-3 rounded text-black">
                            <p><strong>Besok (Sabtu)</strong></p>
                            <p>• 10:00 - Matematika dengan Budi</p>
                            <p>• 14:00 - Fisika dengan Dewi</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Tutup',
                width: 500
            });
        }

        // Messages
        function showMessages() {
            Swal.fire({
                title: '💬 Pesan Siswa',
                html: `
                    <div class="text-left space-y-3">
                        <div class="bg-yellow-100 p-3 rounded text-black">
                            <p><strong>Sarah:</strong> "Kak, bisa bantu PR matematika?"</p>
                            <p class="text-sm opacity-60">2 jam yang lalu</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded text-black">
                            <p><strong>Andi:</strong> "Terima kasih penjelasan fisikanya!"</p>
                            <p class="text-sm opacity-60">4 jam yang lalu</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded text-black">
                            <p><strong>Dewi:</strong> "Jadwal besok bisa dimajukan?"</p>
                            <p class="text-sm opacity-60">1 hari yang lalu</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Buka WhatsApp',
                showCancelButton: true,
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('https://wa.me/081234567890', '_blank');
                }
            });
        }

        // Achievements
        function showAchievements() {
            Swal.fire({
                title: '🏆 Pencapaian Anda',
                html: `
                    <div class="text-left space-y-3">
                        <div class="flex items-center justify-between bg-gold-100 p-3 rounded">
                            <span>🥇 Volunteer Terbaik Bulan Ini</span>
                            <span class="text-sm bg-yellow-400 px-2 py-1 rounded text-black">BARU!</span>
                        </div>
                        <div class="flex items-center justify-between bg-gray-100 p-3 rounded text-black">
                            <span>⭐ Rating 4.8+ selama 3 bulan</span>
                            <span class="text-green-600">✓</span>
                        </div>
                        <div class="flex items-center justify-between bg-gray-100 p-3 rounded text-black">
                            <span>👥 Mengajar 25+ siswa</span>
                            <span class="text-green-600">✓</span>
                        </div>
                        <div class="flex items-center justify-between bg-gray-100 p-3 rounded text-black">
                            <span>🎯 100+ jam mengajar</span>
                            <span class="text-blue-600">78/100</span>
                        </div>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Bagikan Pencapaian',
                showCancelButton: true,
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil Dibagikan!',
                        text: 'Pencapaian Anda telah dibagikan ke media sosial',
                        icon: 'success',
                        timer: 2000
                    });
                }
            });
        }
    </script>
</body>
</html>