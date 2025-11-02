@extends('layouts.app')
@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Master Data - Sistem Arsip Warkah')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Daftar Master Data Warkah</h1>
        <p class="mt-2 text-sm text-gray-600">Kelola semua dokumen warkah Anda dengan mudah</p>
    </div>
    <a href="{{ route('warkah.create') }}"
        class="mt-4 sm:mt-0 inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Data
    </a>
</div>

<!-- Statistics Card -->
<div class="mt-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium opacity-90">Total data Warkah Keseluruhan</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalWarkah ?? 0 }} Warkah</h3>
        </div>
        <div class="bg-white bg-opacity-20 rounded-full p-3">
            <i class="fas fa-folder-open text-2xl"></i>
        </div>
    </div>
</div>

<div class="mt-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-lg border-2 border-green-200 p-5 transform transition-all duration-300 hover:shadow-xl">
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <!-- Info Section -->
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-file-export text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    Export & Import Data
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                        <i class="fa-solid fa-database mr-1"></i>
                        {{ $totalWarkah ?? 0 }} Data
                    </span>
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Format file harus .xlsx atau .xls
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <form action="{{ route('warkah.export') }}" method="GET">
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                <input type="hidden" name="kurun_waktu_berkas" value="{{ request('kurun_waktu_berkas') }}">
                <input type="hidden" name="ruang_penyimpanan_rak" value="{{ request('ruang_penyimpanan_rak') }}">
                <input type="hidden" name="kode_klasifikasi" value="{{ request('kode_klasifikasi') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">

                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 group">
                    <i class="fa-solid fa-file-excel text-xl mr-2 group-hover:animate-bounce"></i>
                    Export Excel
                </button>
            </form>

            <form action="{{ route('warkah.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 cursor-pointer group">
                    <i class="fa-solid fa-file-import text-xl mr-2 group-hover:animate-bounce"></i>
                    Import Excel
                    <input type="file" name="file" accept=".xlsx,.xls" required class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Search & Filter Section -->
    <form method="GET" action="{{ route('warkah.index') }}" class="bg-gradient-to-r from-white via-gray-50 to-white rounded-xl shadow-lg border border-gray-200 p-4 sm:p-6 transform transition-all duration-300 hover:shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari Data
                </label>
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="Cari, uraian informasi atau kode klasifikasi..."
                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition">
            </div>

            <!-- Filter Tahun -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fa-solid fa-calendar mr-1"></i> Tahun
                </label>
                <select
                    name="kurun_waktu_berkas"
                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                    <option value="{{ $tahun }}" {{ request('kurun_waktu_berkas') == $tahun ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Lokasi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fa-solid fa-map-pin mr-1"></i> Ruang Penyimpanan / Rak
                </label>
                <select
                    name="ruang_penyimpanan_rak"
                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasiList as $rak)
                    <option value="{{ $rak }}" {{ request('ruang_penyimpanan_rak') == $rak ? 'selected' : '' }}>
                        {{ $rak }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tombol -->
        <div class="mt-4 flex flex-wrap gap-2">
            <button
                type="submit"
                class="px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                <i class="fa-solid fa-search mr-1"></i> Cari
            </button>
            <a href="{{ route('warkah.index') }}"
                class="px-5 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                <i class="fa-solid fa-rotate-left mr-1"></i> Reset
            </a>
        </div>
    </form>

    <!-- Table Section -->
    <div class="hidden md:block bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-200 overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 px-6 py-4">
            <h2 class="text-lg font-bold text-white flex items-center">
                <i class="fa-solid fa-table mr-2"></i>
                Data Master Warkah
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gradient-to-r from-gray-50 via-gray-100 to-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">No</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">
                            <i class="fa-solid fa-tag mr-1"></i> Kode Klasifikasi
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">
                            <i class="fa-solid fa-note-sticky mr-1"></i> Uraian Informasi Arsip
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">
                            <i class="fa-solid fa-calendar mr-1"></i> Kurun Waktu
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">
                            <i class="fa-solid fa-map-pin mr-1"></i> Ruang Penyimpanan / Rak
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">
                            <i class="fa-solid fa-circle-info mr-1"></i> Status
                        </th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">
                            <i class="fa-solid fa-cog mr-1"></i> Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($warkah as $index => $item)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200">
                        <td class="text-center text-sm font-medium px-4 py-3">
                            {{ ($warkah->currentPage() - 1) * $warkah->perPage() + $loop->iteration }}
                        </td>
                        <td class="text-center text-sm px-4 py-3">
                            {{ $item->kode_klasifikasi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs" title="{{ $item->uraian_informasi_arsip }}">
                            {{ Str::limit($item->uraian_informasi_arsip, 60) ?: '-' }}
                        </td>
                        <td class="text-center text-sm px-4 py-3">
                            {{ $item->kurun_waktu_berkas ?? '-' }}
                        </td>
                        <td class="text-center text-sm px-4 py-3">
                            {{ $item->ruang_penyimpanan_rak ?? '-' }}
                        </td>
                        <td class="text-center text-sm px-4 py-3">
                            @if($item->status == 'Tersedia')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                {{ $item->status }}
                            </span>
                            @elseif($item->status == 'Dipinjam')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                {{ $item->status }}
                            </span>
                            @elseif($item->status == 'Terlambat')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                {{ $item->status }}
                            </span>
                            @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                {{ $item->status ?? '-' }}
                            </span>
                            @endif
                        </td>
                        <td class="text-center px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('warkah.show', $item->id) }}"
                                    class="p-2 rounded-full text-blue-600 hover:bg-blue-100 transition"
                                    title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('warkah.edit', $item->id) }}"
                                    class="p-2 rounded-full text-green-600 hover:bg-green-100 transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                @if($item->status == 'Tersedia')
                                <form action="{{ route('warkah.destroy', $item->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data warkah ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 rounded-full text-red-600 hover:bg-red-100 transition"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button"
                                    class="p-2 rounded-full text-gray-400 cursor-not-allowed opacity-50"
                                    title="Tidak dapat dihapus ({{ $item->status }})"
                                    disabled>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
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

        <!-- Pagination -->
        @if ($warkah->hasPages())
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t-2 border-blue-500">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Info Data (Kiri) -->
                <div class="text-sm font-semibold text-gray-700">
                    Results: 
                    <span class="text-blue-600 font-bold">{{ $warkah->firstItem() }}</span>
                    -
                    <span class="text-blue-600 font-bold">{{ $warkah->lastItem() }}</span>
                    of
                    <span class="text-blue-600 font-bold">{{ $warkah->total() }}</span>
                </div>

                <!-- Pagination Navigation (Tengah) -->
                <div class="flex items-center gap-2">
                    @php
                        $currentPage = $warkah->currentPage();
                        $lastPage = $warkah->lastPage();
                        $perPage = $warkah->perPage();
                    @endphp

                    <!-- Previous Button -->
                    @if ($warkah->onFirstPage())
                        <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $warkah->previousPageUrl() }}" 
                            class="w-12 h-12 flex items-center justify-center rounded-xl bg-white hover:bg-blue-50 text-gray-700 border-2 border-gray-200 hover:border-blue-500 transition-all duration-200">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    @for ($i = 1; $i <= $lastPage; $i++)
                        @if ($i == 1 || $i == $lastPage || ($i >= $currentPage - 1 && $i <= $currentPage + 1))
                            @if ($i == $currentPage)
                                <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold shadow-lg">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ $warkah->url($i) }}" 
                                    class="w-12 h-12 flex items-center justify-center rounded-xl bg-white hover:bg-blue-50 text-gray-700 font-semibold border-2 border-gray-200 hover:border-blue-500 transition-all duration-200">
                                    {{ $i }}
                                </a>
                            @endif
                        @elseif ($i == $currentPage - 2 || $i == $currentPage + 2)
                            <span class="w-12 h-12 flex items-center justify-center text-gray-400">
                                ...
                            </span>
                        @endif
                    @endfor

                    <!-- Next Button -->
                    @if ($warkah->hasMorePages())
                        <a href="{{ $warkah->nextPageUrl() }}" 
                            class="w-12 h-12 flex items-center justify-center rounded-xl bg-white hover:bg-blue-50 text-gray-700 border-2 border-gray-200 hover:border-blue-500 transition-all duration-200">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-right"></i>
                        </span>
                    @endif
                </div>

                <!-- Per Page Selector (Kanan) -->
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('warkah.index') }}" class="flex items-center">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <select name="per_page" onchange="this.form.submit()" 
                            class="px-4 py-2 bg-gray-100 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 appearance-none cursor-pointer font-semibold text-gray-700 transition-all duration-200">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Card Section (Mobile) -->
    <div class="md:hidden space-y-4">
        @forelse ($warkah as $item)
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-lg border-2 border-blue-200 p-4 transform transition-all duration-300 hover:shadow-xl">
            <div class="flex justify-between items-start mb-3">
                <h3 class="font-semibold text-gray-900 text-lg">{{ $item->kode_klasifikasi }}</h3>
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $item->kurun_waktu_berkas }}
                </span>
            </div>

            <p class="text-sm text-gray-700 mb-2"><strong>Lokasi:</strong> {{ $item->ruang_penyimpanan_rak }}</p>
            <p class="text-sm text-gray-700 mb-3"><strong>Uraian:</strong> {{ Str::limit($item->uraian_informasi_arsip, 80) ?: '-' }}</p>

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
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-lg border-2 border-gray-200 p-8 text-center">
            <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3 block"></i>
            <p class="text-gray-500 font-medium">Tidak ada data arsip</p>
            <a href="{{ route('warkah.create') }}"
                class="mt-4 inline-block px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition">
                Buat Data Pertama
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection