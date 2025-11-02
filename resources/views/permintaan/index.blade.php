@extends('layouts.app')

@section('title', 'Daftar Permintaan Salinan Arsip')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2"> 
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Daftar Permintaan Salinan Arsip</h1>
                            <p class="text-gray-500 text-sm mt-1">
                                Kelola dan pantau semua permintaan salinan warkah
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('permintaan.create') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Permintaan
                </a>
            </div>
        </div>

         {{-- Stats Cards --}}
        @php
        $totalPermintaan = \App\Models\Permintaan::count();
        $statusDiajukan = \App\Models\Permintaan::where('status_permintaan', 'Diajukan')->count();
        $statusDiproses = \App\Models\Permintaan::whereIn('status_permintaan', ['Diterima', 'Disposisi', 'Disalin'])->count();
        $statusSelesai = \App\Models\Permintaan::where('status_permintaan', 'Selesai')->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Permintaan</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalPermintaan }}</p>
                    </div>
                    <i class="fa-solid fa-clipboard-list text-4xl opacity-30"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Diajukan</p>
                        <p class="text-3xl font-bold mt-1">{{ $statusDiajukan }}</p>
                    </div>
                    <i class="fa-solid fa-clock text-4xl opacity-30"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Diproses</p>
                        <p class="text-3xl font-bold mt-1">{{ $statusDiproses }}</p>
                    </div>
                    <i class="fa-solid fa-spinner text-4xl opacity-30"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Selesai</p>
                        <p class="text-3xl font-bold mt-1">{{ $statusSelesai }}</p>
                    </div>
                    <i class="fa-solid fa-check-circle text-4xl opacity-30"></i>
                </div>
            </div>
        </div>

           {{-- Export Section --}}
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-lg border-2 border-green-200 p-5 mb-6 transform transition-all duration-300 hover:shadow-xl">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                {{-- Info Section --}}
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-file-export text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            Export Data Permintaan Salinan
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                <i class="fa-solid fa-database mr-1"></i>
                                {{ $permintaan->total() }} Data
                            </span>
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Export data ke format Excel untuk analisis lebih lanjut. Filter pencarian akan otomatis diterapkan.
                        </p>
                    </div>
                </div>

                {{-- Export Button --}}
                <form method="GET" action="{{ route('permintaan.export.excel') }}" class="inline-block">
                    <input type="hidden" name="nama" value="{{ request('nama') }}">
                    <input type="hidden" name="uraian" value="{{ request('uraian') }}">
                    <input type="hidden" name="tanggal_permintaan" value="{{ request('tanggal_permintaan') }}">
                    <button 
                        type="submit"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 group">
                        <i class="fa-solid fa-file-excel text-2xl mr-2 group-hover:animate-bounce"></i>
                        <span>Export Excel</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Filter & Search Section --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <form method="GET" action="{{ route('permintaan.index') }}" class="space-y-4">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fa-solid fa-filter text-blue-600 text-lg"></i>
                    <h3 class="text-lg font-semibold text-gray-800">Filter & Pencarian</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Pencarian Nama --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-user mr-1"></i> Nama Pemohon
                        </label>
                        <input type="text"
                            name="nama"
                            value="{{ request('nama') }}"
                            placeholder="Cari nama pemohon..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    {{-- Pencarian Uraian Arsip --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-file-lines mr-1"></i> Uraian Arsip
                        </label>
                        <input type="text"
                            name="uraian"
                            value="{{ request('uraian') }}"
                            placeholder="Cari uraian arsip..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    {{-- Tanggal Permintaan --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-calendar-days mr-1"></i> Tanggal Permintaan
                        </label>
                        <input type="date"
                            name="tanggal_permintaan"
                            value="{{ request('tanggal_permintaan') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg shadow hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-search mr-2"></i> Cari
                    </button>
                    <a href="{{ route('permintaan.index') }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all duration-300">
                        <i class="fa-solid fa-rotate-right mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-user mr-1"></i> Pemohon
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-building mr-1"></i> Instansi
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-file-alt mr-1"></i> Uraian Warkah
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-hashtag mr-1"></i> Jumlah
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-calendar mr-1"></i> Tanggal
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-info-circle mr-1"></i> Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fa-solid fa-cog mr-1"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($permintaan as $index => $item)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xs shadow-md">
                                    {{ $permintaan->firstItem() + $loop->index }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold shadow">
                                        {{ strtoupper(substr($item->nama_pemohon, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $item->nama_pemohon }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ $item->instansi ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-md">
                                    <p class="text-sm text-gray-900 font-medium line-clamp-2">
                                        {{ $item->warkah->uraian_informasi_arsip ?? '-' }}
                                    </p>
                                    @if($item->warkah && $item->warkah->lokasi)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fa-solid fa-map-marker-alt mr-1"></i>
                                        {{ $item->warkah->lokasi }}
                                    </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full font-bold text-sm shadow-md">
                                    {{ $item->jumlah_salinan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm">
                                    <div class="font-semibold text-gray-800">
                                        {{ $item->tanggal_permintaan ? \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') : '-' }}
                                    </div>
                                </div>
                            </td>
                               <td class="px-6 py-4 text-center">
                                @php
                                $statusConfig = [
                                'Diajukan' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                                'Diterima' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-inbox'],
                                'Disposisi' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'icon' => 'fa-share'],
                                'Disalin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'fa-copy'],
                                'Selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                                ];
                                $config = $statusConfig[$item->status_permintaan] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-question'];
                                @endphp
                                <span class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-full {{ $config['bg'] }} {{ $config['text'] }} shadow-sm">
                                    <i class="fa-solid {{ $config['icon'] }}"></i>
                                    {{ $item->status_permintaan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('permintaan.show', $item->id) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-110 shadow-sm"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('permintaan.cetak', $item->id) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition-all duration-300 transform hover:scale-110 shadow-sm"
                                        title="Cetak PDF">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </div>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                        <i class="fa-solid fa-inbox text-gray-400 text-5xl"></i>
                                    </div>
                                    <p class="text-gray-600 text-xl font-bold mb-2">Belum ada data permintaan</p>
                                    <p class="text-gray-400 text-sm mb-4">Klik tombol "Tambah Permintaan" untuk memulai</p>
                                    <a href="{{ route('permintaan.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Tambah Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($permintaan->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t-2 border-blue-500">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm font-semibold text-gray-700 bg-white px-4 py-2 rounded-lg shadow-md">
                        Menampilkan
                        <span class="text-blue-600 font-bold">{{ $permintaan->firstItem() }}</span>
                        -
                        <span class="text-blue-600 font-bold">{{ $permintaan->lastItem() }}</span>
                        dari
                        <span class="text-blue-600 font-bold">{{ $permintaan->total() }}</span>
                        data
                    </div>
                    <div>
                        {{ $permintaan->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection