<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Warkah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
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
        }

        .left-side {
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .illustration-wrapper {
            text-align: center;
            position: relative;
        }

        .social-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e5e7eb;
            background: white;
            color: #6b7280;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .social-btn.facebook { color: #1877f2; border-color: #1877f2; }
        .social-btn.twitter { color: #1da1f2; border-color: #1da1f2; }
        .social-btn.google { color: #ea4335; border-color: #ea4335; }

        .social-btn.facebook:hover { background: #1877f2; color: white; }
        .social-btn.twitter:hover { background: #1da1f2; color: white; }
        .social-btn.google:hover { background: #ea4335; color: white; }

        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            .left-side {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- Left Side - Illustration -->
    <div class="left-side">
        <div class="illustration-wrapper">
            <!-- Ganti dengan gambar ilustrasi welcome Anda -->
            <img 
                src="{{ asset('image/Login.png') }}" 
                alt="Welcome Illustration" 
                class="max-w-full h-auto"
                style="max-height: 500px; object-fit: contain;"
            >
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="p-12 flex flex-col justify-center">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Login</h1>
            <p class="text-gray-600">Please login to continue</p>
        </div>

        <!-- Alert Error -->
        @error('email')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6 flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                <div>
                    <p class="text-red-700 font-medium">Login Gagal</p>
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                </div>
            </div>
        @enderror

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6 flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email Input -->
            <div>
                <input 
                    type="email" 
                    name="email"
                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none text-gray-700 @error('email') border-red-500 @enderror"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required 
                    autofocus>
                @error('email')
                    <p class="text-red-600 text-sm mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input -->
            <div>
                <input 
                    type="password" 
                    name="password"
                    class="w-full border-2 border-gray-200 rounded-lg px-4 py-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none text-gray-700 @error('password') border-red-500 @enderror"
                    placeholder="Password"
                    required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-gray-700 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm">Keep Me Logged In</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 uppercase text-sm tracking-wide">
                LOGIN
            </button>
        </form>

        <!-- Divider -->
        <div class="mt-8 mb-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>