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
</style>

<body class="modal">
    <form class="space-y-4 modal-content" method="post" enctype="multipart/form-data" action="register_proses.php">
        <div>
            <label class="text-sm font-medium mb-2">Nama Lengkap</label>
            <input type="text" name="name"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm"
                placeholder="Enter your name" required>
        </div>
        <div>
            <label class="text-sm font-medium mb-2">Email</label>
            <input type="email" name="email"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm"
                placeholder="email@example.com" required>
        </div>
        <div>
            <label class="text-sm font-medium mb-2">Nomor Telepon</label>
            <input type="text" name="phone"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm"
                placeholder="08xxxxxxxxxx" required>
        </div>
        <div>
            <label class="text-sm font-medium mb-2">Password</label>
            <input type="text" name="password"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-gray-300 text-sm"
                placeholder="Enter your password" required>
        </div>
        <div>
            <label class="text-sm font-medium mb-2">Peran</label>
            <select name="peran"
                class="w-full p-3 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white text-sm"
                required>
                <option value="relawan" class="text-black">Relawan</option>
            </select>
        </div>
        <button type="submit" class="w-full cta-button py-3 rounded-lg font-semibold text-base mt-6">
            Buat Akun & Lanjutkan
        </button>
        <div class="text-center mt-4">
            <p class="text-sm text-gray-300">Sudah punya akun?
                <a href="login_form.php"
                    class="text-yellow-400 hover:text-yellow-300 underline bg-transparent border-none cursor-pointer">Login
                    di sini</a>
            </p>
        </div>
    </form>
</body>