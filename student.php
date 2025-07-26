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

        .verification-status.unverified {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid #FFC107;
            color: #FFC107;
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
                this.studentName = studentData.name; // Expected student name
            }

            async analyzeDocument(file, documentType) {
                this.isProcessing = true;

                try {
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

                // Simulate realistic document analysis
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                const validImageExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
                
                // Check if file is an image
                if (!validImageExtensions.includes(fileExtension)) {
                    return {
                        isValid: false,
                        confidence: 0,
                        issues: ['File harus berupa gambar (JPG, PNG, WEBP)'],
                        details: {}
                    };
                }

                // Check file size (simulate checking if image is too small/large)
                if (file.size < 50000) { // Less than 50KB
                    return {
                        isValid: false,
                        confidence: 15,
                        issues: ['Ukuran file terlalu kecil, kemungkinan kualitas gambar rendah'],
                        details: { fileSize: 'too small' }
                    };
                }

                if (file.size > 10000000) { // More than 10MB
                    return {
                        isValid: false,
                        confidence: 20,
                        issues: ['Ukuran file terlalu besar, mohon kompres terlebih dahulu'],
                        details: { fileSize: 'too large' }
                    };
                }

                // Simulate advanced AI document verification
                return this.performDetailedAnalysis(file, documentType);
            }

            performDetailedAnalysis(file, documentType) {
                const fileName = file.name.toLowerCase();
                const issues = [];
                let confidence = 0;
                let isValid = false;
                const details = {};

                switch (documentType) {
                    case 'ktp':
                        return this.analyzeKTP(file, fileName);
                    case 'kartuKeluarga':
                        return this.analyzeKartuKeluarga(file, fileName);
                    case 'sertifikatSekolah':
                        return this.analyzeSertifikat(file, fileName);
                    default:
                        return {
                            isValid: false,
                            confidence: 0,
                            issues: ['Jenis dokumen tidak dikenali'],
                            details: {}
                        };
                }
            }

            analyzeKTP(file, fileName) {
                const issues = [];
                let confidence = 0;
                const details = {};

                // Simulate OCR and image analysis
                const hasKTPInName = fileName.includes('ktp') || fileName.includes('identitas');
                const simulatedNameDetection = this.simulateNameDetection(fileName);
                const simulatedImageQuality = this.simulateImageQuality(file);
                const simulatedDocumentAuthenticity = this.simulateDocumentAuthenticity();

                // Check if filename suggests it's a KTP
                if (hasKTPInName) {
                    confidence += 20;
                    details.filenameMatch = 'filename suggests KTP document';
                } else {
                    issues.push('Nama file tidak mengindikasikan dokumen KTP');
                }

                // Simulate name matching with profile
                if (simulatedNameDetection.nameFound) {
                    if (simulatedNameDetection.nameMatches) {
                        confidence += 40;
                        details.nameVerification = 'nama sesuai dengan profil';
                    } else {
                        confidence = Math.max(0, confidence - 30);
                        issues.push(`Nama pada KTP (${simulatedNameDetection.detectedName}) tidak sesuai dengan nama profil (${this.studentName})`);
                    }
                } else {
                    confidence = Math.max(0, confidence - 25);
                    issues.push('Nama tidak dapat dideteksi pada dokumen');
                }

                // Simulate image quality check
                if (simulatedImageQuality.isGood) {
                    confidence += 20;
                    details.imageQuality = 'kualitas gambar baik';
                } else {
                    issues.push('Kualitas gambar kurang jelas, mohon ambil foto dengan pencahayaan yang lebih baik');
                }

                // Simulate document authenticity
                if (simulatedDocumentAuthenticity.authentic) {
                    confidence += 20;
                    details.documentAuthenticity = 'dokumen terdeteksi asli';
                } else {
                    confidence = Math.max(0, confidence - 40);
                    issues.push('Dokumen terindikasi tidak asli atau telah dimanipulasi');
                }

                return {
                    isValid: confidence >= 70 && issues.length === 0,
                    confidence: Math.min(confidence, 100),
                    issues: issues,
                    details: details
                };
            }

            analyzeKartuKeluarga(file, fileName) {
                const issues = [];
                let confidence = 0;
                const details = {};

                const hasKKInName = fileName.includes('kk') || fileName.includes('keluarga') || fileName.includes('family');
                const simulatedFamilyVerification = this.simulateFamilyMemberVerification();
                const simulatedImageQuality = this.simulateImageQuality(file);

                if (hasKKInName) {
                    confidence += 20;
                    details.filenameMatch = 'filename suggests Kartu Keluarga';
                } else {
                    issues.push('Nama file tidak mengindikasikan Kartu Keluarga');
                }

                // Simulate family member verification
                if (simulatedFamilyVerification.studentFound) {
                    confidence += 50;
                    details.familyVerification = `nama siswa ditemukan dalam daftar anggota keluarga`;
                } else {
                    confidence = Math.max(0, confidence - 40);
                    issues.push(`Nama siswa (${this.studentName}) tidak ditemukan dalam Kartu Keluarga`);
                }

                if (simulatedImageQuality.isGood) {
                    confidence += 20;
                    details.imageQuality = 'kualitas gambar baik';
                } else {
                    issues.push('Kualitas gambar kurang jelas');
                }

                // Additional KK-specific checks
                const hasValidHeader = Math.random() > 0.3;
                if (hasValidHeader) {
                    confidence += 10;
                    details.headerCheck = 'header dokumen valid';
                } else {
                    issues.push('Header Kartu Keluarga tidak terdeteksi atau tidak sesuai format');
                }

                return {
                    isValid: confidence >= 70 && issues.length === 0,
                    confidence: Math.min(confidence, 100),
                    issues: issues,
                    details: details
                };
            }

            analyzeSertifikat(file, fileName) {
                const issues = [];
                let confidence = 0;
                const details = {};

                const hasCertInName = fileName.includes('sertifikat') || fileName.includes('certificate') || fileName.includes('ijazah');
                const simulatedNameDetection = this.simulateNameDetection(fileName);
                const simulatedImageQuality = this.simulateImageQuality(file);

                if (hasCertInName) {
                    confidence += 25;
                    details.filenameMatch = 'filename suggests certificate document';
                } else {
                    issues.push('Nama file tidak mengindikasikan sertifikat atau ijazah');
                }

                if (simulatedNameDetection.nameFound && simulatedNameDetection.nameMatches) {
                    confidence += 40;
                    details.nameVerification = 'nama sesuai dengan profil';
                } else {
                    issues.push('Nama pada sertifikat tidak sesuai dengan profil');
                }

                if (simulatedImageQuality.isGood) {
                    confidence += 20;
                    details.imageQuality = 'kualitas gambar baik';
                } else {
                    issues.push('Kualitas gambar kurang jelas');
                }

                // School verification
                const schoolMatch = Math.random() > 0.4;
                if (schoolMatch) {
                    confidence += 15;
                    details.schoolVerification = 'sekolah terverifikasi';
                } else {
                    issues.push('Nama sekolah pada sertifikat tidak sesuai dengan profil');
                }

                return {
                    isValid: confidence >= 70 && issues.length === 0,
                    confidence: Math.min(confidence, 100),
                    issues: issues,
                    details: details
                };
            }

            simulateNameDetection(fileName) {
                // Simulate more realistic name detection
                const commonNames = ['ahmad', 'budi', 'siti', 'andi', 'dewi', 'rizki', 'putri', 'joko'];
                const studentNameLower = this.studentName.toLowerCase();
                const fileNameLower = fileName.toLowerCase();
                
                // Check if any part of student name is in filename
                const studentNameParts = studentNameLower.split(' ');
                const nameInFilename = studentNameParts.some(part => fileNameLower.includes(part));
                
                if (nameInFilename) {
                    // 80% chance the name matches if it's in filename
                    const nameMatches = Math.random() > 0.2;
                    return {
                        nameFound: true,
                        nameMatches: nameMatches,
                        detectedName: nameMatches ? this.studentName : this.generateRandomName()
                    };
                } else {
                    // Simulate detecting a different name
                    return {
                        nameFound: Math.random() > 0.3,
                        nameMatches: false,
                        detectedName: this.generateRandomName()
                    };
                }
            }

            simulateFamilyMemberVerification() {
                // More realistic family verification
                const random = Math.random();
                
                if (random > 0.6) {
                    return { studentFound: true };
                } else {
                    return { studentFound: false };
                }
            }

            simulateImageQuality(file) {
                // Consider file size as quality indicator
                const goodQuality = file.size > 200000 && file.size < 5000000; // 200KB - 5MB
                return {
                    isGood: goodQuality && Math.random() > 0.3
                };
            }

            simulateDocumentAuthenticity() {
                // 70% chance document is authentic
                return {
                    authentic: Math.random() > 0.3
                };
            }

            generateRandomName() {
                const firstNames = ['Budi', 'Siti', 'Ahmad', 'Dewi', 'Andi', 'Putri', 'Joko', 'Maya'];
                const lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Sari', 'Putra', 'Indra', 'Lestari'];
                
                const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
                const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
                
                return `${firstName} ${lastName}`;
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
                    Swal.fire({
                        title: 'Logging out...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
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

        // Helper functions
        function getVerificationStatusText(status) {
            const statusTexts = {
                'unverified': 'Belum Diverifikasi',
                'pending': 'Sedang Diproses',
                'verified': 'Terverifikasi',
                'rejected': 'Ditolak'
            };
            return statusTexts[status] || 'Unknown';
        }

        function getVerificationColor(status) {
            const colors = {
                'unverified': 'text-yellow-400',
                'pending': 'text-blue-400',
                'verified': 'text-green-400',
                'rejected': 'text-red-400'
            };
            return colors[status] || 'text-gray-400';
        }

        function getVerificationIcon(status) {
            const icons = {
                'unverified': '⏳',
                'pending': '🔄',
                'verified': '✅',
                'rejected': '❌'
            };
            return icons[status] || '?';
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function closeModal() {
            document.getElementById('addRequestModal').classList.remove('show');
            document.getElementById('addRequestForm').reset();
        }

        function openModal() {
            if (studentData.verificationStatus !== 'verified') {
                showWarningAlert('Verifikasi Diperlukan', 'Anda harus memverifikasi dokumen terlebih dahulu sebelum dapat mengajukan bantuan.');
                return;
            }
            document.getElementById('addRequestModal').classList.add('show');
        }

        function logout() {
            window.location.href = 'index.php';
        }

        function showSection(section) {
            document.querySelectorAll('.nav-button').forEach(b => b.classList.remove('active'));
            document.getElementById(section + '-btn').classList.add('active');
            
            const content = document.getElementById('content');
            
            switch(section) {
                case 'profile':
                    content.innerHTML = `
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
                        
                        <div class="grid md:grid-cols-3 gap-6 mb-8">
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Permintaan</h3>
                                        <p class="text-2xl font-bold text-yellow-400">${studentData.requests.length}</p>
                                    </div>
                                    <div class="text-3xl">📝</div>
                                </div>
                            </div>
                            <div class="card rounded-xl p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Bantuan Diterima</h3>
                                        <p class="text-2xl font-bold text-blue-400">${studentData.received.length}</p>
                                    </div>
                                    <div class="text-3xl">🎁</div>
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
                                    <label class="block text-sm font-medium mb-2">Rekening Bank:</label>
                                    <input type="text" value="${studentData.bankAccount}" class="input-field">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Cerita Pribadi:</label>
                                    <textarea class="input-field h-24">${studentData.story}</textarea>
                                </div>
                                <button type="submit" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    `;
                    break;

                case 'verification':
                    content.innerHTML = `
                        <div class="card rounded-xl p-8 mb-6">
                            <h3 class="text-2xl font-bold mb-6">Verifikasi Dokumen</h3>
                            <div class="verification-status ${studentData.verificationStatus} mb-6">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl">${getVerificationIcon(studentData.verificationStatus)}</span>
                                    <div>
                                        <h4 class="font-bold">Status: ${getVerificationStatusText(studentData.verificationStatus)}</h4>
                                        <p class="text-sm mt-1">
                                            ${studentData.verificationStatus === 'unverified' ? 
                                                'Upload dokumen yang diperlukan untuk verifikasi identitas.' :
                                                studentData.verificationStatus === 'pending' ?
                                                'Dokumen sedang dianalisis oleh AI. Proses biasanya memakan waktu 1-5 menit.' :
                                                studentData.verificationStatus === 'verified' ?
                                                'Selamat! Dokumen Anda telah terverifikasi.' :
                                                'Dokumen ditolak. Silakan perbaiki dan upload ulang.'
                                            }
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="border border-gray-600 rounded-lg p-4">
                                    <h4 class="font-semibold mb-3">📄 KTP/Kartu Identitas</h4>
                                    <input type="file" id="ktpFile" accept="image/*" class="input-field mb-3">
                                    <button onclick="uploadDocument('ktp')" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm transition-colors">
                                        Upload KTP
                                    </button>
                                </div>
                                
                                <div class="border border-gray-600 rounded-lg p-4">
                                    <h4 class="font-semibold mb-3">👨‍👩‍👧‍👦 Kartu Keluarga</h4>
                                    <input type="file" id="kkFile" accept="image/*" class="input-field mb-3">
                                    <button onclick="uploadDocument('kartuKeluarga')" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm transition-colors">
                                        Upload Kartu Keluarga
                                    </button>
                                </div>
                                
                                <div class="border border-gray-600 rounded-lg p-4">
                                    <h4 class="font-semibold mb-3">🎓 Sertifikat Sekolah</h4>
                                    <input type="file" id="sertifikatFile" accept="image/*" class="input-field mb-3">
                                    <button onclick="uploadDocument('sertifikatSekolah')" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm transition-colors">
                                        Upload Sertifikat
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        ${studentData.verificationHistory.length > 0 ? `
                            <div class="card rounded-xl p-8">
                                <h3 class="text-xl font-bold mb-4">Riwayat Verifikasi</h3>
                                <div class="space-y-4">
                                    ${studentData.verificationHistory.map(history => `
                                        <div class="border border-gray-600 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-semibold">${history.type}</h4>
                                                <span class="badge ${history.status}">${history.status}</span>
                                            </div>
                                            <p class="text-sm text-gray-300 mb-2">Tanggal: ${formatDate(history.date)}</p>
                                            <p class="text-sm">${history.reason}</p>
                                            <div class="mt-2">
                                                <span class="text-xs text-blue-400">AI Score: ${history.aiScore}%</span>
                                            </div>
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
                            <h3 class="text-2xl font-bold">Permintaan Bantuan</h3>
                            <button onclick="openModal()" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                                + Tambah Permintaan
                            </button>
                        </div>
                        
                        <div class="grid gap-6">
                            ${studentData.requests.map(request => `
                                <div class="card rounded-xl p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-xl font-semibold">${request.item}</h4>
                                            <p class="text-gray-300 mt-1">${request.detail}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="badge ${request.status}">${request.status}</span>
                                            <button onclick="showDeleteConfirmation('${request.item}', () => deleteRequest(${request.id}))" class="text-red-400 hover:text-red-300">
                                                🗑️
                                            </button>
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
                                    
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-sm text-gray-300">Target: </span>
                                            <span class="font-semibold">${formatCurrency(request.amount)}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-300">Terkumpul: </span>
                                            <span class="font-semibold text-green-400">${formatCurrency(request.collected)}</span>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        
                        ${studentData.requests.length === 0 ? `
                            <div class="text-center py-12">
                                <div class="text-6xl mb-4">📝</div>
                                <h3 class="text-xl font-semibold mb-2">Belum Ada Permintaan</h3>
                                <p class="text-gray-300 mb-4">Mulai dengan menambahkan permintaan bantuan pertama Anda</p>
                                <button onclick="openModal()" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                                    Tambah Permintaan
                                </button>
                            </div>
                        ` : ''}
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
                                            <h4 class="text-xl font-semibold">${item.item}</h4>
                                            <p class="text-gray-300 mt-1">Dari: ${item.donor}</p>
                                        </div>
                                        <span class="badge ${item.status}">${item.status}</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-sm text-gray-300">Jumlah: </span>
                                            <span class="font-semibold text-green-400">${formatCurrency(item.amount)}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-300">Tanggal: </span>
                                            <span class="font-semibold">${formatDate(item.date)}</span>
                                        </div>
                                    </div>
                                    
                                    ${item.proof ? `
                                        <div class="mt-4">
                                            <button onclick="viewProof('${item.proof}')" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm transition-colors">
                                                Lihat Bukti
                                            </button>
                                        </div>
                                    ` : ''}
                                </div>
                            `).join('')}
                        </div>
                        
                        ${studentData.received.length === 0 ? `
                            <div class="text-center py-12">
                                <div class="text-6xl mb-4">🎁</div>
                                <h3 class="text-xl font-semibold mb-2">Belum Ada Bantuan</h3>
                                <p class="text-gray-300">Bantuan yang Anda terima akan muncul di sini</p>
                            </div>
                        ` : ''}
                    `;
                    break;

                case 'validation':
                    content.innerHTML = `
                        <h3 class="text-2xl font-bold mb-6">Upload Bukti Penggunaan</h3>
                        
                        <div class="card rounded-xl p-8 mb-6">
                            <h4 class="text-lg font-semibold mb-4">Upload Bukti untuk Bantuan yang Diterima</h4>
                            <p class="text-gray-300 mb-6">Upload foto atau dokumen sebagai bukti bahwa bantuan telah digunakan sesuai tujuan.</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Pilih Bantuan:</label>
                                    <select class="input-field" id="validationItem">
                                        <option value="">Pilih bantuan yang sudah diterima</option>
                                        ${studentData.received.map(item => `
                                            <option value="${item.id}">${item.item} - ${formatCurrency(item.amount)}</option>
                                        `).join('')}
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium mb-2">Upload Bukti:</label>
                                    <input type="file" id="proofFile" accept="image/*,video/*,.pdf" class="input-field">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium mb-2">Keterangan:</label>
                                    <textarea id="proofDescription" class="input-field h-24" placeholder="Jelaskan bagaimana bantuan telah digunakan"></textarea>
                                </div>
                                
                                <button onclick="submitProof()" class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors">
                                    Upload Bukti
                                </button>
                            </div>
                        </div>
                        
                        <div class="card rounded-xl p-8">
                            <h4 class="text-lg font-semibold mb-4">Tips Upload Bukti:</h4>
                            <ul class="list-disc list-inside space-y-2 text-gray-300">
                                <li>Pastikan foto jelas dan tidak blur</li>
                                <li>Sertakan tanggal dan waktu jika memungkinkan</li>
                                <li>Upload nota pembelian jika ada</li>
                                <li>Berikan keterangan yang detail tentang penggunaan bantuan</li>
                            </ul>
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

        // Document upload functions
        async function uploadDocument(type) {
            const fileInput = document.getElementById(`${type === 'kartuKeluarga' ? 'kk' : type}File`);
            const file = fileInput.files[0];
            
            if (!file) {
                showWarningAlert('File Diperlukan', 'Silakan pilih file terlebih dahulu.');
                return;
            }
            
            // Show loading
            showLoadingAlert('Memproses Dokumen', 'AI sedang menganalisis dokumen Anda...');
            
            try {
                // Analyze document with AI
                const analysis = await documentVerifier.analyzeDocument(file, type);
                
                // Close loading
                Swal.close();
                
                // Show analysis result
                showAnalysisResult(type, analysis);
                
                // Update verification status
                if (analysis.isValid) {
                    studentData.documents[type] = file.name;
                    checkAllDocumentsUploaded();
                } else {
                    // Add to verification history
                    studentData.verificationHistory.unshift({
                        date: new Date().toISOString().split('T')[0],
                        type: type.toUpperCase(),
                        status: 'rejected',
                        reason: analysis.issues.join(', '),
                        aiScore: analysis.confidence
                    });
                    studentData.verificationStatus = 'rejected';
                }
                
                // Refresh the verification section
                showSection('verification');
                
            } catch (error) {
                Swal.close();
                showErrorAlert('Error', 'Terjadi kesalahan saat memproses dokumen.');
            }
        }

        function showAnalysisResult(type, analysis) {
            const title = analysis.isValid ? 'Dokumen Valid!' : 'Dokumen Ditolak';
            const icon = analysis.isValid ? 'success' : 'error';
            
            let content = `
                <div class="ai-analysis">
                    <h4 class="font-bold mb-2">🤖 Analisis AI:</h4>
                    <p><strong>Confidence Score:</strong> ${analysis.confidence}%</p>
                    ${analysis.issues.length > 0 ? `
                        <p><strong>Issues:</strong></p>
                        <ul class="list-disc list-inside ml-4">
                            ${analysis.issues.map(issue => `<li>${issue}</li>`).join('')}
                        </ul>
                    ` : ''}
                    ${Object.keys(analysis.details).length > 0 ? `
                        <p><strong>Details:</strong></p>
                        <ul class="list-disc list-inside ml-4">
                            ${Object.entries(analysis.details).map(([key, value]) => `<li>${key}: ${value}</li>`).join('')}
                        </ul>
                    ` : ''}
                </div>
            `;
            
            Swal.fire({
                icon: icon,
                title: title,
                html: content,
                confirmButtonText: 'OK'
            });
        }

        function checkAllDocumentsUploaded() {
            const requiredDocs = ['ktp', 'kartuKeluarga', 'sertifikatSekolah'];
            const uploadedDocs = requiredDocs.filter(doc => studentData.documents[doc] !== null);
            
            if (uploadedDocs.length === requiredDocs.length) {
                studentData.verificationStatus = 'verified';
                showVerificationSuccess();
            }
        }

        // Request management functions
        function deleteRequest(id) {
            studentData.requests = studentData.requests.filter(req => req.id !== id);
            showSection('requests');
        }

        function submitProof() {
            const itemSelect = document.getElementById('validationItem');
            const proofFile = document.getElementById('proofFile');
            const description = document.getElementById('proofDescription');
            
            if (!itemSelect.value || !proofFile.files[0] || !description.value) {
                showWarningAlert('Data Tidak Lengkap', 'Mohon lengkapi semua field yang diperlukan.');
                return;
            }
            
            showSuccessAlert('Bukti Berhasil Diupload', 'Terima kasih! Bukti penggunaan bantuan telah berhasil diupload.', () => {
                // Reset form
                itemSelect.value = '';
                proofFile.value = '';
                description.value = '';
            });
        }

        function viewProof(proofFile) {
            showInfoAlert('Bukti Penggunaan', `Menampilkan bukti: ${proofFile}`);
        }

        // Form submission handlers
        document.addEventListener('DOMContentLoaded', function() {
            // Profile form submission
            document.addEventListener('submit', function(e) {
                if (e.target.id === 'profileForm') {
                    e.preventDefault();
                    showSuccessAlert('Profil Disimpan', 'Perubahan profil berhasil disimpan.');
                }
                
                if (e.target.id === 'addRequestForm') {
                    e.preventDefault();
                    
                    const type = document.getElementById('requestType').value;
                    const detail = document.getElementById('requestDetail').value;
                    const amount = parseInt(document.getElementById('requestAmount').value);
                    const urgency = document.getElementById('requestUrgency').value;
                    
                    // Add new request
                    const newRequest = {
                        id: Date.now(),
                        item: document.getElementById('requestType').options[document.getElementById('requestType').selectedIndex].text,
                        detail: detail,
                        amount: amount,
                        status: urgency,
                        progress: 0,
                        collected: 0
                    };
                    
                    studentData.requests.push(newRequest);
                    closeModal();
                    showSuccessAlert('Permintaan Ditambahkan', 'Permintaan bantuan berhasil ditambahkan.');
                    showSection('requests');
                }
            });
            
            // Initialize the dashboard
            showSection('profile');
            document.getElementById('studentName').textContent = studentData.name;
            document.getElementById('welcomeText').textContent = `Welcome, ${studentData.name}!`;
        });
    </script>
</body>
</html>