<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Masuk - OPTIK FOCUS' ?></title>
    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Ionicons CDN -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- CSS Embed for completely standalone aesthetic login layout -->
    <style>
        :root {
            --color-primary: #6366f1;
            --color-primary-hover: #4f46e5;
            --color-dark: #0f172a;
            --color-dark-light: #1e293b;
            --color-white: #ffffff;
            --color-border: #334155;
            --text-muted: #94a3b8;
            --font-sans: 'Outfit', system-ui, -apple-system, sans-serif;
            --radius-lg: 24px;
            --radius-md: 14px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--color-dark);
            color: var(--color-white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Abstract glowing backdrop circles */
        .glow-circle {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
        }

        .glow-1 {
            top: -100px;
            left: -100px;
        }

        .glow-2 {
            bottom: -150px;
            right: -100px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            z-index: 10;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-lg);
            padding: 2.5rem 2.25rem;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            animation: cardSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            width: 54px;
            height: 54px;
            background-color: var(--color-primary);
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
            margin-bottom: 1.25rem;
            animation: pulseLogo 3s infinite ease-in-out;
        }

        .login-logo ion-icon {
            font-size: 1.75rem;
            color: var(--color-white);
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.75px;
            margin-bottom: 0.35rem;
        }

        .login-header p {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Forms styling */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #cbd5e1;
            letter-spacing: 0.25px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper ion-icon {
            position: absolute;
            left: 1.15rem;
            font-size: 1.2rem;
            color: var(--text-muted);
            pointer-events: none;
            transition: var(--transition-smooth);
        }

        .form-control {
            width: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: 0.9rem 1.15rem 0.9rem 2.85rem;
            color: var(--color-white);
            font-family: inherit;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            background-color: rgba(15, 23, 42, 0.8);
        }

        .form-control:focus + ion-icon {
            color: var(--color-primary);
        }

        /* Alerts within login */
        .alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            border: 1px solid transparent;
            animation: fadeIn 0.4s ease forwards;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.25);
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.25);
        }

        .alert ion-icon {
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* Buttons */
        .btn-submit {
            width: 100%;
            background-color: var(--color-primary);
            color: var(--color-white);
            border: none;
            padding: 0.9rem;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25);
            transition: var(--transition-smooth);
            margin-top: 1.75rem;
        }

        .btn-submit:hover {
            background-color: var(--color-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.35);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .demo-credentials {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px dashed rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-md);
            padding: 0.85rem 1rem;
            margin-top: 2rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .demo-credentials code {
            color: #cbd5e1;
            font-weight: 600;
            background-color: rgba(255, 255, 255, 0.05);
            padding: 0.15rem 0.35rem;
            border-radius: 4px;
        }

        /* Animations */
        @keyframes cardSlideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulseLogo {
            0%, 100% { box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35); }
            50% { box-shadow: 0 8px 32px rgba(99, 102, 241, 0.55); }
        }
    </style>
</head>
<body>
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <ion-icon name="glasses-outline"></ion-icon>
                </div>
                <h1>OPTIK FOCUS</h1>
                <p>Pencatatan Rekam Medis Optik Focus</p>
            </div>

            <!-- Dynamic Alerts -->
            <?php if ($error = getFlash('error')): ?>
                <div class="alert alert-danger">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success = getFlash('success')): ?>
                <div class="alert alert-success">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    <span><?= $success ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= baseUrl('login') ?>">
                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus>
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        <ion-icon name="lock-closed-outline"></ion-icon>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    <span>Masuk Ke Sistem</span>
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
