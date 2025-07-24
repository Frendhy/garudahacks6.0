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
            <p class="text-lg opacity-90">Kelola aktivitas mengajar dan dampak kontribusi Anda. Cek WhatsApp Anda secara berkala.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
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
            <form class="space-y-4">
                <!-- Baris 1 -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Lengkap:</label>
                        <input type="text" value="${volunteerData.name}" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Status (Mahasiswa/Alumni & Institusi):</label>
                        <input type="text" value="${volunteerData.status}" class="input-field">
                    </div>
                </div>

                <!-- Baris 2 -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Jurusan:</label>
                        <input type="text" value="${volunteerData.major}" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Mengajar Apa:</label>
                        <input type="text" value="${volunteerData.subject}" class="input-field">
                    </div>
                </div>

                <!-- Baris 3 -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Link WhatsApp:</label>
                        <input type="text" value="${volunteerData.whatsapp}" class="input-field" placeholder="https://wa.me/08xxxxx">
                    </div>
                    <div></div> <!-- Kolom kosong untuk menjaga grid seimbang -->
                </div>

                <button type="submit" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                    Update Profil
                </button>
            </form>
        </div>
    </main>
</body>