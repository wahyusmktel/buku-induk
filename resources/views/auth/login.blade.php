<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SD Muhammadiyah Gisting</title>
    <!-- Modern Font: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0ea5e9;
            --light-blue: #e0f2fe;
            --sky-blue: #38bdf8;
            --accent-yellow: #facc15;
            --light-yellow: #fef08a;
            --dark-blue: #0c4a6e;
            --text-gray: #374151;
            --light-gray: #f3f4f6;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.6);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: var(--text-gray);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
        }

        .login-container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Left Side: Form */
        .login-form-side {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            background: white;
            position: relative;
            z-index: 10;
            box-shadow: 20px 0 50px rgba(0,0,0,0.05);
        }

        .logo-container {
            position: absolute;
            top: 2rem;
            left: 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--dark-blue);
        }

        .logo h2 {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .logo span {
            color: var(--primary-blue);
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary-blue), var(--sky-blue));
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
        }

        .form-content {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-blue);
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        .form-header p {
            font-size: 1.1rem;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--dark-blue);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            font-family: inherit;
            font-size: 1rem;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: white;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: #64748b;
        }

        .forgot-password {
            color: var(--primary-blue);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-blue), var(--sky-blue));
            color: white;
            padding: 1.1rem;
            border: none;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.5);
        }

        .back-to-home {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
        }

        .back-to-home:hover {
            color: var(--primary-blue);
        }

        /* Right Side: Abstract Blobs/Image */
        .login-image-side {
            width: 50%;
            background: linear-gradient(135deg, var(--light-blue) 0%, #f0f9ff 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 1;
            opacity: 0.7;
            animation: moveBlob 20s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
        }

        .blob-1 { top: -10%; left: -10%; width: 40vw; height: 40vw; background: var(--sky-blue); animation-duration: 25s; }
        .blob-2 { bottom: -20%; right: -10%; width: 50vw; height: 50vw; background: var(--accent-yellow); animation-duration: 22s; animation-delay: -5s; }

        @keyframes moveBlob {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(10vw, -10vh) scale(1.1); }
            100% { transform: translate(0, 0) scale(1); }
        }

        .glass-overlay {
            width: 70%;
            height: 70%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 2rem;
            z-index: 2;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .glass-overlay img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1.5rem;
        }

        /* Mobile */
        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
            }
            .login-image-side {
                display: none;
            }
            .login-form-side {
                width: 100%;
                padding: 2rem;
            }
            .logo-container {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 2rem;
            }
        }
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-container {
            background: white;
            border-radius: 1.5rem;
            width: 90%;
            max-width: 450px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(20px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .modal-overlay.active .modal-container {
            transform: translateY(0) scale(1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark-blue);
        }

        .btn-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-close:hover {
            background: #f1f5f9;
            color: var(--dark-blue);
        }
        
        .modal-body p {
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Form Section -->
        <div class="login-form-side">
            <div class="logo-container">
                <a href="{{ url('/') }}" class="logo">
                    @php $logoSekolah = \App\Models\Setting::getValue('sekolah_logo'); @endphp
                    @if($logoSekolah)
                        <img src="{{ \Storage::url($logoSekolah) }}" alt="Logo Sekolah" style="width:40px;height:40px;border-radius:12px;object-fit:contain;">
                    @else
                        <div class="logo-icon">BI</div>
                    @endif
                    <h2>Buku Induk<span>.</span></h2>
                </a>
            </div>

            <div class="form-content">
                <div class="form-header">
                    <h1>Selamat Datang</h1>
                    <p>Silakan masuk dengan akun Anda untuk melanjutkan ke sistem administrasi Buku Induk.</p>
                </div>

                @if ($errors->any())
                    <div style="background-color: #fee2e2; border: 1px solid #fccca7; color: #b91c1c; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.95rem;">
                        <ul style="margin-left: 1.5rem; list-style-type: disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Alamat Email / NIP</label>
                        <input type="text" id="email" class="form-control" name="email" placeholder="contoh@sdmuhgisting.sch.id" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <input type="password" id="password" class="form-control" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember"> Ingat Saya
                        </label>
                        <a href="#" class="forgot-password" onclick="event.preventDefault(); toggleModal(true);">Lupa Kata Sandi?</a>
                    </div>

                    <button type="submit" class="btn-submit">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                        Masuk Sistem
                    </button>
                    
                    <a href="{{ url('/') }}" class="back-to-home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Kembali ke Halaman Utama
                    </a>
                </form>
            </div>
        </div>

        <!-- Image / Visual Section -->
        <div class="login-image-side">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="glass-overlay">
                <img src="{{ asset('images/hero.png') }}" alt="SD Muhammadiyah Gisting illustration">
            </div>
        </div>
    </div>
    <!-- Forgot Password Modal -->
    <div class="modal-overlay" id="forgotPasswordModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Reset Kata Sandi</h3>
                <button class="btn-close" onclick="toggleModal(false)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p>Masukkan alamat email yang terdaftar pada akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Ini fitur demonstrasi. Tautan reset fiktif telah dikirim!'); toggleModal(false);">
                    <div class="form-group">
                        <label for="reset_email">Alamat Email</label>
                        <input type="email" id="reset_email" class="form-control" placeholder="contoh@sdmuhgisting.sch.id" required>
                    </div>
                    <button type="submit" class="btn-submit" style="margin-top: 1rem;">Kirim Tautan Reset</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(show) {
            const modal = document.getElementById('forgotPasswordModal');
            if (show) {
                modal.classList.add('active');
                setTimeout(() => document.getElementById('reset_email').focus(), 100);
            } else {
                modal.classList.remove('active');
            }
        }
    </script>
</body>
</html>
