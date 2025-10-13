@extends('layouts.app')

@section('title', 'Master Data - Sistem Arsip Warkah')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Master Data Arsip</h1>
            <p class="mt-2 text-sm text-gray-600">Kelola semua dokumen warkah Anda dengan mudah</p>
        </div>
        <a href="{{ route('warkah.create') }}" 
           class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition transform hover:scale-105">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Data
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">

        <!-- Search & Filter Section -->
        <form method="GET" action="{{ route('warkah.index') }}" class="bg-white rounded-lg shadow-md p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari Data
                    </label>
                    <input 
                        type="text" 
                        name="keyword"
                        value="{{ $keyword ?? '' }}"
                        placeholder="Cari nama, no SK, atau no warkah..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <!-- Filter Tahun -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-calendar mr-1"></i> Tahun
                    </label>
                    <select 
                        name="tahun"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ ($filters['tahun'] ?? '') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Lokasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-map-pin mr-1"></i> Lokasi
                    </label>
                    <select 
                        name="lokasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasiList as $lokasi)
                            <option value="{{ $lokasi }}" {{ ($filters['lokasi'] ?? '') == $lokasi ? 'selected' : '' }}>{{ $lokasi }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-4 flex flex-wrap gap-2">
                <button 
                    type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    <i class="fa-solid fa-search mr-1"></i> Cari
                </button>
                <a href="{{ route('warkah.index') }}"
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                </a>
            </div>
        </form>

        <!-- Table Section (Desktop) -->
        <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-file-contract mr-1"></i> No. SK
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-file mr-1"></i> Nama Arsip
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-tag mr-1"></i> Kode Klasifikasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-calendar mr-1"></i> Tahun
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-note-sticky mr-1"></i> Uraian Informasi Arsip
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-cog mr-1"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($warkah as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $item->no_sk }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nama }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->kode_klasifikasi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->tahun }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->uraian_informasi_arsip, 50) ?: '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('warkah.show', $item->id) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                           title="Lihat Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('warkah.edit', $item->id) }}" 
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                                           title="Edit">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada data arsip</p>
                                       
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card Section (Mobile) -->
        <div class="md:hidden space-y-4">
            @forelse ($warkah as $item)
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-600">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $item->nama }}</h3>
                            <p class="text-xs text-gray-500">{{ $item->no_sk }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $item->tahun }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-4 text-sm">
                        <div class="flex items-start">
                            <span class="font-medium text-gray-600 mr-2 w-28">Klasifikasi:</span>
                            <span class="text-gray-900">{{ $item->kode_klasifikasi }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-medium text-gray-600 mr-2 w-28">Lokasi:</span>
                            <span class="text-gray-900">{{ $item->lokasi }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-medium text-gray-600 mr-2 w-28">Keterangan:</span>
                            <span class="text-gray-900">{{ Str::limit($item->keterangan, 60) ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <a href="{{ route('warkah.show', $item->id) }}" 
                           class="flex-1 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium rounded text-center transition">
                            <i class="fa-solid fa-eye mr-1"></i> Lihat
                        </a>
                        <a href="{{ route('warkah.edit', $item->id) }}" 
                           class="flex-1 py-2 bg-green-50 hover:bg-green-100 text-green-600 font-medium rounded text-center transition">
                            <i class="fa-solid fa-pencil mr-1"></i> Edit
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500 font-medium">Tidak ada data arsip</p>
                    <a href="{{ route('warkah.create') }}" 
                       class="mt-4 inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Buat Data Pertama
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($warkah->hasPages())
            <div class="flex justify-center">
                {{ $warkah->links() }}
            </div>
        @endif

    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fa-solid fa-exclamation text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Penghapusan</h3>
            <p class="text-gray-600 text-center mb-6">
                Apakah Anda yakin ingin menghapus data arsip ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-3">
                <button 
                    onclick="closeDeleteModal()"
                    type="button"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button 
                    onclick="submitDelete()"
                    type="button"
                    class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let deleteItemId = null;

        function openDeleteModal(id) {
            deleteItemId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteItemId = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function submitDelete() {
            if (deleteItemId) {
                const form = document.getElementById('deleteForm');
                form.action = '/warkah/' + deleteItemId;
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection