<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Arsip Warkah')</title>

    <!-- Tailwind & Alpine -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="//unpkg.com/alpinejs" defer></script>



    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false, darkMode: false }">

    <!-- 🔵 Navigation Bar -->
    <nav class="bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a class="flex items-center space-x-2 text-xl sm:text-2xl font-bold hover:opacity-90 transition">
                    <i class="fa-solid fa-folder-open text-white"></i>
                    <span class="hidden sm:inline">Warkah System</span>
                    <span class="sm:hidden">Warkah</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-6">
                    <a href="{{ route('warkah.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fa-solid fa-database mr-1"></i> Master Data
                    </a>
                    <a href="{{ route('peminjaman.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fa-solid fa-handshake mr-1"></i> Peminjaman & Pengembalian
                    </a>
                    <a href="#" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fa-solid fa-copy mr-1"></i> Permintaan Salinan
                    </a>
                </div>

                <!-- User & Mobile Menu Button -->
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:flex items-center space-x-2">
                        <i class="fa-solid fa-user-circle text-lg sm:text-xl"></i>
                        <span class="text-xs sm:text-sm font-medium">Admin</span>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-md hover:bg-blue-700 transition"
                        aria-label="Toggle menu"
                        aria-expanded="mobileMenuOpen">
                        <i :class="mobileMenuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- 🌐 Mobile Overlay -->
        <div
            class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
            x-show="mobileMenuOpen"
            x-transition.opacity
            @click="mobileMenuOpen = false"
            x-cloak>
        </div>

        <!-- 📱 Mobile Slide Menu -->
        <div
            class="fixed top-0 left-0 h-full w-64 bg-blue-600 shadow-lg transform transition-transform duration-300 z-50 md:hidden"
            :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
            x-cloak>
            <div class="flex items-center justify-between px-4 py-4 border-b border-blue-500">
                <h2 class="text-lg font-semibold">Menu</h2>
                <button @click="mobileMenuOpen = false" class="p-2 rounded hover:bg-blue-700">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <nav class="px-4 py-3 space-y-2">
                <a href="{{ route('warkah.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fa-solid fa-database mr-2"></i> Master Data
                </a>
                <a href="{{ route('peminjaman.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fa-solid fa-handshake mr-2"></i> Peminjaman
                </a>
                <a href="#" class="block px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fa-solid fa-copy mr-2"></i> Permintaan Salinan
                </a>
                <div class="border-t border-blue-500 mt-3 pt-3">
                    <a href="#" class="block px-3 py-2 text-sm hover:bg-blue-700 rounded-md transition">
                        <i class="fa-solid fa-user-circle mr-2"></i> Admin
                    </a>
                </div>
            </nav>
        </div>
    </nav>

    <!-- 🌤️ Optional Header Section -->
    @hasSection('header')
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
            @yield('header')
        </div>
    </header>
    @endif

    <!-- 📦 Main Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fa-solid fa-circle-exclamation text-red-600 mt-1 mr-3"></i>
                    <div>
                        <h3 class="font-semibold text-red-800">Terjadi Kesalahan</h3>
                        <ul class="mt-2 text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start">
                    <i class="fa-solid fa-circle-check text-green-600 mt-1 mr-3"></i>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- ⚪ Footer -->
    <footer class="bg-white border-t border-gray-200 mt-10">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Tentang</h3>
                    <p class="text-sm text-gray-600">Sistem Arsip Warkah untuk pengelolaan dokumen dengan efisien.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Link Cepat</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><a href="#" class="hover:text-blue-600 transition">Bantuan</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Kontak</h3>
                    <p class="text-sm text-gray-600">support@warkah.id</p>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-6 text-center text-gray-600 text-sm">
                <p>&copy; {{ date('Y') }} <span class="font-semibold text-blue-600">Sistem Arsip Warkah</span>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    {{--
    <!-- 🌙 Floating Dark Mode Toggle -->
    <button 
        @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode)"
        class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg hover:shadow-xl transition transform hover:scale-110"
        title="Toggle Dark Mode"
        aria-label="Toggle dark mode">
        <i :class="darkMode ? 'fa-solid fa-sun' : 'fa-solid fa-moon'"></i>
    </button> --}}

</body>

</html>