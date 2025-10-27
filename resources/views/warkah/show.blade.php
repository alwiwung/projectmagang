@extends('layouts.app')

@section('title', 'Detail Arsip - Sistem Arsip Warkah')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Detail Data Warkah</h1>
            <p class="mt-2 text-sm text-gray-600">Informasi lengkap data arsip</p>
        </div>
        <div class="flex gap-2 mt-4 sm:mt-0">
            <a href="{{ route('warkah.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">

        <!-- Informasi Arsip -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fa-solid fa-layer-group text-blue-600 mr-2"></i> Informasi Arsip
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Kode Klasifikasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-barcode mr-1"></i> Kode Klasifikasi
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $warkah->kode_klasifikasi }}
                        </span>
                    </div>
                </div>

                <!-- Jenis Arsip Vital -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-star mr-1"></i> Jenis Arsip Vital
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->jenis_arsip_vital }}</p>
                    </div>
                </div>

                <!-- Nomor Item Arsip -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-hashtag mr-1"></i> Nomor Item Arsip
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->nomor_item_arsip ?? '-' }}</p>
                    </div>
                </div>

                <!-- Uraian Informasi Arsip -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-align-left mr-1"></i> Uraian Informasi Arsip
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-wrap leading-relaxed">{{ $warkah->uraian_informasi_arsip }}</p>
                    </div>
                </div>

                <!-- Kurun Waktu Berkas -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-calendar mr-1"></i> Kurun Waktu Berkas
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->kurun_waktu_berkas }}</p>
                    </div>
                </div>

                <!-- Media -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-photo-film mr-1"></i> Media
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->media ?? '-' }}</p>
                    </div>
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-box mr-1"></i> Jumlah
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium text-lg">{{ $warkah->jumlah }}</p>
                    </div>
                </div>

                <!-- Jangka Simpan Aktif -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-clock mr-1"></i> Jangka Simpan Aktif
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->aktif ?? '-' }}</p>
                    </div>
                </div>

                <!-- Jangka Simpan Inaktif -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-hourglass-half mr-1"></i> Jangka Simpan Inaktif
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->inaktif ?? '-' }}</p>
                    </div>
                </div>

                <!-- Tingkat Perkembangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-chart-line mr-1"></i> Tingkat Perkembangan
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($warkah->tingkat_perkembangan === 'Aktif') bg-green-100 text-green-800
                            @elseif($warkah->tingkat_perkembangan === 'Semi Aktif') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $warkah->tingkat_perkembangan }}
                        </span>
                    </div>
                </div>

                <!-- Ruang Penyimpanan / Rak -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-map-pin mr-1"></i> Ruang Penyimpanan / Rak
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->ruang_penyimpanan_rak }}</p>
                    </div>
                </div>

                <!-- No. Boks Definitif -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-box-archive mr-1"></i> No. Boks Definitif
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->no_boks_definitif }}</p>
                    </div>
                </div>

                <!-- No. Folder -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-folder mr-1"></i> No. Folder
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <p class="text-gray-900 font-medium">{{ $warkah->no_folder }}</p>
                    </div>
                </div>

                <!-- Metode Perlindungan -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fa-solid fa-shield mr-1"></i> Metode Perlindungan
                    </label>
                    <div class="bg-gray-50 px-4 py-3 rounded-lg">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            {{ $warkah->metode_perlindungan }}
                        </span>
                    </div>
                </div>

                <!-- Keterangan -->
                @if($warkah->keterangan)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            <i class="fa-solid fa-note-sticky mr-1"></i> Keterangan
                        </label>
                        <div class="bg-gray-50 px-4 py-3 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $warkah->keterangan }}</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fa-solid fa-circle-info text-blue-600 mr-2"></i> Informasi
            </h3>

            <div class="space-y-4">
                <div class="pb-4 border-b border-gray-200">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($warkah->status === 'Tersedia') bg-green-100 text-green-800
                        @elseif($warkah->status === 'Dipinjam') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $warkah->status }}
                    </span>
                </div>

                <div class="pb-4 border-b border-gray-200">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Dibuat Oleh</p>
                    <p class="text-sm text-gray-900">{{ $warkah->createdBy->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500">{{ $warkah->created_at->format('d M Y H:i') }}</p>
                </div>

                @if($warkah->updated_at && $warkah->updated_at->ne($warkah->created_at))
                    <div class="pb-4 border-b border-gray-200">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Terakhir Diperbarui</p>
                        <p class="text-sm text-gray-900">{{ $warkah->updatedBy->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500">{{ $warkah->updated_at->format('d M Y H:i') }}</p>
                    </div>
                @endif

                <div class="pt-4">
                    <a href="{{ route('warkah.edit', $warkah->id) }}" 
                       class="w-full block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <i class="fa-solid fa-pencil mr-1"></i> Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
