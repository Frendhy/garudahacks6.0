<?php
// Start the session and include the database connection
session_start();
include('db_connection.php'); // Make sure this file connects to your MySQL database

// Get donor ID from session or another mechanism (e.g., after login)
$donor_id = $_SESSION['donor_id']; // Adjust this based on how your system manages logged-in users

// Fetch donor's donation requests
$query = "SELECT * FROM donation_requests WHERE student_id IN (SELECT id FROM students WHERE donor_id = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the donation history
$queryDonations = "SELECT * FROM donations WHERE donor_id = ?";
$stmtDonations = $conn->prepare($queryDonations);
$stmtDonations->bind_param("i", $donor_id);
$stmtDonations->execute();
$resultDonations = $stmtDonations->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Donatur - Equalizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.all.min.js"></script>
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
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            color: var(--dark);
        }

        .donate-button {
            background: linear-gradient(45deg, var(--highlight), #ff6b6b);
            color: var(--dark);
            transition: all 0.3s ease;
        }

        .donate-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(250, 229, 70, 0.4);
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: var(--secondary);
            color: var(--light);
        }

        .progress-bar {
            height: 20px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            transition: width 0.3s ease;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge.urgent {
            background: #dc2626;
            color: white;
        }

        .badge.pending {
            background: #f59e0b;
            color: white;
        }

        .badge.waiting_proof {
            background: var(--highlight);
            color: var(--dark);
        }

        .badge.completed,
        .badge.verified {
            background: #16a34a;
            color: white;
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
            border-radius: 15px !important;
        }

        .upload-area {
            border: 2px dashed #A78BFA;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(167, 139, 250, 0.1);
        }

        .upload-area:hover {
            background: rgba(167, 139, 250, 0.2);
            border-color: #8B5CF6;
        }

        .file-preview {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid #22c55e;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="px-6 md:px-10 py-5 flex justify-between items-center backdrop-blur-md bg-white bg-opacity-10">
        <div class="flex items-center space-x-4">
            <div class="text-2xl font-bold">🤝 Equalizer</div>
            <span class="text-sm bg-yellow-500 text-black px-3 py-1 rounded-full font-medium">Donatur</span>
        </div>
        <div class="flex items-center space-x-4">
            <span class="hidden md:inline text-sm" id="welcomeText">Halo, Budi Santoso</span>
            <button onclick="logout()"
                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm transition-colors">
                Keluar
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-6 md:px-10 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" id="donaturName">Dashboard Donatur</h1>
            <p class="text-lg opacity-90">Kelola donasi dan lihat dampak kontribusi Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Total Donasi</h3>
                        <p class="text-2xl font-bold text-yellow-400">Rp 150,000</p>
                    </div>
                    <div class="text-3xl">💰</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Pelajar Terbantu</h3>
                        <p class="text-2xl font-bold text-blue-400">1</p>
                    </div>
                    <div class="text-3xl">🎓</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Donasi Aktif</h3>
                        <p class="text-2xl font-bold text-green-400">1</p>
                    </div>
                    <div class="text-3xl">📈</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Status</h3>
                        <p class="text-2xl font-bold text-purple-400">Aktif</p>
                    </div>
                    <div class="text-3xl">🌟</div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-2 mb-6">
                <button onclick="showSection('requests')"
                    class="tab-button active px-6 py-3 rounded-lg font-medium transition-all" id="requests-btn">
                    Permintaan Bantuan
                </button>
                <button onclick="showSection('donations')"
                    class="tab-button px-6 py-3 rounded-lg font-medium transition-all hover:bg-white hover:bg-opacity-10"
                    id="donations-btn">
                    Donasi Saya
                </button>
                <button onclick="showSection('impact')"
                    class="tab-button px-6 py-3 rounded-lg font-medium transition-all hover:bg-white hover:bg-opacity-10"
                    id="impact-btn">
                    Dampak Donasi
                </button>
                <button onclick="showSection('profile')"
                    class="tab-button px-6 py-3 rounded-lg font-medium transition-all hover:bg-white hover:bg-opacity-10"
                    id="profile-btn">
                    Profil
                </button>
            </div>

            <!-- Content Area -->
            <div class="content-card rounded-xl p-8" id="content">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </main>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-luNrPHRSBmDtAsLb"></script>
    <script>
        // Sample data
        let donorData = {
            name: 'Budi Santoso',
            email: 'budi.santoso@email.com',
            phone: '081234567890',
            donations: [
                {
                    id: 1,
                    item: 'Sepatu Sekolah',
                    student: 'Ahmad Rizki',
                    amount: 150000,
                    date: '2025-07-20',
                    status: 'verified',
                    note: 'Semoga bermanfaat untuk sek3olah!',
                    proofUrl: null
                }
            ],
            requests: [
                { id: 1, student: 'Ahmad Rizki', item: 'Kuota Internet', amount: 50000, status: 'urgent', progress: 0, collected: 0 },
                { id: 2, student: 'Ahmad Rizki', item: 'Biaya SPP', amount: 200000, status: 'pending', progress: 25, collected: 50000 },
                { id: 3, student: 'Sari Putri', item: 'Buku Pelajaran', amount: 300000, status: 'pending', progress: 40, collected: 120000 },
                { id: 4, student: 'Maya Sari', item: 'Seragam Sekolah', amount: 250000, status: 'urgent', progress: 10, collected: 25000 }
            ]
        };

        function showSection(section) {
            // Update active tab
            document.querySelectorAll('.tab-button').forEach(b => {
                b.classList.remove('active');
                b.classList.add('hover:bg-white', 'hover:bg-opacity-10');
            });
            document.getElementById(section + '-btn').classList.add('active');
            document.getElementById(section + '-btn').classList.remove('hover:bg-white', 'hover:bg-opacity-10');

            const content = document.getElementById('content');
            switch (section) {
                case 'requests':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Permintaan Bantuan Siswa</h3>
                        
                        <!-- Filter Section -->
                        <div class="filter-section rounded-lg p-6 mb-6">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-black">Filter Status:</label>
                                    <select id="filterStatus" onchange="filterRequests()" class="w-full p-3 rounded-lg bg-white bg-opacity-20 text-black border border-black border-opacity-30 focus:border-opacity-60 outline-none">
                                        <option value="">Semua</option>
                                        <option value="urgent">Mendesak</option>
                                        <option value="pending">Menunggu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-black">Cari Nama Siswa:</label>
                                    <input type="text" id="searchStudent" placeholder="Nama siswa..." oninput="filterRequests()" class="w-full p-3 rounded-lg bg-white bg-opacity-20 text-black placeholder-white placeholder-opacity-70 border border-black border-opacity-30 focus:border-opacity-60 outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Requests Grid -->
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="requestsGrid">
                            <?php
                            foreach ($donation_requests as $request) {
                                echo "
                        <div class='bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all'>
                            <h4 class='text-xl font-bold mb-3 text-gray-800'>{$request['item']}</h4>
                            <p class='text-gray-600 mb-2'>Nama Siswa: <span class='font-medium'>{$request['student_id']}</span></p>
                            <p class='text-gray-600 mb-2'>Estimasi: <span class='font-bold text-blue-600'>Rp " . number_format($request['amount']) . "</span></p>
                            <p class='mb-3'>Status: <span class='badge {$request['status']}'>" . ucfirst($request['status']) . "</span></p>
                            <div class='progress-bar mb-3'>
                                <div class='progress-fill' style='width: {$request['progress']}%'></div>
                            </div>
                            <p class='text-sm text-gray-600 mb-4'>Dana terkumpul: <span class='font-bold text-green-600'>Rp " . number_format($request['collected']) . "</span></p>
                            <button class='w-full donate-button py-3 px-4 rounded-lg font-medium' onclick='donate({$request['id']})'>
                                Donasi Sekarang
                            </button>
                        </div>";
                            }
                            ?>
                        </div>
                    `;
                    break;

                case 'donations':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Donasi Saya</h3>
                        <div class="space-y-6">
                            ${donorData.donations.map(donation => `
                                <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-purple-500">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-xl font-bold mb-2 text-gray-800">${donation.item}</h4>
                                            <p class="text-gray-600 mb-1">Untuk: <span class="font-medium">${donation.student}</span></p>
                                            <p class="text-gray-600 mb-1">Jumlah: <span class="font-bold text-blue-600">Rp ${donation.amount.toLocaleString()}</span></p>
                                            <p class="text-gray-600 mb-1">Tanggal: ${new Date(donation.date).toLocaleDateString('id-ID')}</p>
                                            <p class="mb-2">Status: <span class="badge ${donation.status}">${donation.status === 'verified' ? 'Tervalidasi' : donation.status === 'pending' ? 'Menunggu Validasi' : 'Ditolak'}</span></p>
                                            <p class="text-gray-700 italic">"${donation.note}"</p>
                                            ${donation.proofUrl ? `<p class="mt-2">Bukti Transfer: <a href="${donation.proofUrl}" target="_blank" class="text-blue-500 hover:underline">Lihat Bukti</a></p>` : ''}
                                        </div>
                                        <div class="mt-4 md:mt-0 md:ml-6">
                                            ${donation.status === 'pending' && !donation.proofUrl ?
                            `<button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors" onclick="uploadProof(${donation.id})">Upload Bukti Transfer</button>` : ''
                        }
                                            ${donation.status === 'waiting_proof' ?
                            `<button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors" onclick="uploadProof(${donation.id})">Upload Bukti Transfer</button>` : ''
                        }
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    break;

                case 'impact':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Dampak Donasi Anda</h3>
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 border-l-4 border-green-500">
                            <h4 class="text-xl font-bold mb-3 text-green-700">Sepatu Sekolah untuk Ahmad Rizki</h4>
                            <p class="text-gray-700 mb-2">Status: <span class="text-green-600 font-medium">✓ Tervalidasi</span></p>
                            <p class="text-gray-700 mb-2">Ucapan Terima Kasih:</p>
                            <blockquote class="bg-white p-4 rounded-lg border-l-4 border-blue-400 italic text-gray-700 mb-3">
                                "Terima kasih banyak Pak Budi! Sepatu ini sangat membantu saya di sekolah. Sekarang saya bisa belajar dengan nyaman dan percaya diri."
                            </blockquote>
                            <p class="text-gray-600">Tanggal Validasi: 21 Juli 2025</p>
                            <div class="mt-4 flex items-center space-x-4">
                                <div class="text-3xl">👟</div>
                                <div class="text-3xl">➡️</div>
                                <div class="text-3xl">😊</div>
                                <div class="text-3xl">🎓</div>
                            </div>
                        </div>
                        
                        <div class="mt-8 grid md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                                <div class="text-4xl mb-3">💝</div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Total Dampak</h4>
                                <p class="text-2xl font-bold text-blue-600">1 Siswa</p>
                                <p class="text-gray-600">Terbantu</p>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                                <div class="text-4xl mb-3">📈</div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Peningkatan</h4>
                                <p class="text-2xl font-bold text-green-600">95%</p>
                                <p class="text-gray-600">Kepercayaan Diri</p>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                                <div class="text-4xl mb-3">⭐</div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Rating</h4>
                                <p class="text-2xl font-bold text-yellow-500">5.0</p>
                                <p class="text-gray-600">Kepuasan</p>
                            </div>
                        </div>
                    `;
                    break;

                case 'profile':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Profil Donatur</h3>
                        <form onsubmit="updateProfile(event)" class="max-w-2xl">
                            <div class="grid gap-6">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-gray-700">Nama Lengkap:</label>
                                    <input type="text" id="profileName" value="${donorData.name}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-gray-700">Email:</label>
                                    <input type="email" id="profileEmail" value="${donorData.email}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-gray-700">Nomor Telepon:</label>
                                    <input type="tel" id="profilePhone" value="${donorData.phone}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                                </div>
                                <div>
                                    <button type="submit" class="bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white px-8 py-3 rounded-lg font-medium transition-all">
                                        Update Profil
                                    </button>
                                </div>
                            </div>
                        </form>
                    `;
                    break;
            }
        }

        function getStatusText(status) {
            switch (status) {
                case 'urgent': return 'Mendesak';
                case 'pending': return 'Menunggu';
                case 'verified': return 'Tervalidasi';
                default: return status;
            }
        }

        async function donate(requestId) {
            const request = donorData.requests.find(r => r.id === requestId);

            const { value: donationAmount } = await Swal.fire({
                title: `Donasi untuk ${request.item}`,
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p><strong>Siswa:</strong> ${request.student}</p>
                        <p><strong>Kebutuhan:</strong> ${request.item}</p>
                        <p><strong>Estimasi biaya:</strong> Rp ${request.amount.toLocaleString()}</p>
                        <p><strong>Dana terkumpul:</strong> Rp ${request.collected.toLocaleString()}</p>
                        <p><strong>Sisa kebutuhan:</strong> Rp ${(request.amount - request.collected).toLocaleString()}</p>
                    </div>
                `,
                input: 'number',
                inputLabel: 'Jumlah donasi (Rp)',
                inputPlaceholder: 'Masukkan jumlah donasi...',
                inputAttributes: {
                    min: 10000,
                    step: 1000
                },
                showCancelButton: true,
                confirmButtonText: 'Donasi Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28A745',
                cancelButtonColor: '#DC3545',
                inputValidator: (value) => {
                    if (!value || value < 10000) {
                        return 'Minimal donasi adalah Rp 10.000'
                    }
                    if (value > (request.amount - request.collected)) {
                        return 'Jumlah donasi melebihi kebutuhan'
                    }
                }
            });

            if (donationAmount) {
                // Get snap token from server
                const response = await fetch('get-snap-token.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: `donation-${request.id}-${Date.now()}`,
                        item_name: request.item,
                        amount: donationAmount,
                        donor_name: donorData.name,
                        donor_email: donorData.email,
                        donor_phone: donorData.phone
                    })
                });

                const result = await response.json();

                if (result.snapToken) {
                    window.snap.pay(result.snapToken, {
                        onSuccess: async function (result) {
                            // Optional: Save result info or show success
                            Swal.fire({
                                title: 'Pembayaran Berhasil!',
                                text: 'Terima kasih atas donasi Anda.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                            // Simulate success handling (update UI)
                            request.collected += parseInt(donationAmount);
                            request.progress = Math.round((request.collected / request.amount) * 100);

                            donorData.donations.push({
                                id: donorData.donations.length + 1,
                                item: request.item,
                                student: request.student,
                                amount: parseInt(donationAmount),
                                date: new Date().toISOString().split('T')[0],
                                status: 'waiting_proof',
                                note: 'Semoga bermanfaat untuk pendidikan!',
                                proofUrl: null
                            });

                            updateStats();
                            showSection('donations');
                        },
                        onPending: function (result) {
                            Swal.fire('Menunggu Pembayaran', 'Silakan selesaikan pembayaran.', 'info');
                        },
                        onError: function (result) {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat memproses pembayaran.', 'error');
                        },
                        onClose: function () {
                            console.log('Payment popup closed');
                        }
                    });
                } else {
                    Swal.fire('Error', 'Gagal mendapatkan token pembayaran.', 'error');
                }
            }


            /*
            if (donationAmount) {
                // Show payment instructions
                await Swal.fire({
                    title: 'Instruksi Pembayaran',
                    html: `
                        <div style="text-align: left;">
                            <h4>Transfer ke rekening siswa:</h4>
                            <p><strong>Bank:</strong> BCA</p>
                            <p><strong>No. Rekening:</strong> 1234567890</p>
                            <p><strong>Atas Nama:</strong> ${request.student}</p>
                            <p><strong>Jumlah:</strong> Rp ${parseInt(donationAmount).toLocaleString()}</p>
                            <br>
                            <p style="color: #666; font-size: 14px;">
                                Setelah transfer, silakan upload bukti pembayaran melalui menu "Donasi Saya"
                            </p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Saya Mengerti',
                    confirmButtonColor: '#A78BFA'
                });

                // Show success message
                await Swal.fire({
                    title: 'Terima Kasih!',
                    text: 'Donasi Anda sangat berarti untuk membantu pendidikan siswa.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28A745'
                });

                // Update the request data
                request.collected += parseInt(donationAmount);
                request.progress = Math.round((request.collected / request.amount) * 100);
                
                // Add to donations history
                donorData.donations.push({
                    id: donorData.donations.length + 1,
                    item: request.item,
                    student: request.student,
                    amount: parseInt(donationAmount),
                    date: new Date().toISOString().split('T')[0],
                    status: 'waiting_proof',
                    note: 'Semoga bermanfaat untuk pendidikan!',
                    proofUrl: null
                });

                // Update stats
                updateStats();
                
                // Refresh the display
                showSection('requests');
            }*/
        }

        async function uploadProof(donationId) {
            const donation = donorData.donations.find(d => d.id === donationId);

            const { value: file } = await Swal.fire({
                title: 'Upload Bukti Transfer',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p><strong>Donasi:</strong> ${donation.item}</p>
                        <p><strong>Jumlah:</strong> Rp ${donation.amount.toLocaleString()}</p>
                        <p><strong>Untuk:</strong> ${donation.student}</p>
                        <br>
                        <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                            <p>📁 Klik untuk memilih file bukti transfer</p>
                            <p style="font-size: 14px; color: #666;">Format: JPG, PNG, PDF (Max: 5MB)</p>
                        </div>
                        <input type="file" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" style="display: none;">
                        <div id="filePreview" style="display: none;" class="file-preview">
                            <p>✓ File terpilih: <span id="fileName"></span></p>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Upload',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28A745',
                cancelButtonColor: '#DC3545',
                didOpen: () => {
                    const fileInput = document.getElementById('fileInput');
                    const filePreview = document.getElementById('filePreview');
                    const fileName = document.getElementById('fileName');

                    fileInput.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            if (file.size > 5 * 1024 * 1024) {
                                Swal.showValidationMessage('Ukuran file tidak boleh lebih dari 5MB');
                                return;
                            }
                            fileName.textContent = file.name;
                            filePreview.style.display = 'block';
                        }
                    });
                },
                preConfirm: () => {
                    const fileInput = document.getElementById('fileInput');
                    if (!fileInput.files[0]) {
                        Swal.showValidationMessage('Silakan pilih file bukti transfer');
                        return false;
                    }
                    return fileInput.files[0];
                }
            });

            if (file) {
                // Show uploading progress
                Swal.fire({
                    title: 'Mengupload Bukti Transfer...',
                    html: '<div style="text-align: center;"><div class="progress-bar"><div class="progress-fill" id="uploadProgress" style="width: 0%"></div></div><p id="uploadText">Memproses file...</p></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        const progressBar = document.getElementById('uploadProgress');
                        const uploadText = document.getElementById('uploadText');
                        let progress = 0;

                        const interval = setInterval(() => {
                            progress += Math.random() * 30;
                            if (progress > 100) progress = 100;

                            progressBar.style.width = progress + '%';

                            if (progress < 30) {
                                uploadText.textContent = 'Memproses file...';
                            } else if (progress < 70) {
                                uploadText.textContent = 'Mengupload ke server...';
                            } else if (progress < 100) {
                                uploadText.textContent = 'Menyelesaikan upload...';
                            } else {
                                uploadText.textContent = 'Upload selesai!';
                                clearInterval(interval);

                                setTimeout(() => {
                                    Swal.close();

                                    // Update donation status
                                    donation.status = 'pending';
                                    donation.proofUrl = URL.createObjectURL(file);

                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Bukti transfer berhasil diupload. Menunggu validasi admin.',
                                        icon: 'success',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#28A745'
                                    });

                                    // Refresh donations view
                                    showSection('donations');
                                }, 1000);
                            }
                        }, 200);
                    }
                });
            }
        }

        function filterRequests() {
            const statusFilter = document.getElementById('filterStatus').value;
            const searchFilter = document.getElementById('searchStudent').value.toLowerCase();

            let filteredRequests = donorData.requests;

            if (statusFilter) {
                filteredRequests = filteredRequests.filter(r => r.status === statusFilter);
            }

            if (searchFilter) {
                filteredRequests = filteredRequests.filter(r =>
                    r.student.toLowerCase().includes(searchFilter)
                );
            }

            const grid = document.getElementById('requestsGrid');
            grid.innerHTML = filteredRequests.map(request => `
                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all hover:transform hover:-translate-y-1">
                    <h4 class="text-xl font-bold mb-3 text-gray-800">${request.item}</h4>
                    <p class="text-gray-600 mb-2">Nama Siswa: <span class="font-medium">${request.student}</span></p>
                    <p class="text-gray-600 mb-2">Estimasi: <span class="font-bold text-blue-600">Rp ${request.amount.toLocaleString()}</span></p>
                    <p class="mb-3">Status: <span class="badge ${request.status}">${getStatusText(request.status)}</span></p>
                    
                    <div class="progress-bar mb-3">
                        <div class="progress-fill" style="width: ${request.progress}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Dana terkumpul: <span class="font-bold text-green-600">Rp ${request.collected.toLocaleString()}</span></p>
                    
                    <button class="w-full donate-button py-3 px-4 rounded-lg font-medium" onclick="donate(${request.id})">
                        Donasi Sekarang
                    </button>
                </div>
            `).join('');
        }

        function updateProfile(event) {
            event.preventDefault();

            const name = document.getElementById('profileName').value;
            const email = document.getElementById('profileEmail').value;
            const phone = document.getElementById('profilePhone').value;

            // Update donor data
            donorData.name = name;
            donorData.email = email;
            donorData.phone = phone;

            // Update welcome text
            document.getElementById('welcomeText').textContent = `Halo, ${name}`;

            Swal.fire({
                title: 'Berhasil!',
                text: 'Profil berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28A745'
            });
        }

        function updateStats() {
            // Update total donation amount
            const totalDonations = donorData.donations.reduce((sum, d) => sum + d.amount, 0);
            document.querySelector('.text-yellow-400').textContent = `Rp ${totalDonations.toLocaleString()}`;

            // Update students helped (verified donations)
            const studentsHelped = new Set(donorData.donations.filter(d => d.status === 'verified').map(d => d.student)).size;
            document.querySelector('.text-blue-400').textContent = studentsHelped;

            // Update active donations
            const activeDonations = donorData.donations.filter(d => d.status !== 'verified').length;
            document.querySelector('.text-green-400').textContent = activeDonations;
        }

        function logout() {
            Swal.fire({
                title: 'Keluar dari Akun?',
                text: 'Anda akan keluar dari dashboard donatur.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#DC3545',
                cancelButtonColor: '#6C757D'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Berhasil Keluar',
                        text: 'Terima kasih telah berkontribusi!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28A745'
                    }).then(() => {
                        // Redirect to login page or home
                        window.location.href = '/login';
                    });
                }
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            showSection('requests');
            updateStats();
        });

        // Sample notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 transform translate-x-full transition-transform duration-300`;

            switch (type) {
                case 'success':
                    notification.classList.add('bg-green-500');
                    break;
                case 'error':
                    notification.classList.add('bg-red-500');
                    break;
                case 'warning':
                    notification.classList.add('bg-yellow-500');
                    break;
                default:
                    notification.classList.add('bg-blue-500');
            }

            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">×</button>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Simulate real-time updates
        setInterval(() => {
            // Randomly update progress for demonstration
            const randomRequest = donorData.requests[Math.floor(Math.random() * donorData.requests.length)];
            if (randomRequest.progress < 100 && Math.random() > 0.95) {
                const increase = Math.floor(Math.random() * 10) + 5;
                randomRequest.collected += increase * 1000;
                randomRequest.progress = Math.min(100, Math.round((randomRequest.collected / randomRequest.amount) * 100));

                // Refresh view if on requests tab
                if (document.getElementById('requests-btn').classList.contains('active')) {
                    showSection('requests');
                }

                showNotification(`Dana untuk ${randomRequest.item} bertambah Rp ${(increase * 1000).toLocaleString()}!`, 'success');
            }
        }, 30000); // Check every 30 seconds
    </script>
</body>

</html>