<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Equalizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.min.css">
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

        /* Custom SweetAlert2 styles */
        .swal2-popup {
            background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
            color: var(--light) !important;
            border-radius: 20px !important;
        }

        .swal2-title {
            color: var(--light) !important;
        }

        .swal2-content {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .swal2-confirm {
            background: var(--highlight) !important;
            color: var(--dark) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }

        .swal2-cancel {
            background: rgba(255, 255, 255, 0.2) !important;
            border-radius: 10px !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="px-6 md:px-10 py-5 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="text-2xl font-bold">Equalizer</div>
            <span class="text-sm bg-blue-500 px-3 py-1 rounded-full">Student</span>
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
            <h1 class="text-3xl md:text-4xl font-bold mb-2" id="studentName">Dashboard Pelajar</h1>
            <p class="text-lg opacity-90">Kelola pembelajaran dan bantuan pendidikan Anda</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex flex-wrap gap-4 mb-8">
            <button onclick="showSection('profile')" class="nav-button active" id="profile-btn">
                Profil
            </button>
            <button onclick="showSection('verification')" class="nav-button" id="verification-btn">
                Verifikasi Dokumen
            </button>
            <button onclick="showSection('requests')" class="nav-button" id="requests-btn">
                Permintaan Bantuan
            </button>
            <button onclick="showSection('received')" class="nav-button" id="received-btn">
                Bantuan Diterima
            </button>
            <button onclick="showSection('validation')" class="nav-button" id="validation-btn">
                Upload Bukti
            </button>
            <button onclick="showSection('courses')" class="nav-button" id="courses-btn">
                Kursus Saya
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
        }

        const documentVerifier = new DocumentVerifier();

        // SweetAlert2 Functions
        function showSuccessAlert(title, text, callback = null) {
            Swal.fire({
                icon: 'success',
                title: title,
                text: text,
                confirmButtonText: 'OK',
                backdrop: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        function showErrorAlert(title, text) {
            Swal.fire({
                icon: 'error',
                title: title,
                text: text,
                confirmButtonText: 'OK'
            });
        }

        function showWarningAlert(title, text) {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: text,
                confirmButtonText: 'OK'
            });
        }

        function showInfoAlert(title, text) {
            Swal.fire({
                icon: 'info',
                title: title,
                text: text,
                confirmButtonText: 'OK'
            });
        }

        function showConfirmDialog(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }

        function showLogoutConfirmation() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari dashboard?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Logging out...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Simulate logout process
                    setTimeout(() => {
                        logout();
                    }, 1500);
                }
            });
        }

        function showVerificationSuccess() {
            Swal.fire({
                icon: 'success',
                title: 'Dokumen Terverifikasi!',
                text: 'Selamat! Dokumen Anda telah berhasil diverifikasi. Anda sekarang dapat mengajukan bantuan.',
                confirmButtonText: 'Lihat Dashboard',
                allowOutsideClick: false
            }).then(() => {
                showSection('profile');
            });
        }

        function showDeleteConfirmation(itemName, callback) {
            Swal.fire({
                title: 'Hapus Item?',
                text: `Apakah Anda yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                    showSuccessAlert('Dihapus!', 'Item berhasil dihapus.');
                }
            });
        }

        function showLoadingAlert(title = 'Memproses...', text = 'Mohon tunggu sebentar') {
            Swal.fire({
                title: title,
                text: text,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

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
                                    <div class="text-3xl"></div>
                                </div>
                            </div>
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Bantuan Diterima</h3>
                                        <p class="text-2xl font-bold text-blue-400">${studentData.received.length}</p>
                                    </div>
                                    <div class="text-3xl"></div>
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
                            <form id="profileForm" class="space-y-4">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Nama Lengkap:</label>
                                        <input type="text" value="${studentData.name}" class="input-field">
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

                    // Add event listener for profile form
                    document.getElementById('profileForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        showConfirmDialog(
                            'Update Profil?',
                            'Apakah Anda yakin ingin menyimpan perubahan profil?',
                            () => {
                                showLoadingAlert('Menyimpan Profil...', 'Mohon tunggu sebentar');
                                setTimeout(() => {
                                    Swal.close();
                                    showSuccessAlert('Berhasil!', 'Profil Anda telah berhasil diperbarui.');
                                }, 2000);
                            }
                        );
                    });
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
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- KTP Upload -->
                            <div class="card rounded-xl p-6">
                                <h4 class="text-lg font-bold mb-4 flex items-center">
                                    <span class="mr-2">🆔</span>
                                    Kartu Tanda Penduduk (KTP)
                                </h4>
                                <div class="mb-4">
                                    <input type="file" id="ktpFile" accept="image/*" class="input-field">
                                    <p class="text-xs mt-2 opacity-70">Format: JPG, PNG (Maks. 5MB)</p>
                                </div>
                                <button onclick="uploadDocument('ktp')" class="w-full bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                    Upload & Verifikasi KTP
                                </button>
                                <div id="ktpAnalysis" class="mt-4"></div>
                            </div>

                            <!-- Kartu Keluarga Upload -->
                            <div class="card rounded-xl p-6">
                                <h4 class="text-lg font-bold mb-4 flex items-center">
                                    <span class="mr-2">👨‍👩‍👧‍👦</span>
                                    Kartu Keluarga
                                </h4>
                                <div class="mb-4">
                                    <input type="file" id="kartuKeluargaFile" accept="image/*" class="input-field">
                                    <p class="text-xs mt-2 opacity-70">Format: JPG, PNG (Maks. 5MB)</p>
                                </div>
                                <button onclick="uploadDocument('kartuKeluarga')" class="w-full bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                    Upload & Verifikasi KK
                                </button>
                                <div id="kartuKeluargaAnalysis" class="mt-4"></div>
                            </div>
                        </div>

                        <!-- Verification History -->
                        ${studentData.verificationHistory.length > 0 ? `
                            <div class="card rounded-xl p-6 mt-8">
                                <h4 class="text-xl font-bold mb-4">Riwayat Verifikasi</h4>
                                <div class="space-y-4">
                                    ${studentData.verificationHistory.map(history => `
                                        <div class="border border-gray-600 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h5 class="font-semibold">${history.type}</h5>
                                                <span class="badge ${history.status}">${history.status}</span>
                                            </div>
                                            <p class="text-sm mb-2">Tanggal: ${formatDate(history.date)}</p>
                                            <p class="text-sm mb-2">Skor AI: ${history.aiScore}/100</p>
                                            ${history.reason ? `<p class="text-sm text-red-400">Alasan: ${history.reason}</p>` : ''}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                    `;
                    break;

                case 'requests':
                    content.innerHTML = `
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold">Permintaan Bantuan Saya</h3>
                            ${studentData.verificationStatus === 'verified' ? `
                                <button onclick="openModal()" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                                    + Tambah Permintaan
                                </button>
                            ` : `
                                <div class="text-right">
                                    <button class="bg-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed opacity-50" disabled>
                                        + Tambah Permintaan
                                    </button>
                                    <p class="text-xs mt-1 text-red-400">Verifikasi dokumen terlebih dahulu</p>
                                </div>
                            `}
                        </div>

                        ${studentData.requests.length === 0 ? `
                            <div class="card rounded-xl p-8 text-center">
                                <div class="text-6xl mb-4">📝</div>
                                <h4 class="text-xl font-bold mb-2">Belum Ada Permintaan</h4>
                                <p class="opacity-70 mb-4">Anda belum membuat permintaan bantuan. Mulai dengan menambah permintaan baru.</p>
                                ${studentData.verificationStatus === 'verified' ? `
                                    <button onclick="openModal()" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                                        Tambah Permintaan Pertama
                                    </button>
                                ` : `
                                    <p class="text-red-400 text-sm">Silakan verifikasi dokumen terlebih dahulu</p>
                                `}
                            </div>
                        ` : `
                            <div class="grid gap-6">
                                ${studentData.requests.map(request => `
                                    <div class="card rounded-xl p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="text-xl font-bold">${request.item}</h4>
                                                <p class="opacity-80">${request.detail}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="badge ${request.status}">${getStatusText(request.status)}</span>
                                                <button onclick="deleteRequest(${request.id})" class="text-red-400 hover:text-red-300 p-1">
                                                    🗑️
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="flex justify-between text-sm mb-2">
                                                <span>Progress: ${request.progress}%</span>
                                                <span>Rp ${request.collected.toLocaleString()} / Rp ${request.amount.toLocaleString()}</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: ${request.progress}%"></div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span>Target: Rp ${request.amount.toLocaleString()}</span>
                                            <span>Sisa: Rp ${(request.amount - request.collected).toLocaleString()}</span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `}
                    `;
                    break;

                case 'received':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6">Bantuan yang Diterima</h3>
                        
                        ${studentData.received.length === 0 ? `
                            <div class="card rounded-xl p-8 text-center">
                                <div class="text-6xl mb-4">🎁</div>
                                <h4 class="text-xl font-bold mb-2">Belum Ada Bantuan Diterima</h4>
                                <p class="opacity-70">Bantuan yang Anda terima akan muncul di sini.</p>
                            </div>
                        ` : `
                            <div class="grid gap-6">
                                ${studentData.received.map(item => `
                                    <div class="card rounded-xl p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="text-xl font-bold">${item.item}</h4>
                                                <p class="opacity-80">Dari: ${item.donor}</p>
                                                <p class="text-sm opacity-70">Tanggal: ${formatDate(item.date)}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-green-400">Rp ${item.amount.toLocaleString()}</div>
                                                <span class="badge ${item.status}">${getStatusText(item.status)}</span>
                                            </div>
                                        </div>
                                        ${item.proof ? `
                                            <div class="mt-4">
                                                <p class="text-sm font-semibold mb-2">Bukti Penggunaan:</p>
                                                <div class="bg-gray-700 p-3 rounded-lg text-sm">
                                                    📷 ${item.proof}
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        `}
                    `;
                    break;

                case 'validation':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-4">Upload Bukti Penggunaan</h3>
                        <p class="mb-6 opacity-80">Upload bukti penggunaan dana bantuan yang telah Anda terima.</p>
                        
                        ${studentData.received.filter(item => !item.proof).length === 0 ? `
                            <div class="card rounded-xl p-8 text-center">
                                <div class="text-6xl mb-4">✅</div>
                                <h4 class="text-xl font-bold mb-2">Semua Bukti Sudah Diupload</h4>
                                <p class="opacity-70">Tidak ada bantuan yang memerlukan bukti penggunaan saat ini.</p>
                            </div>
                        ` : `
                            <div class="grid gap-6">
                                ${studentData.received.filter(item => !item.proof).map(item => `
                                    <div class="card rounded-xl p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="text-xl font-bold">${item.item}</h4>
                                                <p class="opacity-80">Bantuan dari: ${item.donor}</p>
                                                <p class="text-lg font-semibold text-green-400">Rp ${item.amount.toLocaleString()}</p>
                                            </div>
                                            <span class="badge pending">Perlu Bukti</span>
                                        </div>
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium mb-2">Upload Bukti Penggunaan:</label>
                                            <input type="file" id="proof-${item.id}" accept="image/*" class="input-field mb-3">
                                            <button onclick="uploadProof(${item.id})" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                                Upload Bukti
                                            </button>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `}
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
                'unverified': 'Belum Diverifikasi',
                'pending': 'Sedang Diproses',
                'verified': 'Terverifikasi',
                'rejected': 'Ditolak'
            };
            return statusMap[status] || status;
        }

        function getVerificationColor(status) {
            const colorMap = {
                'unverified': 'text-red-400',
                'pending': 'text-yellow-400',
                'verified': 'text-green-400',
                'rejected': 'text-red-400'
            };
            return colorMap[status] || 'text-gray-400';
        }

        function getVerificationIcon(status) {
            const iconMap = {
                'unverified': '❌',
                'pending': '⏳',
                'verified': '✅',
                'rejected': '❌'
            };
            return iconMap[status] || '❓';
        }

        function getVerificationDescription(status) {
            const descriptionMap = {
                'unverified': 'Silakan upload dokumen KTP dan Kartu Keluarga untuk memulai proses verifikasi.',
                'pending': 'Dokumen Anda sedang dianalisis oleh sistem AI. Proses ini biasanya memakan waktu beberapa menit.',
                'verified': 'Dokumen Anda telah terverifikasi. Anda dapat mengajukan bantuan sekarang.',
                'rejected': 'Dokumen Anda tidak memenuhi kriteria verifikasi. Silakan upload ulang dengan kualitas yang lebih baik.'
            };
            return descriptionMap[status] || 'Status tidak diketahui.';
        }

        function getStatusText(status) {
            const statusMap = {
                'urgent': 'Mendesak',
                'pending': 'Menunggu',
                'completed': 'Selesai'
            };
            return statusMap[status] || status;
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        // Document upload and verification
        async function uploadDocument(docType) {
            const fileInput = document.getElementById(docType + 'File');
            const file = fileInput.files[0];
            
            if (!file) {
                showWarningAlert('Peringatan', 'Silakan pilih file terlebih dahulu.');
                return;
            }

            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                showErrorAlert('File Terlalu Besar', 'Ukuran file maksimal 5MB.');
                return;
            }

            const analysisDiv = document.getElementById(docType + 'Analysis');
            analysisDiv.innerHTML = `
                <div class="ai-analysis">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="spinner"></div>
                        <span class="font-semibold">🤖 AI sedang menganalisis dokumen...</span>
                    </div>
                    <p class="text-sm opacity-80">Sistem AI sedang memverifikasi keaslian dan kevalidan dokumen Anda.</p>
                </div>
            `;

            try {
                const result = await documentVerifier.analyzeDocument(file, docType);
                
                analysisDiv.innerHTML = `
                    <div class="ai-analysis">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-semibold">🤖 Hasil Analisis AI</span>
                            <span class="badge ${result.isValid ? 'verified' : 'rejected'}">
                                ${result.isValid ? 'Valid' : 'Invalid'}
                            </span>
                        </div>
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span>Confidence Score</span>
                                <span>${result.confidence}%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${result.confidence}%"></div>
                            </div>
                        </div>
                        ${result.issues.length > 0 ? `
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-red-400 mb-1">Issues Found:</p>
                                <ul class="text-sm space-y-1">
                                    ${result.issues.map(issue => `<li class="text-red-400">• ${issue}</li>`).join('')}
                                </ul>
                            </div>
                        ` : ''}
                        <div class="text-xs opacity-70">
                            <p>Analysis Details: ${Object.entries(result.details).map(([key, value]) => `${key}: ${value}`).join(', ')}</p>
                        </div>
                    </div>
                `;

                if (result.isValid) {
                    studentData.documents[docType] = file.name;
                    
                    // Add to verification history
                    studentData.verificationHistory.push({
                        date: new Date().toISOString().split('T')[0],
                        type: docType.toUpperCase(),
                        status: 'verified',
                        reason: null,
                        aiScore: result.confidence
                    });

                    // Check if all required documents are verified
                    const requiredDocs = ['ktp', 'kartuKeluarga'];
                    const verifiedDocs = requiredDocs.filter(doc => studentData.documents[doc]);
                    
                    if (verifiedDocs.length === requiredDocs.length) {
                        studentData.verificationStatus = 'verified';
                        setTimeout(() => {
                            showVerificationSuccess();
                        }, 1000);
                    }
                    
                    showSuccessAlert('Dokumen Valid!', `${docType.toUpperCase()} Anda telah berhasil diverifikasi dengan skor ${result.confidence}%.`);
                } else {
                    studentData.verificationHistory.push({
                        date: new Date().toISOString().split('T')[0],
                        type: docType.toUpperCase(),
                        status: 'rejected',
                        reason: result.issues.join(', '),
                        aiScore: result.confidence
                    });
                    
                    showErrorAlert('Dokumen Invalid', `Dokumen ${docType.toUpperCase()} tidak memenuhi kriteria verifikasi. Silakan upload ulang dengan kualitas yang lebih baik.`);
                }
            } catch (error) {
                analysisDiv.innerHTML = `
                    <div class="ai-analysis">
                        <div class="text-red-400">
                            <span class="font-semibold">❌ Error Analysis</span>
                            <p class="text-sm mt-1">Terjadi kesalahan saat menganalisis dokumen. Silakan coba lagi.</p>
                        </div>
                    </div>
                `;
                showErrorAlert('Error', 'Terjadi kesalahan saat menganalisis dokumen. Silakan coba lagi.');
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

        // Add request form handler
        document.getElementById('addRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const newRequest = {
                id: studentData.requests.length + 1,
                item: document.getElementById('requestType').value === 'lainnya' ? 
                      'Kebutuhan Lainnya' : 
                      document.getElementById('requestType').options[document.getElementById('requestType').selectedIndex].text,
                detail: document.getElementById('requestDetail').value,
                amount: parseInt(document.getElementById('requestAmount').value),
                status: document.getElementById('requestUrgency').value,
                progress: 0,
                collected: 0
            };

            showConfirmDialog(
                'Tambah Permintaan?',
                'Apakah Anda yakin ingin menambah permintaan bantuan ini?',
                () => {
                    studentData.requests.push(newRequest);
                    closeModal();
                    showSuccessAlert('Berhasil!', 'Permintaan bantuan telah ditambahkan.');
                    showSection('requests');
                }
            );
        });

        // Delete request function
        function deleteRequest(id) {
            const request = studentData.requests.find(r => r.id === id);
            if (request) {
                showDeleteConfirmation(
                    request.item,
                    () => {
                        studentData.requests = studentData.requests.filter(r => r.id !== id);
                        showSection('requests');
                    }
                );
            }
        }

        // Upload proof function
        function uploadProof(itemId) {
            const fileInput = document.getElementById(`proof-${itemId}`);
            const file = fileInput.files[0];
            
            if (!file) {
                showWarningAlert('Peringatan', 'Silakan pilih file bukti terlebih dahulu.');
                return;
            }

            showLoadingAlert('Mengupload Bukti...', 'Mohon tunggu sebentar');
            
            setTimeout(() => {
                const item = studentData.received.find(r => r.id === itemId);
                if (item) {
                    item.proof = file.name;
                }
                
                Swal.close();
                showSuccessAlert('Berhasil!', 'Bukti penggunaan telah berhasil diupload.');
                showSection('validation');
            }, 2000);
        }

        // Logout function
        function logout() {
            // Simulate logout process
            setTimeout(() => {
                showSuccessAlert('Logout Berhasil', 'Terima kasih telah menggunakan Equalizer!', () => {
                    window.location.href = 'index.php';
                });
            }, 1000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('welcomeText').textContent = `Welcome, ${studentData.name}!`;
            document.getElementById('studentName').textContent = `Dashboard ${studentData.name}`;
            showSection('profile');
        });

        // Close modal when clicking outside
        document.getElementById('addRequestModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>