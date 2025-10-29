<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Warkah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Decorative Background Circles */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        body::before {
            width: 500px;
            height: 500px;
            top: -200px;
            right: -100px;
        }

        body::after {
            width: 300px;
            height: 300px;
            bottom: -100px;
            left: -50px;
        }

        .login-container {
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 1100px;
            width: 95%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            z-index: 1;
            min-height: 600px;
        }

        .left-side {
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .illustration-wrapper {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .illustration-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .right-side {
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header {
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 36px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
            font-size: 16px;
        }

        .alert {
            border-left: 4px solid;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: start;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: #fef2f2;
            border-color: #ef4444;
        }

        .alert-success {
            background: #f0fdf4;
            border-color: #22c55e;
        }

        .alert i {
            margin-top: 4px;
            margin-right: 12px;
            font-size: 18px;
        }

        .alert-error i {
            color: #ef4444;
        }

        .alert-success i {
            color: #22c55e;
        }

        .alert-error .alert-content p {
            color: #991b1b;
        }

        .alert-success .alert-content p {
            color: #166534;
        }

        .alert .alert-content p.title {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .alert .alert-content p.message {
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-input {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 16px;
            color: #374151;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #dc2626;
            font-size: 14px;
            margin-top: 4px;
            margin-left: 4px;
        }

        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #374151;
            cursor: pointer;
            font-size: 14px;
        }

        .checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .submit-btn {
            width: 100%;
            background: #2563eb;
            color: white;
            font-weight: bold;
            padding: 14px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
            transition: all 0.2s ease;
        }

        .submit-btn:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            .left-side {
                display: none;
            }
            .right-side {
                padding: 40px;
            }
        }

        @media (max-width: 640px) {
            .right-side {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- Left Side - Illustration -->
    <div class="left-side">
        <div class="illustration-wrapper">
            <img 
                src="{{ asset('image/Login1.jpg') }}" 
                alt="Welcome Illustration">
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="right-side">
        
        <!-- Header -->
        <div class="header">
            <h1>Login</h1>
            <p>Please login to continue</p>
        </div>

        <!-- Alert Error -->
        @error('email')
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div class="alert-content">
                    <p class="title">Login Gagal</p>
                    <p class="message">{{ $message }}</p>
                </div>
            </div>
        @enderror

        <!-- Alert Success -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <!-- Email Input -->
            <div class="form-group">
                <input 
                    type="email" 
                    name="email"
                    class="form-input @error('email') error @enderror"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required 
                    autofocus>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <input 
                    type="password" 
                    name="password"
                    class="form-input @error('password') error @enderror"
                    placeholder="Password"
                    required>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-footer">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember">
                    <span>Keep Me Logged In</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">
                LOGIN
            </button>
        </form>

    </div>

</div>

<script>
    // Auto-hide success message after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.transition = 'opacity 0.3s ease-out';
                successAlert.style.opacity = '0';
                setTimeout(function() {
                    successAlert.remove();
                }, 300);
            }, 3000);
        }
    });
</script>

</body>
</html>