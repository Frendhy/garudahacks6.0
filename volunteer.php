<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard - Equalizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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

        .schedule-item {
            border-left: 4px solid var(--highlight);
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .schedule-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .volunteer-button {
            background: linear-gradient(45deg, #8B5CF6, #A78BFA);
            transition: all 0.3s ease;
        }

        .volunteer-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);
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
            <button onclick="logout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm transition-colors">
                Logout
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-6 md:px-10 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" id="volunteerName">Dashboard Relawan</h1>
            <p class="text-lg opacity-90">Kelola aktivitas mengajar dan dampak kontribusi Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Jam Mengajar</h3>
                        <p class="text-2xl font-bold text-purple-400">45</p>
                    </div>
                    <div class="text-3xl">⏰</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Siswa Diajar</h3>
                        <p class="text-2xl font-bold text-blue-400">28</p>
                    </div>
                    <div class="text-3xl">👥</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Kelas Aktif</h3>
                        <p class="text-2xl font-bold text-green-400">4</p>
                    </div>
                    <div class="text-3xl">📚</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Rating</h3>
                        <p class="text-2xl font-bold text-yellow-400">4.8/5</p>
                    </div>
                    <div class="text-3xl">⭐</div>
                </div>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Jadwal Hari Ini</h2>
            <div class="card rounded-xl p-6">
                <div class="space-y-4">
                    <div class="schedule-item p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="text-2xl">📊</div>
                                <div>
                                    <h3 class="font-semibold">Matematika - Kelas 10</h3>
                                    <p class="text-sm opacity-70">09:00 - 10:30 WIB</p>
                                    <p class="text-xs opacity-60">15 siswa</p>
                                </div>
                            </div>
                            <button class="volunteer-button px-4 py-2 rounded-lg text-sm font-medium">
                                Mulai Kelas
                            </button>
                        </div>
                    </div>
                    <div class="schedule-item p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="text-2xl">🇬🇧</div>
                                <div>
                                    <h3 class="font-semibold">Bahasa Inggris - Kelas 11</h3>
                                    <p class="text-sm opacity-70">13:00 - 14:30 WIB</p>
                                    <p class="text-xs opacity-60">12 siswa</p>
                                </div>
                            </div>
                            <button class="volunteer-button px-4 py-2 rounded-lg text-sm font-medium">
                                Mulai Kelas
                            </button>
                        </div>
                    </div>
                    <div class="schedule-item p-4 rounded-lg opacity-60">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="text-2xl">⚗️</div>
                                <div>
                                    <h3 class="font-semibold">Kimia - Kelas 12</h3>
                                    <p class="text-sm opacity-70">15:00 - 16:30 WIB</p>
                                    <p class="text-xs opacity-60">8 siswa</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-400">Selesai</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Opportunities -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Peluang Mengajar</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="card rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="text-3xl">📐</div>
                        <div>
                            <h3 class="text-lg font-semibold">Matematika Lanjutan</h3>
                            <p class="text-sm opacity-70">SMA Kelas 12</p>
                        </div>
                    </div>
                    <p class="text-sm opacity-80 mb-4">Butuh bantuan untuk persiapan ujian nasional</p>
                    <div class="mb-4">
                        <p class="text-xs text-gray-300">📅 Senin & Rabu, 14:00-15:30</p>
                        <p class="text-xs text-gray-300">👥 10 siswa</p>
                        <p class="text-xs text-gray-300">💰 Rp 150,000/sesi</p>
                    </div>
                    <button class="volunteer-button w-full py-2 rounded-lg text-sm font-medium">
                        Ambil Kelas
                    </button>
                </div>
                <div class="card rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="text-3xl">🌍</div>
                        <div>
                            <h3 class="text-lg font-semibold">Geografi</h3>
                            <p class="text-sm opacity-70">SMA Kelas 11</p>
                        </div>
                    </div>
                    <p class="text-sm opacity-80 mb-4">Materi tentang iklim dan cuaca Indonesia</p>
                    <div class="mb-4">
                        <p class="text-xs text-gray-300">📅 Selasa & Kamis, 10:00-11:30</p>
                        <p class="text-xs text-gray-300">👥 8 siswa</p>
                        <p class="text-xs text-gray-300">💰 Rp 120,000/sesi</p>
                    </div>
                    <button class="volunteer-button w-full py-2 rounded-lg text-sm font-medium">
                        Ambil Kelas
                    </button>
                </div>
                <div class="card rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="text-3xl">💻</div>
                        <div>
                            <h3 class="text-lg font-semibold">Komputer Dasar</h3>
                            <p class="text-sm opacity-70">SMP Kelas 7-9</p>
                        </div>
                    </div>
                    <p class="text-sm opacity-80 mb-4">Pengenalan Microsoft Office dan internet</p>
                    <div class="mb-4">
                        <p class="text-xs text-gray-300">📅 Sabtu, 09:00-12:00</p>