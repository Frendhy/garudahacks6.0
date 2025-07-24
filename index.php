<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equalizer - Platform Bantuan Pendidikan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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

        .hero-bg {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            position: relative;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%23ffffff" opacity="0.1"><animate attributeName="opacity" values="0.1;0.3;0.1" dur="3s" repeatCount="indefinite"/></circle><circle cx="80" cy="30" r="1.5" fill="%23ffffff" opacity="0.1"><animate attributeName="opacity" values="0.1;0.4;0.1" dur="2s" repeatCount="indefinite"/></circle><circle cx="60" cy="70" r="1" fill="%23ffffff" opacity="0.1"><animate attributeName="opacity" values="0.1;0.5;0.1" dur="4s" repeatCount="indefinite"/></circle></svg>') repeat;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(180deg);
            }

            100% {
                transform: translateY(0px) rotate(360deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(250, 229, 70, 0.3);
            }

            50% {
                box-shadow: 0 0 30px rgba(250, 229, 70, 0.6);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }

        .animate-glow {
            animation: glow 2s infinite;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--highlight);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
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

        @keyframes shimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
                opacity: 0;
            }
        }

        .hero-image {
            transition: all 0.4s ease;
        }

        .hero-image:hover {
            transform: scale(1.05) rotate(1deg);
        }

        .logo {
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.1);
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        .section-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .section-hidden {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(15px);
            z-index: 10000;
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .modal.show {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 50px;
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

        @media (max-width: 768px) {
            .modal {
                padding: 10px;
                padding-top: 20px;
            }

            .modal.show {
                align-items: flex-start;
                padding-top: 20px;
            }

            .modal-content {
                padding: 20px;
                width: 100%;
                max-width: none;
                border-radius: 15px;
                max-height: calc(100vh - 40px);
                margin: 0;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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

        .typing-animation {
            border-right: 2px solid var(--highlight);
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            50% {
                border-color: var(--highlight);
            }

            51%,
            100% {
                border-color: transparent;
            }
        }

        .gradient-text {
            background: linear-gradient(45deg, var(--highlight), #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--highlight);
            border-radius: 50%;
            opacity: 0.7;
            animation: particleFloat 6s infinite ease-in-out;
        }

        @keyframes particleFloat {

            0%,
            100% {
                transform: translateY(0px) translateX(0px) rotate(0deg);
                opacity: 0;
            }

            10%,
            90% {
                opacity: 1;
            }

            50% {
                transform: translateY(-100px) translateX(50px) rotate(180deg);
            }
        }

        .swal2-popup {
            background: linear-gradient(135deg, var(--primary), var(--secondary)) !important;
            color: white !important;
            border-radius: 20px !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .swal2-title {
            color: white !important;
            font-weight: 600 !important;
        }

        .swal2-html-container {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .swal2-confirm {
            background: var(--highlight) !important;
            color: var(--dark) !important;
            border: none !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            padding: 12px 30px !important;
            font-size: 16px !important;
        }

        .swal2-cancel {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            padding: 12px 30px !important;
            font-size: 16px !important;
        }

        .swal2-icon.swal2-success {
            border-color: var(--highlight) !important;
        }

        .swal2-icon.swal2-success .swal2-success-ring {
            border-color: var(--highlight) !important;
        }

        .swal2-icon.swal2-success .swal2-success-fix,
        .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: var(--highlight) !important;
        }

        .swal2-icon.swal2-error {
            border-color: #ff6b6b !important;
            color: #ff6b6b !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #ffd93d !important;
            color: #ffd93d !important;
        }
    </style>
</head>

<body class="hero-bg">
    <!-- Floating Particles -->
    <div class="particles-container fixed inset-0 pointer-events-none z-0">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 3s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 0.5s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 1.5s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 2.5s;"></div>
    </div>

    <header class="relative z-10 px-6 md:px-10 py-5 flex justify-between items-center animate-fade-in-up">
        <div class="logo text-2xl font-bold cursor-pointer">Equalizer</div>
        <nav class="hidden md:flex space-x-8">
            <a href="#tentang" class="nav-link font-medium hover:text-yellow-300">Tentang</a>
            <a href="#peran" class="nav-link font-medium hover:text-yellow-300">Peran</a>
            <a href="login_form.php"
                class="nav-link font-medium hover:text-yellow-300 bg-transparent border-none text-white cursor-pointer">Login</a>
            <a href="register_form.php"
                class="bg-yellow-400 text-black px-4 rounded-lg font-medium hover:bg-yellow-500 transition-colors">Daftar</a>
        </nav>
        <button class="md:hidden text-white" onclick="toggleMobileMenu()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </header>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-90 z-50 hidden">
        <div class="flex flex-col items-center justify-center h-full space-y-8 text-xl">
            <button class="absolute top-6 right-6 text-white" onclick="toggleMobileMenu()">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <a href="#tentang" class="nav-link font-medium hover:text-yellow-300"
                onclick="toggleMobileMenu()">Tentang</a>
            <a href="#peran" class="nav-link font-medium hover:text-yellow-300" onclick="toggleMobileMenu()">Peran</a>
            <button onclick="openLoginModal(); toggleMobileMenu()"
                class="nav-link font-medium hover:text-yellow-300 bg-transparent border-none text-white">Login</button>
            <button onclick="openModal(); toggleMobileMenu()"
                class="bg-yellow-400 text-black px-6 py-3 rounded-lg font-medium hover:bg-yellow-500 transition-colors">Daftar</button>
        </div>
    </div>

    <section
        class="relative z-10 flex items-center justify-between px-6 md:px-10 py-12 md:py-16 gap-10 flex-wrap min-h-[70vh]">
        <div class="max-w-2xl animate-fade-in-left">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 gradient-text">
                <span id="typingText">Bantu Pendidikan, Ubah Masa Depan</span>
            </h1>
            <p class="text-lg md:text-xl leading-relaxed mb-8 animate-fade-in-up" style="animation-delay: 0.3s;">
                Equalizer adalah platform kolaboratif yang menghubungkan pelajar, donatur, dan relawan untuk menciptakan
                akses pendidikan yang merata di seluruh Indonesia.
            </p>
            <a href="register_form.php"
                class="cta-button px-8 py-4 text-lg font-semibold rounded-xl animate-fade-in-up animate-glow"
                style="animation-delay: 0.6s;">Gabung Sekarang</a>

        </div>
        <div class="animate-fade-in-right floating" style="animation-delay: 0.4s;">
            <img src="image/logo_icon_white.png" alt="Hero Image" class="hero-image max-w-full rounded-3xl">
        </div>
    </section>

    <section id="peran" class="relative z-10 px-6 md:px-10 py-16 section-hidden">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 gradient-text">Peran Anda di Equalizer</h2>
            <p class="text-lg opacity-90">Pilih peran yang sesuai dengan kemampuan dan keinginan Anda</p>
        </div>
        <div class="flex gap-6 flex-wrap justify-center">
            <div class="card rounded-3xl p-8 w-full md:w-80 group cursor-pointer" onclick="selectRole('pelajar')">
                <div class="text-center mb-4">
                    <div
                        class="w-16 h-16 bg-blue-500 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🎓
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-center">Pelajar</h3>
                <p class="text-sm text-gray-200 text-center leading-relaxed">
                    Dapatkan bantuan biaya pendidikan dan bimbingan untuk meraih impianmu.
                </p>
                <div class="mt-6 text-center">
                    <a href="register_form_pelajar.php"
                        class="bg-blue-500 hover:bg-blue-600 px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Daftar Sebagai Pelajar
                    </a>
                </div>
            </div>

            <div class="card rounded-3xl p-8 w-full md:w-80 group cursor-pointer" onclick="selectRole('donatur')">
                <div class="text-center mb-4">
                    <div
                        class="w-16 h-16 bg-yellow-500 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        💝
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-center">Donatur</h3>
                <p class="text-sm text-gray-200 text-center leading-relaxed">
                    Salurkan bantuan secara transparan dan lihat dampaknya secara langsung.
                </p>
                <div class="mt-6 text-center">
                    <a href="register_form_donatur.php"
                        class="bg-yellow-500 hover:bg-yellow-600 px-6 py-2 rounded-lg text-sm font-medium text-black transition-colors">
                        Daftar Sebagai Donatur
                    </a>
                </div>
            </div>

            <div class="card rounded-3xl p-8 w-full md:w-80 group cursor-pointer" onclick="selectRole('relawan')">
                <div class="text-center mb-4">
                    <div
                        class="w-16 h-16 bg-purple-500 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        🤝
                    </div>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-center">Relawan</h3>
                <p class="text-sm text-gray-200 text-center leading-relaxed">
                    Berikan waktumu untuk mengajar, membimbing, atau mendukung sistem kami.
                </p>
                <div class="mt-6 text-center">
                    <a href="register_form_relawan.php"
                        class="bg-purple-500 hover:bg-purple-600 px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Daftar Sebagai Relawan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="relative z-10 px-6 md:px-10 py-16 section-hidden">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-8 gradient-text">Tentang Equalizer</h2>
            <div class="grid md:grid-cols-2 gap-8 items-center mb-16">
                <div class="text-left">
                    <h3 class="text-2xl font-semibold mb-4">Misi Kami</h3>
                    <p class="text-lg leading-relaxed mb-6">
                        Menciptakan ekosistem pendidikan yang adil dan merata di Indonesia melalui teknologi dan
                        kolaborasi komunitas.
                    </p>
                    <h3 class="text-2xl font-semibold mb-4">Visi Kami</h3>
                    <p class="text-lg leading-relaxed">
                        Menjadi platform terdepan yang menghubungkan semua elemen masyarakat untuk mendukung pendidikan
                        berkualitas bagi semua.
                    </p>
                </div>
                <div class="card rounded-3xl p-8">
                    <div class="text-center">
                        <div class="text-4xl mb-4">📚</div>
                        <h4 class="text-xl font-semibold mb-4">Statistik Platform</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Pelajar Terbantu:</span>
                                <span class="font-bold text-yellow-400" id="studentCount">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Donasi:</span>
                                <span class="font-bold text-yellow-400" id="donationCount">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Relawan Aktif:</span>
                                <span class="font-bold text-yellow-400" id="volunteerCount">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="relative z-10 text-center py-6 text-sm opacity-75 bg-black bg-opacity-20 mt-auto">
        &copy; 2025 Equalizer. All rights reserved.
    </footer>


    <script>
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('section-hidden');
                    entry.target.classList.add('section-visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.section-hidden').forEach(el => {
            observer.observe(el);
        });

        // Typing animation
        function typeWriter(text, element, speed = 100) {
            let i = 0;
            element.innerHTML = '';
            element.classList.add('typing-animation');

            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                } else {
                    element.classList.remove('typing-animation');
                }
            }
            type();
        }

        // Counter animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);

            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString();
                }
            }
            updateCounter();
        }

        function switchToLogin() {
            closeModal();
            setTimeout(openLoginModal, 200);
        }

        function switchToRegister() {
            closeLoginModal();
            setTimeout(openModal, 200);
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        function selectRole(role) {
            openModal();
            setTimeout(() => {
                const select = document.getElementById('peranSelect');
                if (select) {
                    select.value = role;
                }
            }, 100);
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Initialize animations when page loads
        window.addEventListener('load', function () {
            // Start typing animation
            setTimeout(() => {
                const typingElement = document.getElementById('typingText');
                const text = typingElement.textContent;
                typeWriter(text, typingElement, 50);
            }, 1000);

            // Animate counters when about section is visible
            const aboutSection = document.getElementById('tentang');
            const aboutObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(document.getElementById('studentCount'), 2847);
                        setTimeout(() => {
                            const donationElement = document.getElementById('donationCount');
                            animateCounter({
                                textContent: '',
                                set textContent(val) {
                                    donationElement.textContent = 'Rp ' + val + ' juta';
                                }
                            }, 425);
                        }, 500);
                        setTimeout(() => {
                            animateCounter(document.getElementById('volunteerCount'), 186);
                        }, 1000);
                        aboutObserver.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            aboutObserver.observe(aboutSection);

            // Show welcome message if user just visited
            if (!localStorage.getItem('welcomeShown')) {
                setTimeout(() => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Selamat Datang di Equalizer!',
                        html: 'Platform kolaboratif untuk menciptakan akses pendidikan yang merata di Indonesia.<br><br>Mari bergabung dan wujudkan perubahan positif! 🌟',
                        confirmButtonText: 'Mari Mulai!',
                        backdrop: true,
                        allowOutsideClick: true
                    });
                    localStorage.setItem('welcomeShown', 'true');
                }, 2000);
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
                closeLoginModal();
                const menu = document.getElementById('mobileMenu');
                if (!menu.classList.contains('hidden')) {
                    toggleMobileMenu();
                }
            }
        });

        // Add parallax effect to hero background
        window.addEventListener('scroll', function () {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.hero-bg');
            const speed = scrolled * 0.5;
            parallax.style.transform = `translateY(${speed}px)`;
        });

        // Add hover effects to cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>

</html>