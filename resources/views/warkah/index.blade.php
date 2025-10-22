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
                value="{{ request('keyword') }}"
                placeholder="Cari ID, uraian informasi atau kode klasifikasi..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        <!-- Filter Tahun (ganti name ke 'kurun_waktu_berkas') -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fa-solid fa-calendar mr-1"></i> Tahun
                </label>
                <select 
                    name="kurun_waktu_berkas"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('kurun_waktu_berkas') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Lokasi (ganti name ke 'ruang_penyimpanan_rak') -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fa-solid fa-map-pin mr-1"></i> Ruang Penyimpanan / Rak
                </label>
                <select 
                    name="ruang_penyimpanan_rak"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
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
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                <i class="fa-solid fa-search mr-1"></i> Cari
            </button>
            <a href="{{ route('warkah.index') }}"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
                <i class="fa-solid fa-rotate-left mr-1"></i> Reset
            </a>
        </div>
    </form>
 {{-- <thead class="bg-blue-600 text-white"> --}}
    <!-- Table Section -->
   <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-separate border-spacing-y-1">
            <thead class="bg-blue-50 border-b border-gray-200">
                <tr>
                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider">No</th>
                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider">ID</th>
                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-calendar mr-1"></i> Kurun Waktu
                    </th>
                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-tag mr-1"></i> Kode Klasifikasi
                    </th>
                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-map-pin mr-1"></i> Ruang Penyimpanan / Rak
                    </th>
                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-note-sticky mr-1"></i> Uraian Informasi Arsip
                    </th>
                    <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-cog mr-1"></i> Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse ($warkah as $index => $item)
                    <tr class="bg-white hover:bg-blue-50 transition">
                        <td class="text-center text-sm font-medium px-4 py-3 align-middle">
                            {{ ($warkah->currentPage() - 1) * $warkah->perPage() + $loop->iteration }}
                        </td>
                        <td class="text-center text-sm font-medium px-4 py-3 align-middle">
                            #{{ $item->id }}
                        </td>
                        <td class="text-center text-sm px-4 py-3 align-middle">
                            {{ $item->kurun_waktu_berkas ?? '-' }}
                        </td>
                        <td class="text-center text-sm px-4 py-3 align-middle">
                            {{ $item->kode_klasifikasi ?? '-' }}
                        </td>
                        <td class="text-center text-sm px-4 py-3 align-middle">
                            {{ $item->ruang_penyimpanan_rak ?? '-' }}
                        </td>
                       <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs" title="{{ $item->uraian_informasi_arsip }}">
                        {{ Str::limit($item->uraian_informasi_arsip, 60) ?: '-' }}
                    </td>

                        <td class="text-center px-4 py-3 align-middle">
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
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
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
                    <h3 class="font-semibold text-gray-900 text-lg">{{ $item->kode_klasifikasi }}</h3>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $item->kurun_waktu_berkas }}
                    </span>
                </div>

                <p class="text-sm text-gray-700 mb-2"><strong>Lokasi:</strong> {{ $item->lokasi }}</p>
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

{{-- Import / Export Excel --}}
<div class="mt-6 bg-white shadow-md rounded-lg p-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <form action="{{ route('warkah.export') }}" method="GET" class="flex items-center">
            {{-- Kirim semua filter yang aktif agar export sesuai hasil pencarian --}}
            <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            <input type="hidden" name="kurun_waktu_berkas" value="{{ request('kurun_waktu_berkas') }}">
            <input type="hidden" name="ruang_penyimpanan_rak" value="{{ request('ruang_penyimpanan_rak') }}">
            <input type="hidden" name="kode_klasifikasi" value="{{ request('kode_klasifikasi') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">

            <button type="submit" 
                class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-md shadow-sm transition">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </form>

        {{-- Bagian Import (tidak diubah) --}}
        <form action="{{ route('warkah.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <label class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md cursor-pointer shadow-sm transition">
                <i class="fas fa-file-import"></i> Import Excel
                <input type="file" name="file" accept=".xlsx,.xls" required class="hidden" onchange="this.form.submit()">
            </label>
        </form>
    </div>

    <div class="text-sm text-gray-500 italic">
        <i class="fas fa-info-circle"></i> Format file harus .xlsx atau .xls
    </div>

    
</div>

@endsection
