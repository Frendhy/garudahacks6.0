<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Equalizer</title>
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

        .progress-bar {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, var(--highlight), #ff6b6b);
            height: 8px;
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .nav-button {
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .nav-button.active {
            background: var(--highlight);
            color: var(--dark);
            box-shadow: 0 5px 15px rgba(250, 229, 70, 0.4);
        }

        .nav-button:not(.active) {
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
        }

        .nav-button:not(.active):hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            backdrop-filter: blur(10px);
            z-index: 10000;
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
            padding: 20px;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .modal-content {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 30px;
            border-radius: 20px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge.urgent {
            background: #DC3545;
            color: white;
        }

        .badge.pending {
            background: var(--highlight);
            color: var(--dark);
        }

        .badge.completed {
            background: #28A745;
            color: white;
        }

        .badge.verified {
            background: #17A2B8;
            color: white;
        }

        .badge.unverified {
            background: #FFC107;
            color: var(--dark);
        }

        .badge.rejected {
            background: #DC3545;
            color: white;
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

        .verification-status {
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            font-weight: 500;
        }

        .verification-status.pending {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid #FFC107;
            color: #FFC107;
        }

        .verification-status.verified {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid #28A745;
            color: #28A745;
        }

        .verification-status.rejected {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid #DC3545;
            color: #DC3545;
        }

        .ai-analysis {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid #3B82F6;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
        }

        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid var(--highlight);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .document-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="px-6 md:px-10 py-5 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="text-2xl font-bold">🎓 Equalizer</div>
            <span class="text-sm bg-blue-500 px-3 py-1 rounded-full">Student</span>
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
            <h1 class="text-3xl md:text-4xl font-bold mb-2" id="studentName">Dashboard Pelajar</h1>
            <p class="text-lg opacity-90">Kelola pembelajaran dan bantuan pendidikan Anda</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex flex-wrap gap-4 mb-8">
            <button onclick="showSection('profile')" class="nav-button active" id="profile-btn">
                👤 Profil
            </button>
            <button onclick="showSection('verification')" class="nav-button" id="verification-btn">
                🔍 Verifikasi Dokumen
            </button>
            <button onclick="showSection('requests')" class="nav-button" id="requests-btn">
                📋 Permintaan Bantuan
            </button>
            <button onclick="showSection('received')" class="nav-button" id="received-btn">
                💰 Bantuan Diterima
            </button>
            <button onclick="showSection('validation')" class="nav-button" id="validation-btn">
                📸 Upload Bukti
            </button>
            <button onclick="showSection('courses')" class="nav-button" id="courses-btn">
                📚 Kursus Saya
            </button>
        </div>

        <!-- Content Area -->
        <div id="content" class="min-h-[500px]">
            <!-- Content will be loaded here -->
        </div>
    </main>

    <!-- Add Request Modal -->
    <div id="addRequestModal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Tambah Permintaan Bantuan</h2>
                <button onclick="closeModal()" class="text-white hover:text-yellow-400 text-3xl leading-none">&times;</button>
            </div>
            <form id="addRequestForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Jenis Kebutuhan:</label>
                    <select id="requestType" class="input-field" required>
                        <option value="">Pilih jenis</option>
                        <option value="kuota">Kuota Internet</option>
                        <option value="spp">Biaya SPP</option>
                        <option value="buku">Buku Pelajaran</option>
                        <option value="seragam">Seragam Sekolah</option>
                        <option value="sepatu">Sepatu Sekolah</option>
                        <option value="tas">Tas Sekolah</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Detail Kebutuhan:</label>
                    <textarea id="requestDetail" class="input-field h-24" placeholder="Jelaskan kebutuhan secara detail" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Estimasi Biaya (Rp):</label>
                    <input type="number" id="requestAmount" class="input-field" placeholder="Masukkan estimasi biaya" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Tingkat Urgensi:</label>
                    <select id="requestUrgency" class="input-field" required>
                        <option value="low">Rendah</option>
                        <option value="medium">Sedang</option>
                        <option value="urgent">Mendesak</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-yellow-400 text-black py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                    Tambah Permintaan
                </button>
            </form>
        </div>
    </div>

    <script>
        // Sample data
        let studentData = {
            name: 'Ahmad Rizki',
            age: 15,
            school: 'SMA Negeri 1 Jakarta',
            class: '10 IPA 2',
            bankAccount: '1234567890 (BCA)',
            story: 'Anak dari keluarga kurang mampu yang berprestasi di sekolah. Ayah bekerja sebagai tukang ojek dan ibu sebagai pedagang kecil.',
            verificationStatus: 'unverified', // unverified, pending, verified, rejected
            documents: {
                ktp: null,
                kartuKeluarga: null,
                sertifikatSekolah: null
            },
            verificationHistory: [
                {
                    date: '2025-07-20',
                    type: 'KTP',
                    status: 'rejected',
                    reason: 'Gambar tidak jelas, mohon upload ulang dengan kualitas yang lebih baik',
                    aiScore: 45
                }
            ],
            requests: [
                { id: 1, item: 'Kuota Internet', detail: 'Untuk mengikuti pembelajaran online', amount: 50000, status: 'urgent', progress: 0, collected: 0 },
                { id: 2, item: 'Biaya SPP', detail: 'Pembayaran SPP semester ini', amount: 200000, status: 'pending', progress: 25, collected: 50000 },
                { id: 3, item: 'Sepatu Sekolah', detail: 'Sepatu hitam untuk kegiatan sekolah', amount: 150000, status: 'completed', progress: 100, collected: 150000 }
            ],
            received: [
                {
                    id: 1,
                    item: 'Sepatu Sekolah',
                    donor: 'Budi Santoso',
                    amount: 150000,
                    date: '2025-07-20',
                    status: 'completed',
                    proof: 'sepatu_receipt.jpg'
                }
            ],
            courses: [
                { id: 1, name: 'Matematika Dasar', mentor: 'Pak Budi', progress: 80 },
                { id: 2, name: 'Bahasa Inggris', mentor: 'Bu Sarah', progress: 65 },
                { id: 3, name: 'Fisika', mentor: 'Pak Ahmad', progress: 45 }
            ]
        };

        // AI Document Verification System
        class DocumentVerifier {
            constructor() {
                this.isProcessing = false;
            }

            async analyzeDocument(file, documentType) {
                this.isProcessing = true;

                try {
                    // Simulate AI processing
                    const analysisResult = await this.simulateAIAnalysis(file, documentType);
                    return analysisResult;
                } catch (error) {
                    console.error('Error analyzing document:', error);
                    return {
                        isValid: false,
                        confidence: 0,
                        issues: ['Error processing document'],
                        details: {}
                    };
                } finally {
                    this.isProcessing = false;
                }
            }

            async simulateAIAnalysis(file, documentType) {
    await new Promise(resolve => setTimeout(resolve, 3000));

    const isKartuKeluargaValid = (file) => {
        // Check if file type is an image and file extension matches
        const validExtensions = ['.jpg', '.jpeg', '.png']; 
        const fileExtension = file.name.split('.').pop().toLowerCase();
        return validExtensions.includes(fileExtension);
    };

    const mockResults = {
        // Simulated results for KTP (always valid)
        ktp: {
            isValid: true, // Always valid
            confidence: 100, // Maximum confidence
            issues: [],
            details: {
                hasPhoto: true,
                hasSignature: true,
                textClarity: 'good',
                documentIntegrity: 'intact',
                hologramDetected: true,
                fontConsistency: 'consistent',
                nameMatch: 'name matches'
            }
        },
        // Simulated results for Kartu Keluarga
        kartuKeluarga: {
            isValid: isKartuKeluargaValid(file), // Valid only if it's a valid Kartu Keluarga file
            confidence: isKartuKeluargaValid(file) ? 100 : 0, // 100 if valid, 0 if invalid
            issues: isKartuKeluargaValid(file) ? [] : ['File bukan Kartu Keluarga yang valid'],
            details: isKartuKeluargaValid(file) ? { familyMemberVerified: 'student name found' } : {}
        }
    };

    // Return predefined result for the requested document type
    return mockResults[documentType] || mockResults.ktp;
}
            // Checks for fake KTP by analyzing certain attributes like font or clarity
            checkForFakeKTP(file) {
                // Example logic: check if certain attributes (like font) indicate a fake document
                return Math.random() > 0.5; // In real implementation, you would check image quality and content
            }

            checkForFakeKartuKeluarga(file) {
                // Check for discrepancies in the Kartu Keluarga document like missing family data
                return Math.random() > 0.5; // Simulate a check
            }

            // Check if the font in the document matches standard fonts (for authenticity)
            checkFontConsistency(file) {
                // Simulated logic to check for font consistency (replace with actual analysis)
                return Math.random() > 0.3; // 70% chance for consistency
            }

            // Compare student name in the document to the profile
            checkStudentName(file) {
                // Check if the student's name in the KTP matches the profile (simulated here)
                const studentName = 'Ahmad Rizki'; // Example, replace with actual profile name
                const documentName = 'Mira Setiawan'; // Replace with extracted name from the document
                return studentName === documentName;
            }

            // Generate KTP-specific issues (like clarity and manipulation)
            generateKTPIssues() {
                const issues = [];
                if (Math.random() > 0.3) issues.push('Gambar buram atau tidak jelas');
                if (Math.random() > 0.4) issues.push('Kemungkinan manipulasi digital terdeteksi');
                if (Math.random() > 0.5) issues.push('Hologram tidak terdeteksi');
                return issues;
            }

            // Generate Kartu Keluarga-specific issues (like data inconsistency)
            generateKKIssues() {
                const issues = [];
                if (Math.random() > 0.3) issues.push('Header dokumen tidak sesuai format resmi');
                if (Math.random() > 0.4) issues.push('Kemungkinan data telah dimanipulasi');
                return issues;
            }

            checkFamilyMemberExistence() {
                // Logic to check if the student's name exists in the Kartu Keluarga's family list
                const studentName = 'Ahmad Rizki'; // Replace with actual student's name from profile
                const familyMembers = ['Budi Santoso', 'Siti Aisyah', 'Ahmad Rizki', 'Joko Widodo']; // Example, replace with actual data from Kartu Keluarga
                return familyMembers.includes(studentName);
            }
        }

        const documentVerifier = new DocumentVerifier();

        // Load user data on page load
        document.addEventListener('DOMContentLoaded', function() {
            const userData = JSON.parse(localStorage.getItem('userData') || '{}');
            const isLoggedIn = localStorage.getItem('isLoggedIn');
            
            if (!isLoggedIn || userData.peran !== 'pelajar') {
                alert('Anda harus login sebagai pelajar untuk mengakses halaman ini.');
                window.location.href = 'index.php';
                return;
            }
            
            // Update student data with logged in user info
            if (userData.nama) {
                studentData.name = userData.nama;
                document.getElementById('welcomeText').textContent = `Halo, ${userData.nama}!`;
                document.getElementById('studentName').textContent = `Dashboard ${userData.nama}`;
            }
            
            // Initialize with profile section
            showSection('profile');
        });

        function showSection(section) {
            // Update active button
            document.querySelectorAll('.nav-button').forEach(b => b.classList.remove('active'));
            document.getElementById(section + '-btn').classList.add('active');
            
            const content = document.getElementById('content');
            
            switch(section) {
                case 'profile':
                    content.innerHTML = `
                        <!-- Verification Alert -->
                        ${studentData.verificationStatus !== 'verified' ? ` 
                            <div class="verification-status ${studentData.verificationStatus} mb-6">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl">⚠️</span>
                                    <div>
                                        <h4 class="font-bold">Status Verifikasi: ${getVerificationStatusText(studentData.verificationStatus)}</h4>
                                        <p class="text-sm mt-1">
                                            ${studentData.verificationStatus === 'unverified' ? 
                                                'Untuk dapat mengajukan bantuan, silakan verifikasi dokumen Anda terlebih dahulu.' :
                                                studentData.verificationStatus === 'pending' ?
                                                'Dokumen Anda sedang dalam proses verifikasi AI. Harap tunggu...' :
                                                'Dokumen Anda ditolak. Silakan upload ulang dengan kualitas yang lebih baik.' 
                                            }
                                        </p>
                                        <button onclick="showSection('verification')" class="mt-2 bg-yellow-400 text-black px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-500 transition-colors">
                                            ${studentData.verificationStatus === 'unverified' ? 'Verifikasi Sekarang' : 'Lihat Status Verifikasi'}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                        
                        <!-- Stats Cards -->
                        <div class="grid md:grid-cols-3 gap-6 mb-8">
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Permintaan</h3>
                                        <p class="text-2xl font-bold text-yellow-400">${studentData.requests.length}</p>
                                    </div>
                                    <div class="text-3xl">📋</div>
                                </div>
                            </div>
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Bantuan Diterima</h3>
                                        <p class="text-2xl font-bold text-blue-400">${studentData.received.length}</p>
                                    </div>
                                    <div class="text-3xl">💰</div>
                                </div>
                            </div>
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Status Verifikasi</h3>
                                        <p class="text-lg font-bold ${getVerificationColor(studentData.verificationStatus)}">${getVerificationStatusText(studentData.verificationStatus)}</p>
                                    </div>
                                    <div class="text-3xl">${getVerificationIcon(studentData.verificationStatus)}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Form -->
                        <div class="card rounded-xl p-8">
                            <h3 class="text-2xl font-bold mb-6">Profil Saya</h3>
                            <form class="space-y-4">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Nama Lengkap:</label>
                                        <input type="text" value="${studentData.name}" class="input-field" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Umur:</label>
                                        <input type="number" value="${studentData.age}" class="input-field">
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Sekolah:</label>
                                        <input type="text" value="${studentData.school}" class="input-field">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Kelas:</label>
                                        <input type="text" value="${studentData.class}" class="input-field">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Nomor Rekening:</label>
                                    <input type="text" value="${studentData.bankAccount}" class="input-field">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Cerita Singkat:</label>
                                    <textarea rows="4" class="input-field">${studentData.story}</textarea>
                                </div>
                                <button type="submit" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                                    Update Profil
                                </button>
                            </form>
                        </div>
                    `;
                    break;
                case 'verification':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-4">Verifikasi Dokumen dengan AI</h3>
                        <p class="mb-6 opacity-80">Upload dokumen identitas Anda untuk diverifikasi menggunakan sistem AI kami. Sistem akan menganalisis keaslian dokumen secara otomatis.</p>
                        
                        <!-- Current Status -->
                        <div class="verification-status ${studentData.verificationStatus} mb-8">
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl">${getVerificationIcon(studentData.verificationStatus)}</span>
                                <div>
                                    <h4 class="text-xl font-bold">Status: ${getVerificationStatusText(studentData.verificationStatus)}</h4>
                                    <p class="text-sm mt-1">
                                        ${getVerificationDescription(studentData.verificationStatus)}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Document Upload Forms -->
                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <!-- KTP Upload -->
                            <div class="card rounded-xl p-6">
                                <h4 class="text-lg font-bold mb-4">📄 Upload KTP</h4>
                                <div class="space-y-4">
                                    <input type="file" id="ktpFile" accept="image/*" class="input-field" onchange="previewDocument(this, 'ktp')">
                                    <div id="ktpPreview"></div>
                                    <button onclick="verifyDocument('ktp')" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors">
                                        🤖 Verifikasi dengan AI
                                    </button>
                                    <div id="ktpAnalysis"></div>
                                </div>
                            </div>

                            <!-- Kartu Keluarga Upload -->
                            <div class="card rounded-xl p-6">
                                <h4 class="text-lg font-bold mb-4">👨‍👩‍👧‍👦 Upload Kartu Keluarga</h4>
                                <div class="space-y-4">
                                    <input type="file" id="kkFile" accept="image/*" class="input-field" onchange="previewDocument(this, 'kartuKeluarga')">
                                    <div id="kkPreview"></div>
                                    <button onclick="verifyDocument('kartuKeluarga')" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition-colors">
                                        🤖 Verifikasi dengan AI
                                    </button>
                                    <div id="kkAnalysis"></div>
                                </div>
                            </div>
                        </div>

                       <!-- Verification History -->
                        <div class="card rounded-xl p-6">
                            <h4 class="text-lg font-bold mb-4">📋 Riwayat Verifikasi</h4>
                            ${studentData.verificationHistory.length > 0 ? 
                                studentData.verificationHistory.map(item => `

                                    <div class="border-b border-gray-600 pb-4 mb-4 last:border-b-0 last:mb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h5 class="font-semibold">${item.type}</h5>
                                                <p class="text-sm opacity-80">${new Date(item.date).toLocaleDateString('id-ID')}</p>
                                                <span class="badge ${item.status}">${item.status.toUpperCase()}</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm">AI Score: ${item.aiScore}/100</p>
                                                <div class="progress-bar w-20 mt-1">
                                                    <div class="progress-fill" style="width: ${item.aiScore}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        ${item.reason ? `<p class="text-sm mt-2 text-red-300">Alasan: ${item.reason}</p>` : ''}
                                    </div>
                                `).join('') : 
                                '<p class="text-center text-gray-400">Belum ada riwayat verifikasi</p>' 
                            }
                        </div>
                    `;
                    break;
                    case 'requests':
                    content.innerHTML = `
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold">Permintaan Bantuan Saya</h3>
                            ${studentData.verificationStatus === 'verified' ? 
                                '<button onclick="openModal()" class="bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-semibold transition-colors">+ Tambah Permintaan</button>' :
                                '<div class="text-sm text-yellow-400">⚠️ Verifikasi dokumen terlebih dahulu untuk menambah permintaan</div>'
                            }
                        </div>
                        
                        <div class="grid gap-6">
                            ${studentData.requests.map(request => `
                                <div class="card rounded-xl p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-xl font-bold">${request.item}</h4>
                                            <p class="text-sm opacity-80 mt-1">${request.detail}</p>
                                        </div>
                                        <span class="badge ${request.status}">${request.status}</span>
                                    </div>
                                    
                                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-sm opacity-80">Target Biaya:</p>
                                            <p class="text-lg font-bold">Rp ${request.amount.toLocaleString('id-ID')}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm opacity-80">Terkumpul:</p>
                                            <p class="text-lg font-bold text-green-400">Rp ${request.collected.toLocaleString('id-ID')}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span>Progress</span>
                                            <span>${request.progress}%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: ${request.progress}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm transition-colors">
                                            Lihat Detail
                                        </button>
                                        ${request.status === 'completed' ? 
                                            '<button class="bg-purple-500 hover:bg-purple-600 px-4 py-2 rounded-lg text-sm transition-colors">Upload Bukti</button>' : ''
                                        }
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    break;
                case 'received':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6">Bantuan yang Diterima</h3>
                        
                        <div class="grid gap-6">
                            ${studentData.received.map(item => `
                                <div class="card rounded-xl p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-xl font-bold">${item.item}</h4>
                                            <p class="text-sm opacity-80">Dari: ${item.donor}</p>
                                            <p class="text-sm opacity-80">Tanggal: ${new Date(item.date).toLocaleDateString('id-ID')}</p>
                                        </div>
                                        <span class="badge ${item.status}">${item.status}</span>
                                    </div>
                                    
                                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-sm opacity-80">Jumlah Bantuan:</p>
                                            <p class="text-lg font-bold text-green-400">Rp ${item.amount.toLocaleString('id-ID')}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm opacity-80">Status:</p>
                                            <p class="text-lg font-bold">${item.status === 'completed' ? 'Selesai' : 'Dalam Proses'}</p>
                                        </div>
                                    </div>
                                    
                                    ${item.proof ? `
                                        <div class="mb-4">
                                            <p class="text-sm opacity-80 mb-2">Bukti Penggunaan:</p>
                                            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCdABmX/9k=" alt="Bukti" class="document-preview">
                                        </div>
                                    ` : ''}
                                    
                                    <div class="flex space-x-2">
                                        <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm transition-colors">
                                            Hubungi Donor
                                        </button>
                                        <button class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg text-sm transition-colors">
                                            Kirim Terima Kasih
                                        </button>
                                    </div>
                                </div>
                            `).join('')}
                            
                            ${studentData.received.length === 0 ? 
                                '<div class="card rounded-xl p-8 text-center"><p class="text-gray-400">Belum ada bantuan yang diterima</p></div>' : ''
                            }
                        </div>
                    `;
                    break;
                    case 'validation':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6">Upload Bukti Penggunaan Bantuan</h3>
                        <p class="mb-6 opacity-80">Upload foto atau dokumen sebagai bukti bahwa bantuan telah digunakan sesuai tujuan.</p>
                        
                        <div class="grid gap-6">
                            ${studentData.received.filter(item => item.status === 'completed' && !item.proof).map(item => `
                                <div class="card rounded-xl p-6">
                                    <h4 class="text-xl font-bold mb-4">🎯 ${item.item}</h4>
                                    <p class="text-sm opacity-80 mb-4">Bantuan dari: ${item.donor} - Rp ${item.amount.toLocaleString('id-ID')}</p>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Upload Bukti (Foto/Dokumen):</label>
                                            <input type="file" accept="image/*,application/pdf" class="input-field" onchange="previewProof(this, ${item.id})">
                                        </div>
                                        
                                        <div id="proof-preview-${item.id}"></div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium mb-2">Keterangan Penggunaan:</label>
                                            <textarea class="input-field h-24" placeholder="Jelaskan bagaimana bantuan ini digunakan..."></textarea>
                                        </div>
                                        
                                        <button onclick="submitProof(${item.id})" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition-colors">
                                            📤 Submit Bukti
                                        </button>
                                    </div>
                                </div>
                            `).join('')}
                            
                            ${studentData.received.filter(item => item.status === 'completed' && !item.proof).length === 0 ? 
                                '<div class="card rounded-xl p-8 text-center"><p class="text-gray-400">Tidak ada bantuan yang perlu divalidasi</p></div>' : ''
                            }
                        </div>
                    `;
                    break;
                    case 'courses':
                    content.innerHTML = `
  <div class="mb-6">
    <h3 class="text-2xl font-bold mb-2">Belajar Bersama Volunteer</h3>
    <p class="opacity-80">Temukan volunteer yang siap membantu Anda belajar secara gratis sesuai dengan bidang keahliannya.</p>
  </div>

  <div class="grid md:grid-cols-2 gap-6">
    <!-- Volunteer 1 -->
    <div class="card rounded-xl p-6 bg-white bg-opacity-10 backdrop-blur-lg border border-white border-opacity-20 shadow-lg text-white">
      <div class="mb-4">
        <h4 class="text-xl font-bold mb-1">Alya Putri</h4>
        <p class="text-sm opacity-80">Mahasiswa Universitas Indonesia</p>
        <p class="text-sm opacity-80">Jurusan: Pendidikan Matematika</p>
        <p class="text-sm opacity-80 mb-2">Mengajar: Matematika Dasar</p>
      </div>
      <div class="flex justify-end">
        <a href="https://wa.me/6281234567890" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
          Hubungi via WhatsApp
        </a>
      </div>
    </div>

    <!-- Volunteer 2 -->
    <div class="card rounded-xl p-6 bg-white bg-opacity-10 backdrop-blur-lg border border-white border-opacity-20 shadow-lg text-white">
      <div class="mb-4">
        <h4 class="text-xl font-bold mb-1">Raka Pratama</h4>
        <p class="text-sm opacity-80">Mahasiswa ITB</p>
        <p class="text-sm opacity-80">Jurusan: Teknik Informatika</p>
        <p class="text-sm opacity-80 mb-2">Mengajar: Coding Dasar & Logika Pemrograman</p>
      </div>
      <div class="flex justify-end">
        <a href="https://wa.me/6289876543210" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
          Hubungi via WhatsApp
        </a>
      </div>
    </div>

    <!-- Volunteer 3 -->
    <div class="card rounded-xl p-6 bg-white bg-opacity-10 backdrop-blur-lg border border-white border-opacity-20 shadow-lg text-white">
      <div class="mb-4">
        <h4 class="text-xl font-bold mb-1">Citra Lestari</h4>
        <p class="text-sm opacity-80">Alumni UNJ</p>
        <p class="text-sm opacity-80">Jurusan: Pendidikan Bahasa Inggris</p>
        <p class="text-sm opacity-80 mb-2">Mengajar: Bahasa Inggris Dasar</p>
      </div>
      <div class="flex justify-end">
        <a href="https://wa.me/6281122233344" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
          Hubungi via WhatsApp
        </a>
      </div>
    </div>
  </div>

  <div class="mt-10 bg-yellow-100 bg-opacity-10 border-l-4 border-yellow-400 text-yellow-200 p-4 rounded-lg text-sm">
    <strong>Catatan untuk Orang Tua:</strong> Harap dampingi anak Anda saat melakukan sesi belajar bersama volunteer demi kenyamanan dan keamanan bersama.
  </div>
`;

                    break;
            }
        }

        // Helper functions
        function getVerificationStatusText(status) {
            const statusMap = {
                unverified: 'Belum Diverifikasi',
                pending: 'Dalam Proses',
                verified: 'Terverifikasi',
                rejected: 'Ditolak'
            };
            return statusMap[status] || status;
        }

        function getVerificationColor(status) {
            const colorMap = {
                unverified: 'text-yellow-400',
                pending: 'text-blue-400',
                verified: 'text-green-400',
                rejected: 'text-red-400'
            };
            return colorMap[status] || 'text-gray-400';
        }

        function getVerificationIcon(status) {
            const iconMap = {
                unverified: '⚠️',
                pending: '⏳',
                verified: '✅',
                rejected: '❌'
            };
            return iconMap[status] || '❓';
        }

        function getVerificationDescription(status) {
            const descMap = {
                unverified: 'Silakan upload dokumen identitas Anda untuk memulai proses verifikasi.',
                pending: 'Dokumen Anda sedang dianalisis oleh sistem AI. Proses ini biasanya memakan waktu 2-5 menit.',
                verified: 'Selamat! Dokumen Anda telah berhasil diverifikasi. Anda sekarang dapat mengajukan bantuan.',
                rejected: 'Dokumen Anda tidak dapat diverifikasi. Silakan periksa kualitas gambar dan upload ulang.'
            };
            return descMap[status] || 'Status tidak dikenal.';
        }

        // Document verification functions
        function previewDocument(input, type) {
            const file = input.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.getElementById(type === 'ktp' ? 'ktpPreview' : 
                                                                type === 'kartuKeluarga' ? 'kkPreview' : 'certificatePreview');
                previewContainer.innerHTML = `
                    <div class="mt-4">
                        <p class="text-sm font-medium mb-2">Preview:</p>
                        <img src="${e.target.result}" alt="Document preview" class="document-preview border border-gray-300 rounded-lg">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }

        async function verifyDocument(type) {
            const fileInput = document.getElementById(type === 'ktp' ? 'ktpFile' : 'kkFile');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('Silakan pilih file terlebih dahulu!');
                return;
            }

            const analysisContainer = document.getElementById(type === 'ktp' ? 'ktpAnalysis' : 'kkAnalysis');
            
            // Show loading
            analysisContainer.innerHTML = `
                <div class="ai-analysis">
                    <div class="flex items-center space-x-3">
                        <div class="spinner"></div>
                        <div>
                            <h5 class="font-bold">🤖 AI sedang menganalisis dokumen...</h5>
                            <p class="text-sm mt-1">Mohon tunggu, proses ini memakan waktu 2-5 menit</p>
                        </div>
                    </div>
                </div>
            `;

            try {
                const result = await documentVerifier.analyzeDocument(file, type);

                analysisContainer.innerHTML = `
                    <div class="ai-analysis">
                        <h5 class="font-bold mb-3">🤖 Hasil Analisis AI</h5>

                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm opacity-80">Status Verifikasi:</p>
                                <p class="font-bold ${result.isValid ? 'text-green-400' : 'text-red-400'}">
                                    ${result.isValid ? '✅ Valid' : '❌ Tidak Valid'}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm opacity-80">Confidence Score:</p>
                                <p class="font-bold">${result.confidence}/100</p>
                                <div class="progress-bar mt-1">
                                    <div class="progress-fill" style="width: ${result.confidence}%"></div>
                                </div>
                            </div>
                        </div>

                        ${result.issues.length > 0 ? `
                            <div class="mb-4">
                                <p class="text-sm font-medium text-red-400 mb-2">Issues Detected:</p>
                                <ul class="text-sm space-y-1">
                                    ${result.issues.map(issue => `<li class="flex items-start space-x-2"><span class="text-red-400">•</span><span>${issue}</span></li>`).join('')}
                                </ul>
                            </div>
                        ` : ''}

                        <div class="mb-4">
                            <p class="text-sm font-medium text-red-400">Nama pelajar: ${result.details.nameMatch}</p>
                        </div>
                    </div>
                `;

                // Update verification history
                studentData.verificationHistory.unshift({
                    date: new Date().toISOString().split('T')[0],
                    type: type.toUpperCase(),
                    status: result.isValid ? 'verified' : 'rejected',
                    reason: result.issues.length > 0 ? result.issues.join(', ') : null,
                    aiScore: result.confidence
                });

                // Update overall verification status
                if (result.isValid && result.confidence > 80) {
                    studentData.verificationStatus = 'verified';
                    setTimeout(() => {
                        alert('🎉 Dokumen berhasil diverifikasi! Anda sekarang dapat mengajukan bantuan.');
                        showSection('profile'); // Refresh to show new status
                    }, 1000);
                } else {
                    studentData.verificationStatus = 'rejected';
                }

            } catch (error) {
                analysisContainer.innerHTML = `
                    <div class="ai-analysis border-red-500">
                        <h5 class="font-bold text-red-400 mb-2">❌ Error Verifikasi</h5>
                        <p class="text-sm">Terjadi kesalahan saat memproses dokumen. Silakan coba lagi.</p>
                    </div>
                `;
            }
        }

        // Modal functions
        function openModal() {
            document.getElementById('addRequestModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('addRequestModal').classList.remove('show');
            document.getElementById('addRequestForm').reset();
        }

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addRequestForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const newRequest = {
                        id: studentData.requests.length + 1,
                        item: document.getElementById('requestType').value,
                        detail: document.getElementById('requestDetail').value,
                        amount: parseInt(document.getElementById('requestAmount').value),
                        status: document.getElementById('requestUrgency').value,
                        progress: 0,
                        collected: 0
                    };
                    
                    studentData.requests.push(newRequest);
                    closeModal();
                    showSection('requests');
                    alert('Permintaan bantuan berhasil ditambahkan!');
                });
            }
        });

        function logout() {
            localStorage.removeItem('isLoggedIn');
            localStorage.removeItem('userData');
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>