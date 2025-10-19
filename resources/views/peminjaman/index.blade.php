@extends('layouts.app')

@section('title', 'Peminjaman Barang')

@section('content')
<div x-data="peminjamanApp()" class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Peminjaman Barang</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola data peminjaman dan pengembalian warkah</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-gradient-to-r from-white via-gray-50 to-white rounded-xl shadow-lg border border-gray-200 p-4 transform transition-all duration-300 hover:shadow-xl">
        <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
            <!-- Tombol Tambah -->
            <button
                @click="openModal = true"
                class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                <i class="fa-solid fa-plus mr-2 text-lg"></i>
                <span>Tambah Peminjaman Baru</span>
            </button>

            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <!-- Search -->
                <form method="GET" action="{{ route('peminjaman.index') }}" class="relative flex-1 sm:flex-none">
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fa-solid fa-search text-gray-400 group-focus-within:text-blue-600"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari Peminjaman..."
                            class="w-full sm:w-64 pl-11 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 bg-white shadow-md hover:shadow-lg" />
                    </div>
                </form>

                <!-- Filter Status -->
                <form method="GET" action="{{ route('peminjaman.index') }}" class="relative flex-1 sm:flex-none">
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fa-solid fa-filter text-gray-400 group-focus-within:text-blue-600"></i>
                        </span>
                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="w-full sm:w-56 pl-11 pr-10 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 appearance-none bg-white transition-all duration-200 cursor-pointer font-semibold text-gray-700 shadow-md hover:shadow-lg">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>🔵 Dipinjam</option>
                            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>🔴 Terlambat</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>🟢 Dikembalikan</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-200 overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 px-6 py-4">
            <h2 class="text-lg font-bold text-white flex items-center">
                <i class="fa-solid fa-table mr-2"></i>
                Data Peminjaman Barang
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 via-gray-100 to-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">No</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Kode Warkah</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Uraian Informasi</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Nama</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">No HP</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Email</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Tanggal Pinjam</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Tujuan Pinjam</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Batas Peminjaman</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Status</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($peminjaman as $index => $item)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200 transform hover:scale-[1.01] hover:shadow-md">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xs shadow-md">
                                {{ $peminjaman->firstItem() + $index }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md">
                                    <i class="fa-solid fa-hashtag mr-1.5 text-xs"></i>
                                    {{ $item->id_warkah }}
                                </span>
                                @if($item->warkah)
                                <span class="text-xs text-gray-500 mt-1 font-medium">
                                    {{ $item->warkah->kode_klasifikasi ?? '-' }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            <div class="max-w-xs">
                                @if($item->warkah)
                                <p class="font-semibold text-gray-900 line-clamp-2" title="{{ $item->warkah->uraian_informasi_arsip }}">
                                    {{ Str::limit($item->warkah->uraian_informasi_arsip, 60) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                    {{ $item->warkah->ruang_penyimpanan_rak ?? 'Lokasi tidak tersedia' }}
                                </p>
                                @else
                                <p class="text-gray-400 italic">Data tidak tersedia</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md mr-3">
                                    {{ strtoupper(substr($item->nama_peminjam, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $item->nama_peminjam }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->no_hp }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->email }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->tanggal_pinjam->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            <div class="max-w-xs truncate" title="{{ $item->tujuan_pinjam }}">
                                {{ $item->tujuan_pinjam }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->batas_peminjaman->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($item->status == 'Dipinjam')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
                                <i class="fa-solid fa-clock mr-1.5"></i>
                                Dipinjam
                            </span>
                            @elseif($item->status == 'Dikembalikan')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg">
                                <i class="fa-solid fa-circle-check mr-1.5"></i>
                                Dikembalikan
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg animate-pulse">
                                <i class="fa-solid fa-circle-exclamation mr-1.5"></i>
                                Terlambat
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    @click="showDetail({{ $item->id }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-bold rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <i class="fa-solid fa-eye mr-1.5"></i>
                                    Lihat Detail
                                </button>
                                @if($item->status != 'Dikembalikan')
                                <form method="POST" action="{{ route('peminjaman.kembalikan', $item->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin mengembalikan barang ini?')"
                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-xs font-bold rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                        <i class="fa-solid fa-arrow-rotate-left mr-1.5"></i>
                                        Kembalikan
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                    <i class="fa-solid fa-inbox text-gray-400 text-5xl"></i>
                                </div>
                                <p class="text-gray-600 text-xl font-bold mb-2">Belum ada data peminjaman</p>
                                <p class="text-gray-400 text-sm mb-4">Klik tombol "Tambah Peminjaman Baru" untuk memulai</p>
                                <button
                                    @click="openModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <i class="fa-solid fa-plus mr-2"></i>
                                    Tambah Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($peminjaman->hasPages())
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t-2 border-blue-500">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-sm font-semibold text-gray-700 bg-white px-4 py-2 rounded-lg shadow-md">
                    Menampilkan
                    <span class="text-blue-600 font-bold">{{ $peminjaman->firstItem() }}</span>
                    -
                    <span class="text-blue-600 font-bold">{{ $peminjaman->lastItem() }}</span>
                    dari
                    <span class="text-blue-600 font-bold">{{ $peminjaman->total() }}</span>
                    data
                </div>
                <div>
                    {{ $peminjaman->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Detail Section -->
    <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg border-2 border-blue-200 p-6 transform transition-all duration-300 hover:shadow-2xl">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-md mr-4">
                <i class="fa-solid fa-info-circle text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Detail Peminjaman</h3>
                <p class="text-sm text-gray-600">Informasi lengkap tentang peminjaman</p>
            </div>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-inner border border-gray-200">
            <p class="text-sm text-gray-600 flex items-center">
                <i class="fa-solid fa-hand-pointer text-blue-600 mr-2"></i>
                Klik tombol <span class="mx-1 px-2 py-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md text-xs font-bold shadow-md">"Lihat Detail"</span> pada tabel untuk menampilkan informasi lengkap peminjaman
            </p>
        </div>
    </div>

    <!-- MODAL TAMBAH PEMINJAMAN BARU -->
    <div
        x-show="openModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div
                x-show="openModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="openModal = false"
                class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                aria-hidden="true">
            </div>

            <!-- Modal panel -->
            <div
                x-show="openModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

                <!-- Header Modal -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-file-circle-plus text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white" id="modal-title">
                                    Tambah Peminjaman Baru
                                </h3>
                                <p class="text-blue-100 text-sm mt-0.5">Isi form di bawah untuk meminjam warkah</p>
                            </div>
                        </div>
                        <button
                            @click="openModal = false"
                            class="text-white hover:text-gray-200 transition-colors p-2 hover:bg-white hover:bg-opacity-10 rounded-lg">
                            <i class="fa-solid fa-xmark text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <form method="POST" action="{{ route('peminjaman.store') }}" x-data="formPeminjamanModal()">
                    @csrf
                    <div class="px-6 py-6 max-h-[calc(100vh-300px)] overflow-y-auto">
                        <div class="space-y-6">

                            <!-- Section 1: Pilih Warkah -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border-2 border-blue-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fa-solid fa-folder-open text-white text-sm"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900">1. Pilih Warkah yang Akan Dipinjam</h4>
                                </div>

                                <!-- Searchable Dropdown Warkah -->
                                <div class="relative">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Cari dan Pilih Warkah <span class="text-red-500">*</span>
                                    </label>

                                    <!-- Search Input -->
                                    <div @click="open = !open" class="relative">
                                        <div class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100 transition-all">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <input
                                                        type="text"
                                                        x-model="search"
                                                        @input="searchWarkah()"
                                                        @click.stop
                                                        @focus="open = true"
                                                        placeholder="Ketik 1972 atau keyword lain..."
                                                        class="w-full outline-none text-gray-900 placeholder-gray-400" />
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <template x-if="selected">
                                                        <button
                                                            type="button"
                                                            @click.stop="clearSelection()"
                                                            class="text-gray-400 hover:text-red-500 transition">
                                                            <i class="fa-solid fa-circle-xmark"></i>
                                                        </button>
                                                    </template>
                                                    <i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                                </div>
                                            </div>

                                            <!-- Selected Item Display -->
                                            <template x-if="selected">
                                                <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-600 text-white">
                                                                #<span x-text="selected.id"></span>
                                                            </span>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-semibold text-gray-900" x-text="selected.uraian_informasi_arsip"></p>
                                                            <p class="text-xs text-gray-600 mt-1">
                                                                <i class="fa-solid fa-tag mr-1"></i>
                                                                <span x-text="selected.kode_klasifikasi || '-'"></span>
                                                                <template x-if="selected.kurun_waktu_berkas">
                                                                    <span>
                                                                        <span class="mx-2">•</span>
                                                                        <i class="fa-solid fa-calendar mr-1"></i>
                                                                        <span x-text="selected.kurun_waktu_berkas"></span>
                                                                    </span>
                                                                </template>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Dropdown List -->
                                    <div
                                        x-show="open"
                                        @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-1"
                                        class="absolute z-10 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-2xl max-h-80 overflow-auto">

                                        <!-- Loading State -->
                                        <template x-if="loading">
                                            <div class="p-6 text-center">
                                                <i class="fa-solid fa-spinner fa-spin text-blue-600 text-2xl mb-2"></i>
                                                <p class="text-sm text-gray-600">Memuat data warkah...</p>
                                            </div>
                                        </template>

                                        <!-- List Items -->
                                        <template x-if="!loading && warkahList.length > 0">
                                            <div>
                                                <template x-for="item in warkahList" :key="item.id">
                                                    <div
                                                        @click="selectItem(item)"
                                                        class="px-4 py-3 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 cursor-pointer border-b border-gray-100 transition-all duration-200"
                                                        :class="selected && selected.id === item.id ? 'bg-blue-50 border-l-4 border-l-blue-600' : ''">
                                                        <div class="flex items-start space-x-3">
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-sm">
                                                                #<span x-text="item.id"></span>
                                                            </span>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-semibold text-gray-900 line-clamp-2" x-text="item.uraian_informasi_arsip"></p>
                                                                <div class="flex flex-wrap gap-2 mt-1.5">
                                                                    <span class="inline-flex items-center text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">
                                                                        <i class="fa-solid fa-tag mr-1"></i>
                                                                        <span x-text="item.kode_klasifikasi || '-'"></span>
                                                                    </span>
                                                                    <template x-if="item.ruang_penyimpanan_rak">
                                                                        <span class="inline-flex items-center text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">
                                                                            <i class="fa-solid fa-location-dot mr-1"></i>
                                                                            <span x-text="item.ruang_penyimpanan_rak"></span>
                                                                        </span>
                                                                    </template>
                                                                    <template x-if="item.kurun_waktu_berkas">
                                                                        <span class="inline-flex items-center text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">
                                                                            <i class="fa-solid fa-calendar mr-1"></i>
                                                                            <span x-text="item.kurun_waktu_berkas"></span>
                                                                        </span>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-sm">
                                                                <i class="fa-solid fa-circle-check mr-1"></i>
                                                                Tersedia
                                                            </span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <!-- Empty State -->
                                        <template x-if="!loading && warkahList.length === 0">
                                            <div class="p-8 text-center">
                                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <i class="fa-solid fa-inbox text-gray-400 text-3xl"></i>
                                                </div>
                                                <p class="text-gray-600 font-semibold mb-1">Tidak ada warkah yang ditemukan</p>
                                                <p class="text-gray-400 text-sm">
                                                    <template x-if="search">
                                                        <span>Coba gunakan kata kunci yang berbeda</span>
                                                    </template>
                                                    <template x-if="!search">
                                                        <span>Belum ada data warkah tersedia</span>
                                                    </template>
                                                </p>
                                            </div>
                                        </template>
                                    </div>

                                    <input type="hidden" name="id_warkah" :value="selected ? selected.id : ''" required>
                                </div>

                                <p class="mt-2 text-xs text-gray-600">
                                    <i class="fa-solid fa-info-circle mr-1"></i>
                                    Ketik untuk mencari berdasarkan kode klasifikasi, uraian informasi, lokasi, tahun, atau nomor item arsip
                                </p>
                            </div>
                            <!-- Section 2: Data Peminjam -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border-2 border-green-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-600 to-green-700 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fa-solid fa-user text-white text-sm"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900">2. Identitas Peminjam</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nama Peminjam -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input
                                                type="text"
                                                name="nama_peminjam"
                                                required
                                                placeholder="Masukkan nama lengkap peminjam"
                                                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all outline-none">
                                        </div>
                                    </div>

                                    <!-- No HP -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nomor HP <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                                <i class="fa-solid fa-phone"></i>
                                            </span>
                                            <input
                                                type="tel"
                                                name="no_hp"
                                                required
                                                placeholder="08xxxxxxxxxx"
                                                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all outline-none">
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                name="email"
                                                required
                                                placeholder="email@example.com"
                                                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all outline-none">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Detail Peminjaman -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border-2 border-purple-200">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fa-solid fa-calendar-days text-white text-sm"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900">3. Informasi Peminjaman</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Tanggal Pinjam -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Pinjam <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                                <i class="fa-solid fa-calendar-check"></i>
                                            </span>
                                            <input
                                                type="date"
                                                name="tanggal_pinjam"
                                                required
                                                value="{{ date('Y-m-d') }}"
                                                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all outline-none">
                                        </div>
                                    </div>

                                    <!-- Batas Peminjaman -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Batas Pengembalian <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                                <i class="fa-solid fa-calendar-xmark"></i>
                                            </span>
                                            <input
                                                type="date"
                                                name="batas_peminjaman"
                                                required
                                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all outline-none">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-600">
                                            <i class="fa-solid fa-info-circle mr-1"></i>
                                            Minimal 1 hari dari tanggal pinjam
                                        </p>
                                    </div>

                                    <!-- Tujuan Pinjam -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tujuan Peminjaman <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute top-3 left-4 text-gray-400">
                                                <i class="fa-solid fa-bullseye"></i>
                                            </span>
                                            <textarea
                                                name="tujuan_pinjam"
                                                required
                                                rows="3"
                                                placeholder="Jelaskan tujuan peminjaman warkah ini..."
                                                class="w-full pl-11 pr-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all outline-none resize-none"></textarea>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-600">
                                            Contoh: Penelitian akademik, audit internal, verifikasi data, dll.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-400 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fa-solid fa-lightbulb text-amber-600 text-xl mr-3 mt-0.5"></i>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-bold text-amber-900 mb-1">Informasi Penting</h5>
                                        <ul class="text-xs text-amber-800 space-y-1">
                                            <li>• Status warkah akan otomatis berubah menjadi "Dipinjam"</li>
                                            <li>• Pastikan data yang Anda masukkan sudah benar sebelum menyimpan</li>
                                            <li>• Anda akan menerima notifikasi reminder sebelum batas pengembalian</li>
                                            <li>• Keterlambatan pengembalian akan tercatat dalam sistem</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row-reverse gap-3">
                            <button
                                type="submit"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-check-circle mr-2"></i>
                                Pinjam Sekarang
                            </button>
                            <button
                                type="button"
                                @click="openModal = false"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold border-2 border-gray-300 rounded-xl transition-all duration-200">
                                <i class="fa-solid fa-xmark mr-2"></i>
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END MODAL -->

</div>

<script>
    function peminjamanApp() {
        return {
            openModal: false,

            showDetail(id) {
                fetch(`/peminjaman/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        let warkahInfo = data.warkah ?
                            `Kode: ${data.warkah.kode_klasifikasi}-${data.warkah.nomor_item_arsip}\n` +
                            `Uraian: ${data.warkah.uraian_informasi_arsip}\n` +
                            `Lokasi: ${data.warkah.ruang_penyimpanan_rak || '-'}` :
                            'Data warkah tidak ditemukan';

                        alert(
                            `Detail Peminjaman:\n\n` +
                            `=== INFORMASI WARKAH ===\n${warkahInfo}\n\n` +
                            `=== INFORMASI PEMINJAM ===\n` +
                            `Nama: ${data.nama_peminjam}\n` +
                            `No HP: ${data.no_hp}\n` +
                            `Email: ${data.email}\n\n` +
                            `=== INFORMASI PEMINJAMAN ===\n` +
                            `Tanggal Pinjam: ${data.tanggal_pinjam}\n` +
                            `Batas Kembali: ${data.batas_peminjaman}\n` +
                            `Tujuan: ${data.tujuan_pinjam}\n` +
                            `Status: ${data.status}`
                        );
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail peminjaman');
                    });
            }
        }
    }

    function formPeminjamanModal() {
        return {
            warkahList: [],
            loading: false,
            searchTimeout: null,
            open: false,
            search: '',
            selected: null,

            init() {
                console.log('🎬 Form modal initialized');
                this.loadWarkah();
            },

            async loadWarkah(searchKeyword = '') {
                this.loading = true;
                console.log('🔄 Loading warkah, search:', searchKeyword);

                try {
                    const url = new URL('/peminjaman/api/available-warkah', window.location.origin);
                    if (searchKeyword) {
                        url.searchParams.append('search', searchKeyword);
                    }

                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error('Gagal memuat data');
                    }

                    const data = await response.json();
                    this.warkahList = data;

                    console.log('✅ Data loaded:', this.warkahList.length, 'items');
                    if (this.warkahList.length > 0) {
                        console.log('📦 First item:', this.warkahList[0]);
                    }
                } catch (error) {
                    console.error('❌ Error loading warkah:', error);
                    alert('Gagal memuat data warkah. Cek console untuk detail.');
                    this.warkahList = [];
                } finally {
                    this.loading = false;
                }
            },

            searchWarkah() {
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }

                this.searchTimeout = setTimeout(() => {
                    console.log('🔍 Searching for:', this.search);
                    this.loadWarkah(this.search);
                }, 300);
            },

            selectItem(item) {
                this.selected = item;
                this.open = false;
                this.search = '';
                console.log('✅ Selected:', item);
            },

            clearSelection() {
                this.selected = null;
                this.search = '';
                this.loadWarkah();
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection