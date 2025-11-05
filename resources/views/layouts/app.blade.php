<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Arsip Warkah')</title>

    <!-- Tailwind & Alpine -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- SweetAlert2 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Alpine.js Data Component -->
    <div x-data="{ mobileMenuOpen: false, showProfileModal: false }" x-cloak>

        <!-- Navigation, Profile Modal, Main Content, Footer -->
        <!-- ... semua konten body Anda di sini ... -->

    </div>
    <!-- End Alpine.js Data Component -->

    <!-- CSS untuk Animasi -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Animasi SweetAlert */
        .animated-popup {
            animation-duration: 0.5s;
        }

        .swal-wide {
            width: 600px !important;
            max-width: 90% !important;
        }

        .swal-button {
            padding: 12px 24px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }

        .swal-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
        }

        .swal-button-delete {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            color: white !important;
        }

        .spinner-border {
            display: inline-block;
            border: 0.25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- Script untuk SweetAlert2 Delete - LETAKKAN DI SINI (SEBELUM </body>) -->
   <!-- Script untuk SweetAlert2 Delete - LETAKKAN SEBELUM </body> -->
<script>
// Fungsi untuk warning status tidak tersedia
function showStatusWarning(status) {
    Swal.fire({
        icon: 'error',
        title: '🚫 Tidak Dapat Dihapus!',
        html: `
            <div style="text-align: left; padding: 10px;">
                <p style="font-size: 15px; margin-bottom: 10px; color: #1f2937;">
                    <strong>Data warkah ini tidak dapat dihapus</strong>
                </p>
                <div style="background: #fef3c7; padding: 14px; border-radius: 8px; border-left: 4px solid #f59e0b; margin: 15px 0;">
                    <p style="margin: 0; font-size: 14px; color: #92400e;">
                        📊 Status Saat Ini: <strong>${status}</strong>
                    </p>
                </div>
                <div style="background: #dbeafe; padding: 14px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-top: 15px;">
                    <p style="margin: 0; font-size: 13px; color: #1e40af;">
                        💡 <strong>Informasi:</strong><br>
                        Hanya data dengan status <strong>"Tersedia"</strong> yang dapat dihapus dari sistem.
                    </p>
                </div>
            </div>
        `,
        confirmButtonText: '<i class="fas fa-check"></i> Mengerti',
        confirmButtonColor: '#2563eb',
        customClass: {
            confirmButton: 'swal-button'
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-warkah').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const warkahInfo = this.dataset.info || 'Data tidak tersedia';
            const warkahStatus = this.dataset.status;
            const deleteUrl = this.dataset.url;
            
            // Cek status
            if (warkahStatus !== 'Tersedia') {
                showStatusWarning(warkahStatus);
                return;
            }
            
            // Konfirmasi hapus
            Swal.fire({
                title: '⚠️ Konfirmasi Penghapusan',
                html: `
                    <div style="text-align: left; margin: 15px 0;">
                        <p style="font-size: 15px; margin-bottom: 15px; color: #1f2937;">
                            <strong>Anda akan menghapus data warkah berikut:</strong>
                        </p>
                        <div style="background: #3b82f6; padding: 16px; border-radius: 10px; margin: 15px 0;">
                            <p style="margin: 0; color: white; font-size: 14px; line-height: 1.6;">
                                <i class="fas fa-file-alt" style="margin-right: 8px;"></i>
                                <strong>${warkahInfo}</strong>
                            </p>
                        </div>
                        <div style="background: #fecaca; padding: 15px; border-radius: 10px; border-left: 4px solid #dc2626;">
                            <p style="margin: 0; font-size: 14px; color: #991b1b; line-height: 1.6;">
                                <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                                <strong>PERINGATAN PENTING:</strong><br>
                                <span style="font-size: 13px;">
                                    Data yang sudah dihapus tidak dapat dikembalikan dan akan hilang permanen dari sistem!
                                </span>
                            </p>
                        </div>
                    </div>
                `,
                icon: 'warning',
                iconColor: '#f59e0b',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus Permanen!',
                cancelButtonText: '<i class="fas fa-times-circle"></i> Batalkan',
                reverseButtons: true,
                focusCancel: true,
                buttonsStyling: true,
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal-popup-custom',
                    confirmButton: 'swal-button-confirm',
                    cancelButton: 'swal-button-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Loading
                    Swal.fire({
                        title: '🗑️ Menghapus Data...',
                        html: '<p style="color: #6b7280; font-size: 14px;">Mohon tunggu sebentar...</p>',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });
                    
                    // Request delete dengan timeout 10 detik
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 10000);
                    
                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        signal: controller.signal
                    })
                    .then(response => {
                        clearTimeout(timeoutId);
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                iconColor: '#10b981',
                                title: '✅ Berhasil Dihapus!',
                                html: `
                                    <div style="text-align: center;">
                                        <p style="font-size: 14px; color: #374151; margin-bottom: 12px;">
                                            Data warkah telah berhasil dihapus dari sistem.
                                        </p>
                                        <div style="background: #d1fae5; padding: 12px; border-radius: 8px; border-left: 4px solid #10b981;">
                                            <p style="margin: 0; font-size: 13px; color: #065f46; text-align: left;">
                                                <i class="fas fa-check-circle"></i> ${data.info || warkahInfo}
                                            </p>
                                        </div>
                                    </div>
                                `,
                                confirmButtonText: '<i class="fas fa-check"></i> OK',
                                confirmButtonColor: '#10b981',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'swal-button'
                                }
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                iconColor: '#dc2626',
                                title: '❌ Gagal Menghapus!',
                                html: `
                                    <p style="font-size: 14px; color: #374151;">${data.message || 'Terjadi kesalahan saat menghapus data.'}</p>
                                    <p style="font-size: 12px; color: #6b7280; margin-top: 8px;">Silakan coba lagi atau hubungi administrator.</p>
                                `,
                                confirmButtonText: '<i class="fas fa-redo"></i> Tutup',
                                confirmButtonColor: '#dc2626',
                                customClass: {
                                    confirmButton: 'swal-button'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        clearTimeout(timeoutId);
                        if (error.name === 'AbortError') {
                            Swal.fire({
                                icon: 'error',
                                iconColor: '#dc2626',
                                title: '⏱️ Timeout!',
                                html: '<p style="font-size: 14px; color: #374151;">Server terlalu lama merespons. Silakan coba lagi.</p>',
                                confirmButtonText: '<i class="fas fa-redo"></i> Tutup',
                                confirmButtonColor: '#dc2626',
                                customClass: {
                                    confirmButton: 'swal-button'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                iconColor: '#dc2626',
                                title: '⚠️ Terjadi Kesalahan!',
                                html: '<p style="font-size: 14px; color: #374151;">Tidak dapat menghubungi server. Periksa koneksi internet Anda.</p>',
                                confirmButtonText: '<i class="fas fa-redo"></i> Tutup',
                                confirmButtonColor: '#dc2626',
                                customClass: {
                                    confirmButton: 'swal-button'
                                }
                            });
                        }
                        console.error('Delete error:', error);
                    });
                }
            });
        });
    });
});
</script>

<!-- CSS untuk SweetAlert Custom -->
<style>
    /* Custom styling untuk SweetAlert */
    .swal-popup-custom {
        border-radius: 12px;
        padding: 20px;
    }

    .swal-button {
        padding: 10px 24px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        transition: all 0.2s ease !important;
    }

    .swal-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    .swal-button-confirm {
        background-color: #dc2626 !important;
        border: none !important;
    }

    .swal-button-confirm:hover {
        background-color: #b91c1c !important;
    }

    .swal-button-cancel {
        background-color: #6b7280 !important;
        border: none !important;
    }

    .swal-button-cancel:hover {
        background-color: #4b5563 !important;
    }

    /* Spinner loading */
    .spinner-border {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }

    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
</style>

    <!-- Stack untuk scripts tambahan dari child views -->
    @stack('scripts')

</body>
</html>

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

                            {{-- ✅ Flash Message (Sukses / Error) --}}
                @if(session('success'))
                <div class="p-4 mb-6 rounded-xl border-l-4 border-green-600 bg-gradient-to-r from-green-50 to-green-100 shadow-md animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-check text-green-600 text-2xl mr-3 mt-1"></i>
                        </div>
                        <div class="flex-1 text-gray-800" style="white-space: normal;">
                            {!! session('success') !!}
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-700 hover:text-green-900">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 mb-6 rounded-xl border-l-4 border-red-600 bg-gradient-to-r from-red-50 to-red-100 shadow-md animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-red-600 text-2xl mr-3 mt-1"></i>
                        </div>
                        <div class="flex-1 text-gray-800" style="white-space: normal;">
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-4 text-red-700 hover:text-red-900">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>
                @endif

                {{-- <script>
                    setTimeout(() => {
                        document.querySelectorAll('.animate-fade-in').forEach(alert => alert.remove());
                    }, 7000);
                </script> --}}

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