<?php
session_start();
include('db_connection.php');

// FIXED: Check if user is NOT logged in (changed condition)
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in first.</p>";
    exit;
}

// FIXED: Added check for donor role
if ($_SESSION['peran'] !== 'donatur') {
    echo "<p>Access denied. This page is for donors only.</p>";
    exit;
}

$id = $_SESSION['user_id'];
// FIXED: Use $id instead of undefined $donor_id variable
$donor_id = $id;

// Get donor information with error handling
$queryDonors = "SELECT * FROM donors WHERE id = ?";
$stmtDonors = $conn->prepare($queryDonors);

if (!$stmtDonors) {
    echo "Error preparing donor query: " . $conn->error;
    exit;
}

$stmtDonors->bind_param("i", $id);
$stmtDonors->execute();
$resultDonors = $stmtDonors->get_result();

if (!$resultDonors) {
    echo "Error executing donor query: " . $conn->error;
    exit;
}

$donor_info = $resultDonors->fetch_assoc();

// Check if donor exists
if (!$donor_info) {
    echo "<p>Donor not found. Please log in again.</p>";
    session_destroy();
    exit;
}

// Get donation requests with updated collected amounts
$query = "SELECT dr.*, s.name as student_name,
          COALESCE(SUM(CASE WHEN d.status = 'verified' THEN d.amount ELSE 0 END), 0) as actual_collected
          FROM donation_requests dr 
          JOIN students s ON dr.student_id = s.id 
          LEFT JOIN donations d ON dr.id = d.request_id 
          GROUP BY dr.id
          ORDER BY dr.id DESC";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo "Error preparing requests query: " . $conn->error;
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo "Error executing requests query: " . $conn->error;
    exit;
}

$donation_requests = array();
while ($row = $result->fetch_assoc()) {
    // Update the collected amount with actual verified donations
    $row['collected'] = $row['actual_collected'];
    
    // Calculate progress based on actual collected amount
    $row['progress'] = $row['amount'] > 0 ? min(100, ($row['actual_collected'] / $row['amount']) * 100) : 0;
    
    // Update status based on progress
    if ($row['actual_collected'] >= $row['amount']) {
        $row['status'] = 'completed';
    } elseif ($row['actual_collected'] > 0) {
        $row['status'] = 'pending';
    }
    
    $donation_requests[] = $row;
}

// Get donor's donations - FIXED: Use $id instead of undefined $donor_id
$queryDonations = "SELECT d.*, dr.item, s.name as student_name 
                   FROM donations d 
                   JOIN donation_requests dr ON d.request_id = dr.id 
                   JOIN students s ON dr.student_id = s.id 
                   WHERE d.donor_id = ?
                   ORDER BY d.date DESC";

$stmtDonations = $conn->prepare($queryDonations);

if (!$stmtDonations) {
    echo "Error preparing donations query: " . $conn->error;
    exit;
}

$stmtDonations->bind_param("i", $id);
$stmtDonations->execute();
$resultDonations = $stmtDonations->get_result();

if (!$resultDonations) {
    echo "Error executing donations query: " . $conn->error;
    exit;
}

$donor_donations = array();
while ($rowDonations = $resultDonations->fetch_assoc()) {
    $donor_donations[] = $rowDonations;
}

// Calculate total donations (only verified)
$totalDonations = 0;
foreach ($donor_donations as $donation) {
    if (isset($donation['status']) && $donation['status'] === 'verified') {
        $totalDonations += $donation['amount'];
    }
}

// Calculate students helped (only from verified donations)
$verifiedDonations = array_filter($donor_donations, function($d) {
    return isset($d['status']) && $d['status'] === 'verified';
});
$studentsHelped = count(array_unique(array_column($verifiedDonations, 'student_name')));

// Close prepared statements
$stmtDonors->close();
$stmt->close();
$stmtDonations->close();
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

        .donate-button:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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

        .badge.urgent { background: #dc2626; color: white; }
        .badge.pending { background: #f59e0b; color: white; }
        .badge.waiting_proof { background: var(--highlight); color: var(--dark); }
        .badge.completed, .badge.verified { background: #16a34a; color: white; }

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

        .completed-badge {
            background: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
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
            <span class="hidden md:inline text-sm" id="welcomeText">
                Halo, <?php echo isset($donor_info['name']) ? htmlspecialchars($donor_info['name']) : (isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Guest'); ?>
            </span>
            <button onclick="logout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm transition-colors">
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
                        <p class="text-2xl font-bold text-yellow-400">Rp <?php echo number_format($totalDonations); ?></p>
                    </div>
                    <div class="text-3xl">💰</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Pelajar Terbantu</h3>
                        <p class="text-2xl font-bold text-blue-400"><?php echo $studentsHelped; ?></p>
                    </div>
                    <div class="text-3xl">🎓</div>
                </div>
            </div>
            <div class="card rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Donasi Aktif</h3>
                        <p class="text-2xl font-bold text-green-400"><?php echo count($donor_donations); ?></p>
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
                <button onclick="showSection('requests')" class="tab-button active px-6 py-3 rounded-lg font-medium transition-all" id="requests-btn">
                    Permintaan Bantuan
                </button>
                <button onclick="showSection('donations')" class="tab-button px-6 py-3 rounded-lg font-medium transition-all hover:bg-white hover:bg-opacity-10" id="donations-btn">
                    Donasi Saya
                </button>
                <button onclick="showSection('impact')" class="tab-button px-6 py-3 rounded-lg font-medium transition-all hover:bg-white hover:bg-opacity-10" id="impact-btn">
                    Dampak Donasi
                </button>
            </div>

            <!-- Content Area -->
            <div class="content-card rounded-xl p-8" id="content">
                <!-- Default content: Requests -->
                <h3 class="text-2xl font-bold mb-6 text-gray-800">Permintaan Bantuan Siswa</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="requestsGrid">
                    <?php if (!empty($donation_requests)): ?>
                        <?php foreach ($donation_requests as $request): ?>
                            <?php 
                            $requestAmount = isset($request['amount']) ? $request['amount'] : 0;
                            $requestCollected = isset($request['collected']) ? $request['collected'] : 0;
                            $remainingAmount = $requestAmount - $requestCollected;
                            $isCompleted = $requestCollected >= $requestAmount;
                            ?>
                            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all <?php echo $isCompleted ? 'border-2 border-green-500' : ''; ?>">
                                <?php if ($isCompleted): ?>
                                    <div class="completed-badge mb-3 text-center">✅ Target Tercapai!</div>
                                <?php endif; ?>
                                
                                <h4 class="text-xl font-bold mb-3 text-gray-800">
                                    <?php echo isset($request['item']) ? htmlspecialchars($request['item']) : 'Item tidak tersedia'; ?>
                                </h4>
                                <p class="text-gray-600 mb-2">Nama Siswa: 
                                    <span class="font-medium">
                                        <?php echo isset($request['student_name']) ? htmlspecialchars($request['student_name']) : 'Tidak tersedia'; ?>
                                    </span>
                                </p>
                                <p class="text-gray-600 mb-2">Target: 
                                    <span class="font-bold text-blue-600">Rp <?php echo number_format($requestAmount); ?></span>
                                </p>
                                <p class="mb-3">Status: 
                                    <span class="badge <?php echo isset($request['status']) ? $request['status'] : 'pending'; ?>">
                                        <?php echo isset($request['status']) ? ucfirst($request['status']) : 'Pending'; ?>
                                    </span>
                                </p>
                                
                                <div class="progress-bar mb-3">
                                    <div class="progress-fill" style="width: <?php echo min(100, isset($request['progress']) ? $request['progress'] : 0); ?>%"></div>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-2">Dana terkumpul: 
                                    <span class="font-bold text-green-600">Rp <?php echo number_format($requestCollected); ?></span>
                                </p>
                                
                                <?php if (!$isCompleted): ?>
                                    <p class="text-sm text-gray-600 mb-4">Sisa kebutuhan: 
                                        <span class="font-bold text-red-600">Rp <?php echo number_format($remainingAmount); ?></span>
                                    </p>
                                    <button class="w-full donate-button py-3 px-4 rounded-lg font-medium" 
                                            onclick="donate(<?php echo isset($request['id']) ? $request['id'] : 0; ?>, '<?php echo isset($request['item']) ? htmlspecialchars($request['item']) : ''; ?>', '<?php echo isset($request['student_name']) ? htmlspecialchars($request['student_name']) : ''; ?>', <?php echo $requestAmount; ?>, <?php echo $requestCollected; ?>)">
                                        Donasi Sekarang
                                    </button>
                                <?php else: ?>
                                    <p class="text-sm text-green-600 mb-4 font-medium">🎉 Target donasi sudah tercapai!</p>
                                    <button class="w-full py-3 px-4 rounded-lg font-medium bg-gray-200 text-gray-500 cursor-not-allowed" disabled>
                                        Target Tercapai
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-500">Belum ada permintaan bantuan tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Include Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-luNrPHRSBmDtAsLb"></script>
    
    <!-- Pass PHP data to JavaScript -->
    <script>
        const donorData = {
            id: <?php echo $donor_id; ?>,
            name: '<?php echo isset($donor_info['name']) ? htmlspecialchars($donor_info['name']) : (isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : ''); ?>',
            email: '<?php echo isset($donor_info['email']) ? htmlspecialchars($donor_info['email']) : (isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''); ?>',
            phone: '<?php echo isset($donor_info['phone']) ? htmlspecialchars($donor_info['phone']) : ''; ?>',
            donations: <?php echo json_encode($donor_donations); ?>
        };
        
        const donationRequests = <?php echo json_encode($donation_requests); ?>;
    </script>

    <script>
        function showSection(section) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(b => {
                b.classList.remove('active');
                b.classList.add('hover:bg-white', 'hover:bg-opacity-10');
            });
            document.getElementById(section + '-btn').classList.add('active');
            document.getElementById(section + '-btn').classList.remove('hover:bg-white', 'hover:bg-opacity-10');

            const content = document.getElementById('content');
            
            switch (section) {
                case 'requests':
                    let requestsHtml = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Permintaan Bantuan Siswa</h3><div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">';
                    
                    if (donationRequests && donationRequests.length > 0) {
                        donationRequests.forEach(request => {
                            const remainingAmount = (request.amount || 0) - (request.collected || 0);
                            const isCompleted = (request.collected || 0) >= (request.amount || 0);
                            const progress = Math.min(100, request.progress || 0);
                            
                            requestsHtml += `
                                <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all ${isCompleted ? 'border-2 border-green-500' : ''}">
                                    ${isCompleted ? '<div class="completed-badge mb-3 text-center">✅ Target Tercapai!</div>' : ''}
                                    
                                    <h4 class="text-xl font-bold mb-3 text-gray-800">${request.item || 'Item tidak tersedia'}</h4>
                                    <p class="text-gray-600 mb-2">Nama Siswa: <span class="font-medium">${request.student_name || 'Tidak tersedia'}</span></p>
                                    <p class="text-gray-600 mb-2">Target: <span class="font-bold text-blue-600">Rp ${(request.amount || 0).toLocaleString()}</span></p>
                                    <p class="mb-3">Status: <span class="badge ${request.status || 'pending'}">${getStatusText(request.status || 'pending')}</span></p>
                                    
                                    <div class="progress-bar mb-3">
                                        <div class="progress-fill" style="width: ${progress}%"></div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-2">Dana terkumpul: <span class="font-bold text-green-600">Rp ${(request.collected || 0).toLocaleString()}</span></p>
                                    
                                    ${!isCompleted ? `
                                        <p class="text-sm text-gray-600 mb-4">Sisa kebutuhan: <span class="font-bold text-red-600">Rp ${remainingAmount.toLocaleString()}</span></p>
                                        <button class="w-full donate-button py-3 px-4 rounded-lg font-medium" onclick="donate(${request.id || 0}, '${request.item || ''}', '${request.student_name || ''}', ${request.amount || 0}, ${request.collected || 0})">
                                            Donasi Sekarang
                                        </button>
                                    ` : `
                                        <p class="text-sm text-green-600 mb-4 font-medium">🎉 Target donasi sudah tercapai!</p>
                                        <button class="w-full py-3 px-4 rounded-lg font-medium bg-gray-200 text-gray-500 cursor-not-allowed" disabled>
                                            Target Tercapai
                                        </button>
                                    `}
                                </div>
                            `;
                        });
                    } else {
                        requestsHtml += '<div class="col-span-full text-center py-8"><p class="text-gray-500">Belum ada permintaan bantuan tersedia.</p></div>';
                    }
                    
                    requestsHtml += '</div>';
                    content.innerHTML = requestsHtml;
                    break;

                case 'donations':
                    let donationsHtml = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Donasi Saya</h3><div class="space-y-6">';
                    
                    if (donorData.donations && donorData.donations.length > 0) {
                        donorData.donations.forEach(donation => {
                            donationsHtml += `
                                <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-purple-500">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-xl font-bold mb-2 text-gray-800">${donation.item || 'Item tidak tersedia'}</h4>
                                            <p class="text-gray-600 mb-1">Untuk: <span class="font-medium">${donation.student_name || 'Tidak tersedia'}</span></p>
                                            <p class="text-gray-600 mb-1">Jumlah: <span class="font-bold text-blue-600">Rp ${(donation.amount || 0).toLocaleString()}</span></p>
                                            <p class="text-gray-600 mb-1">Tanggal: ${donation.date || 'Tidak tersedia'}</p>
                                            <p class="text-gray-700 italic">${donation.note || ''}</p>
                                            ${donation.proof_url ? `<p class="mt-2">Bukti Transfer: <a href="${donation.proof_url}" target="_blank" class="text-blue-500 hover:underline">Lihat Bukti</a></p>` : ''}
                                        </div>
                                        <div class="mt-4 md:mt-0 md:ml-6">
                                            ${donation.status === 'waiting_proof' && !donation.proof_url ? `<button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors mb-2" onclick="uploadProof(${donation.id || 0})">Upload Bukti Transfer</button><br>` : ''}
                                            <span class="badge ${donation.status || 'pending'}">${getStatusText(donation.status || 'pending')}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        donationsHtml += '<div class="text-center py-8"><p class="text-gray-500">Belum ada riwayat donasi.</p></div>';
                    }
                    
                    donationsHtml += '</div>';
                    content.innerHTML = donationsHtml;
                    break;

                case 'impact':
                    const verifiedDonations = (donorData.donations || []).filter(d => d.status === 'verified');
                    const totalVerifiedAmount = verifiedDonations.reduce((sum, d) => sum + (d.amount || 0), 0);
                    const uniqueStudents = new Set(verifiedDonations.map(d => d.student_name)).size;
                    
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6 text-gray-800">Dampak Donasi Anda</h3>
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 border-l-4 border-green-500">
                            <h4 class="text-xl font-bold mb-3 text-green-700">Dampak Positif Donasi Anda</h4>
                            <p class="text-gray-700 mb-2">Total Donasi Terverifikasi: <span class="text-green-600 font-bold">Rp ${totalVerifiedAmount.toLocaleString()}</span></p>
                            <p class="text-gray-700 mb-2">Siswa Terbantu: <span class="text-blue-600 font-bold">${uniqueStudents} siswa</span></p>
                            <p class="text-gray-700 mb-2">Status: <span class="text-green-600 font-medium">✓ Membantu Pendidikan</span></p>
                            <blockquote class="bg-white p-4 rounded-lg border-l-4 border-blue-400 italic text-gray-700 mb-3">
                                "Donasi Anda sangat membantu siswa-siswa yang membutuhkan untuk melanjutkan pendidikan mereka."
                            </blockquote>
                        </div>
                        
                        <div class="mt-8 grid md:grid-cols-3 gap-6">
                            <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                                <div class="text-4xl mb-3">💝</div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Total Donasi</h4>
                                <p class="text-2xl font-bold text-blue-600">${verifiedDonations.length}</p>
                                <p class="text-gray-600">Donasi Terverifikasi</p>
                            </div>
                            <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                                <div class="text-4xl mb-3">📈</div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Siswa Terbantu</h4>
                                <p class="text-2xl font-bold text-green-600">${uniqueStudents}</p>
                                <p class="text-gray-600">Siswa</p>
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
            }
        }

        function getStatusText(status) {
            switch (status) {
                case 'urgent': return 'Mendesak';
                case 'pending': return 'Sedang Berjalan';
                case 'verified': return 'Tervalidasi';
                case 'waiting_proof': return 'Menunggu Bukti';
                case 'completed': return 'Selesai';
                default: return status;
            }
        }

        async function donate(requestId, itemName, studentName, totalAmount, collectedAmount) {
            const remainingAmount = totalAmount - collectedAmount;
            
            if (remainingAmount <= 0) {
                Swal.fire('Info', 'Target donasi untuk item ini sudah tercapai.', 'info');
                return;
            }
            
            const { value: donationAmount } = await Swal.fire({
                title: `Donasi untuk ${itemName}`,
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p><strong>Siswa:</strong> ${studentName}</p>
                        <p><strong>Kebutuhan:</strong> ${itemName}</p>
                        <p><strong>Target biaya:</strong> Rp ${totalAmount.toLocaleString()}</p>
                        <p><strong>Dana terkumpul:</strong> Rp ${collectedAmount.toLocaleString()}</p>
                        <p><strong>Sisa kebutuhan:</strong> Rp ${remainingAmount.toLocaleString()}</p>
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
                    if (value > remainingAmount) {
                        return 'Jumlah donasi melebihi kebutuhan'
                    }
                }
            });

            if (donationAmount) {
                try {
                    // Get snap token from server
                    const response = await fetch('get-snap-token.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            request_id: requestId,
                            item_name: itemName,
                            amount: parseInt(donationAmount),
                            donor_name: donorData.name,
                            donor_email: donorData.email,
                            donor_phone: donorData.phone
                        })
                    });

                    const result = await response.json();

                    if (result.snapToken) {
                        window.snap.pay(result.snapToken, {
                            onSuccess: async function (result) {
                                Swal.fire({
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih atas donasi Anda.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Reload page to show updated data
                                    location.reload();
                                });
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
                        Swal.fire('Error', 'Gagal mendapatkan token pembayaran: ' + (result.error || 'Unknown error'), 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
                }
            }
        }

        async function uploadProof(donationId) {
            const { value: file } = await Swal.fire({
                title: 'Upload Bukti Transfer',
                html: `
                    <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                        <div class="text-4xl mb-4">📁</div>
                        <p class="text-lg font-medium mb-2">Klik untuk pilih file</p>
                        <p class="text-sm text-gray-600">Format: JPG, PNG, PDF (Max: 5MB)</p>
                        <input type="file" id="fileInput" accept="image/*,.pdf" style="display: none;" onchange="showFilePreview(this)">
                        <div id="filePreview" style="display: none;" class="file-preview">
                            <p id="fileName" class="font-medium text-green-700"></p>
                            <p class="text-sm text-green-600">File berhasil dipilih</p>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Upload',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28A745',
                preConfirm: () => {
                    const fileInput = document.getElementById('fileInput');
                    if (!fileInput.files[0]) {
                        Swal.showValidationMessage('Silakan pilih file bukti transfer');
                        return false;
                    }
                    if (fileInput.files[0].size > 5 * 1024 * 1024) {
                        Swal.showValidationMessage('Ukuran file tidak boleh lebih dari 5MB');
                        return false;
                    }
                    return fileInput.files[0];
                }
            });

            if (file) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('donationId', donationId);

                // Show uploading progress
                Swal.fire({
                    title: 'Mengupload Bukti Transfer...',
                    html: '<div class="text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                try {
                    const response = await fetch('save-proof.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Bukti transfer berhasil diupload. Menunggu validasi admin.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload to show updated data
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat menyimpan bukti.', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Gagal', 'Terjadi kesalahan saat mengupload file.', 'error');
                }
            }
        }

        // Helper function for file preview
        function showFilePreview(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                document.getElementById('fileName').textContent = fileName;
                document.getElementById('filePreview').style.display = 'block';
            }
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
                        window.location.href = 'logout.php';
                    });
                }
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            showSection('requests');
        });
    </script>
</body>
</html>