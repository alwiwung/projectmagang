<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Arsip Warkah')</title>

    <!-- Tailwind & Alpine -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Alpine.js Data Component -->
    <div x-data="{ mobileMenuOpen: false, showProfileModal: false }" x-cloak>

        <!-- 🔵 Navigation Bar -->
        <nav class="bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md sticky top-0 z-50" x-data="{ userDropdown: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('warkah.index') }}" class="flex items-center space-x-2 text-xl sm:text-2xl font-bold hover:opacity-90 transition">
                        <i class="fa-solid fa-folder-open text-white"></i>
                        <span class="hidden sm:inline">Warkah Hebat</span>
                        <span class="sm:hidden">Warkah</span>
                    </a>

                    <div class="hidden md:flex space-x-6 items-center">
                        <a href="{{ route('warkah.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition flex items-center">
                            <i class="fa-solid fa-database mr-2"></i> Master Data Warkah
                        </a>
                        <a href="{{ route('peminjaman.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition flex items-center">
                            <i class="fa-solid fa-handshake mr-2"></i> Peminjaman & Pengembalian
                        </a>
                        <a href="{{ route('permintaan.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition flex items-center">
                            <i class="fa-solid fa-copy mr-2"></i> Permintaan Salinan
                        </a>
                    </div>


                    <!-- User Dropdown & Mobile Menu Button -->
                    <div class="flex items-center space-x-3">
                        <!-- User Dropdown (Desktop) -->
                        <div class="hidden sm:block relative" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                @click.away="open = false"
                                class="flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-blue-700 transition">
                                <i class="fa-solid fa-user-circle text-lg sm:text-xl"></i>
                                <span class="text-xs sm:text-sm font-medium">
                                    @auth
                                    {{ auth()->user()->name ?? 'Admin' }}
                                    @else
                                    Admin
                                    @endauth
                                </span>
                                <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                style="display: none;">

                                <!-- Profile Link -->
                                <button
                                    @click.prevent="open = false; showProfileModal = true"
                                    type="button"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    <i class="fa-solid fa-user mr-2"></i> Profil Saya
                                </button>

                                <!-- Divider -->
                                <div class="border-t border-gray-200"></div>

                                <!-- Logout -->
                                @auth
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                                    </button>
                                </form>
                                @endauth
                            </div>
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
                    <a href="{{ route('permintaan.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                        <i class="fa-solid fa-copy mr-2"></i> Permintaan Salinan
                    </a>
                    <div class="border-t border-blue-500 mt-3 pt-3">
                        <button
                            @click.prevent="mobileMenuOpen = false; showProfileModal = true"
                            type="button"
                            class="w-full text-left block px-3 py-2 text-sm hover:bg-blue-700 rounded-md transition">
                            <i class="fa-solid fa-user-circle mr-2"></i> Profil Saya
                        </button>
                        @auth
                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-2 text-sm hover:bg-red-600 rounded-md transition">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                            </button>
                        </form>
                        @endauth
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

        <!-- 👤 Profile Modal -->
        <template x-if="showProfileModal">
            <div
                class="fixed inset-0 z-[60] overflow-y-auto"
                aria-labelledby="modal-title"
                role="dialog"
                aria-modal="true"
                @keydown.escape.window="showProfileModal = false"
                x-cloak>

                <!-- Background Overlay -->
                <div
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showProfileModal = false">
                </div>

                <!-- Modal Content -->
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        @click.stop>

                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-white flex items-center" id="modal-title">
                                    <i class="fa-solid fa-user-circle mr-2"></i>
                                    Profil Saya
                                </h3>
                                <button
                                    @click="showProfileModal = false"
                                    type="button"
                                    class="text-white hover:text-gray-200 transition">
                                    <i class="fa-solid fa-xmark text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="bg-white px-6 py-6">
                            <!-- Profile Avatar -->
                            <div class="flex justify-center mb-6">
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fa-solid fa-user text-white text-4xl"></i>
                                </div>
                            </div>

                            <!-- User Information -->
                            <div class="space-y-4">
                                <!-- Nama -->
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center mb-2">
                                        <i class="fa-solid fa-user mr-2 text-blue-600"></i>
                                        Nama Lengkap
                                    </label>
                                    <p class="text-gray-900 font-medium text-lg">
                                        @auth
                                        {{ auth()->user()->name ?? 'Admin' }}
                                        @else
                                        Admin
                                        @endauth
                                    </p>
                                </div>

                                <!-- Email -->
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center mb-2">
                                        <i class="fa-solid fa-envelope mr-2 text-blue-600"></i>
                                        Email
                                    </label>
                                    <p class="text-gray-900 font-medium text-lg break-all">
                                        @auth
                                        {{ auth()->user()->email ?? 'admin@warkah.id' }}
                                        @else
                                        admin@warkah.id
                                        @endauth
                                    </p>
                                </div>

                                <!-- Joined Date -->
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center mb-2">
                                        <i class="fa-solid fa-calendar mr-2 text-blue-600"></i>
                                        Bergabung Sejak
                                    </label>
                                    <p class="text-gray-900 font-medium">
                                        @auth
                                        {{ auth()->user()->created_at ? auth()->user()->created_at->format('d F Y') : '-' }}
                                        @else
                                        -
                                        @endauth
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button
                                @click="showProfileModal = false"
                                type="button"
                                class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <!-- End Profile Modal -->

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

    </div>
    <!-- End Alpine.js Data Component -->

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</body>

</html>