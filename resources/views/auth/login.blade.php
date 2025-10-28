<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Warkah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-center text-2xl font-bold mb-6 text-indigo-600">
            Login Sistem Warkah
        </h2>

        {{-- Alert Error --}}
{{-- Alert Error Login --}}
@error('email')
    <div class="bg-red-100 p-3 rounded text-red-600 mb-4 text-center text-sm">
        ⚠️ {{ $message }}
    </div>
@enderror

        @if (session('success'))
            <div class="bg-green-100 p-3 rounded text-green-700 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-gray-600 font-medium">Email</label>
                <input type="email" name="email"
                       class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-300"
                       value="{{ old('email') }}"
                       required autofocus>
            </div>

            <div>
                <label class="block text-gray-600 font-medium">Password</label>
                <input type="password" name="password"
                       class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-300"
                       required>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="h-4 w-4">
                    Ingat saya
                </label>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded shadow">
                Login
            </button>
        </form>

    </div>
</div>

<div>
    <label class="block text-gray-600 font-medium">Email</label>
    <input type="email" name="email"
           class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-300"
           value="{{ old('email') }}"
           required autofocus>
    @error('email')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-gray-600 font-medium">Password</label>
    <input type="password" name="password"
           class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-300"
           required>
    @error('password')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


</body>
</html>
