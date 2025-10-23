@extends('layouts.app')

@section('title', 'Detail Permintaan Salinan Arsip')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                            <i class="fa-solid fa-file-lines text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Detail Permintaan Salinan</h1>
                            <p class="text-gray-500 text-sm mt-1">Informasi lengkap mengenai permintaan arsip</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('permintaan.cetak', $permintaan->id) }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-print mr-2"></i> Cetak PDF
                    </a>
                    <a href="{{ route('permintaan.index') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-white text-gray-700 rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 border border-gray-200">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                    <span class="text-green-700 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-exclamation-circle text-red-600 text-xl"></i>
                    <span class="text-red-700 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
     {{-- Informasi Pemohon --}}
<div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-3">
        <h2 class="text-white font-semibold text-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-info"></i> Informasi Pemohon
        </h2>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm text-gray-700">

        {{-- Nama --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Nama Pemohon</p>
            <div class="flex items-center gap-2 mt-1">
                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600">
                    {{ strtoupper(substr($permintaan->nama_pemohon, 0, 1)) }}
                </div>
                <span class="text-gray-800 font-medium">{{ $permintaan->nama_pemohon }}</span>
            </div>
        </div>

        {{-- Instansi --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Instansi</p>
            <p class="mt-1 text-gray-800">
                <i class="fa-solid fa-building text-indigo-500 mr-2"></i>{{ $permintaan->instansi }}
            </p>
        </div>

        {{-- Nomor Identitas --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Nomor Identitas (KTP/SIM)</p>
            <p class="mt-1 text-gray-800">
                <i class="fa-solid fa-id-card text-indigo-500 mr-2"></i>{{ $permintaan->nomor_identitas ?? '-' }}
            </p>
        </div>

        {{-- Alamat Lengkap --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Alamat Lengkap</p>
            <p class="mt-1 text-gray-800">
                <i class="fa-solid fa-location-dot text-red-500 mr-2"></i>{{ $permintaan->alamat_lengkap ?? '-' }}
            </p>
        </div>

        {{-- Nomor Telepon --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Nomor Telepon</p>
            <p class="mt-1 text-gray-800">
                <i class="fa-solid fa-phone text-green-500 mr-2"></i>{{ $permintaan->nomor_telepon ?? '-' }}
            </p>
        </div>

        {{-- Email --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Email</p>
            <p class="mt-1 text-gray-800">
                <i class="fa-solid fa-envelope text-amber-500 mr-2"></i>{{ $permintaan->email ?? '-' }}
            </p>
        </div>

        {{-- Tanggal Permintaan --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Tanggal Permintaan</p>
            <p class="mt-1 text-gray-800">
                <i class="fa-solid fa-calendar text-blue-500 mr-2"></i>
                {{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->translatedFormat('d F Y') }}
            </p>
        </div>

        {{-- Jumlah Salinan --}}
        <div>
            <p class="font-semibold text-gray-500 uppercase text-xs">Jumlah Salinan</p>
            <div class="mt-1 flex items-center gap-2">
                <span class="inline-flex items-center justify-center px-2 py-1 bg-blue-100 text-blue-700 rounded-md font-semibold">
                    {{ $permintaan->jumlah_salinan }}
                </span>
                <span class="text-gray-700">Berkas</span>
            </div>
        </div>

        {{-- Catatan Tambahan --}}
        <div class="md:col-span-2">
            <p class="font-semibold text-gray-500 uppercase text-xs">Catatan Tambahan</p>
            <textarea class="w-full mt-1 border border-gray-200 rounded-lg p-2 text-gray-700 bg-gray-50" rows="2" readonly>{{ $permintaan->catatan_tambahan ?? '-' }}</textarea>
        </div>

    </div>
</div>

                {{-- Informasi Warkah --}}
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-folder-open"></i>
            Informasi Warkah
        </h2>
    </div>
    <div class="p-6 space-y-6">
        {{-- Uraian Informasi Arsip --}}
        <div>
            <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 block">Uraian Informasi Arsip</label>
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-5 border border-purple-200">
                <p class="text-gray-800 leading-relaxed">
                    {{ $permintaan->warkah->uraian_informasi_arsip ?? 'Informasi warkah tidak ditemukan' }}
                </p>
            </div>
        </div>

        {{-- Detail Tambahan --}}
        @if($permintaan->warkah)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Nomor Folder</label>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-folder text-purple-600"></i>
                        <p class="text-gray-800 font-medium">
                            {{ $permintaan->warkah->no_folder ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Ruang Penyimpanan / Rak</label>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-warehouse text-pink-600"></i>
                        <p class="text-gray-800 font-medium">
                            {{ $permintaan->warkah->ruang_penyimpanan_rak ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Kode Klasifikasi</label>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-barcode text-indigo-600"></i>
                        <p class="text-gray-800 font-medium">
                            {{ $permintaan->warkah->kode_klasifikasi ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                <i class="fa-solid fa-circle-exclamation text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500">Data tambahan warkah tidak tersedia</p>
            </div>
        @endif
    </div>
</div>


                {{-- Nota Dinas Masuk --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-envelope"></i>
                            Nota Dinas Masuk
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Nomor Nota Dinas</label>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-hashtag text-green-600"></i>
                                <p class="text-gray-800 font-medium">{{ $permintaan->nota_dinas_masuk_no ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 block">File Nota Dinas</label>
                            @if($permintaan->nota_dinas_masuk_file)
                                <div class="flex gap-3">
                                    <a href="{{ route('permintaan.lihatFile', ['id' => $permintaan->id, 'type' => 'nota']) }}" 
                                       target="_blank" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-all duration-300 font-medium shadow-sm">
                                        <i class="fa-solid fa-eye mr-2"></i> Lihat File
                                    </a>
                                    <a href="{{ route('permintaan.downloadFile', ['id' => $permintaan->id, 'type' => 'nota']) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-green-100 text-green-700 rounded-xl hover:bg-green-200 transition-all duration-300 font-medium shadow-sm">
                                        <i class="fa-solid fa-download mr-2"></i> Download
                                    </a>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                                    <i class="fa-solid fa-file-circle-xmark text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-gray-500">File tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Surat Disposisi --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-file-signature"></i>
                            Surat Disposisi
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2 block">Nomor Surat Disposisi</label>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-hashtag text-orange-600"></i>
                                <p class="text-gray-800 font-medium">{{ $permintaan->nomor_surat_disposisi ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 block">File Disposisi</label>
                            @if($permintaan->file_disposisi)
                                <div class="flex gap-3">
                                    <a href="{{ route('permintaan.lihatFile', ['id' => $permintaan->id, 'type' => 'disposisi']) }}" 
                                       target="_blank" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-all duration-300 font-medium shadow-sm">
                                        <i class="fa-solid fa-eye mr-2"></i> Lihat File
                                    </a>
                                    <a href="{{ route('permintaan.downloadFile', ['id' => $permintaan->id, 'type' => 'disposisi']) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-green-100 text-green-700 rounded-xl hover:bg-green-200 transition-all duration-300 font-medium shadow-sm">
                                        <i class="fa-solid fa-download mr-2"></i> Download
                                    </a>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                                    <i class="fa-solid fa-file-circle-xmark text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-gray-500">File tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-tasks"></i>
                            Status Permintaan
                        </h2>
                    </div>
                    <div class="p-6">
                        @php
                            $statusConfig = [
                                'Diajukan' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => 'fa-clock', 'gradient' => 'from-yellow-400 to-yellow-600'],
                                'Diterima' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'icon' => 'fa-inbox', 'gradient' => 'from-blue-400 to-blue-600'],
                                'Disposisi' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'border' => 'border-indigo-300', 'icon' => 'fa-share', 'gradient' => 'from-indigo-400 to-indigo-600'],
                                'Disalin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-300', 'icon' => 'fa-copy', 'gradient' => 'from-purple-400 to-purple-600'],
                                'Selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => 'fa-check-circle', 'gradient' => 'from-green-400 to-green-600'],
                            ];
                            $currentStatus = $statusConfig[$permintaan->status_permintaan] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => 'fa-question', 'gradient' => 'from-gray-400 to-gray-600'];
                        @endphp
                        
                        {{-- Current Status Display --}}
                        <div class="mb-6">
                            <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 block">Status Saat Ini</label>
                            <div class="bg-gradient-to-r {{ $currentStatus['gradient'] }} rounded-xl p-4 shadow-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                                        <i class="fa-solid {{ $currentStatus['icon'] }} text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-lg font-bold">{{ $permintaan->status_permintaan }}</p>
                                        <p class="text-white text-xs opacity-90">Status permintaan saat ini</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Update Status Form --}}
                        <form action="{{ route('permintaan.updateStatus', $permintaan->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 block">Ubah Status</label>
                                <select name="status_permintaan" 
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium text-gray-700"
                                        required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Diajukan" {{ $permintaan->status_permintaan == 'Diajukan' ? 'selected' : '' }}>
                                        📋 Diajukan
                                    </option>
                                    <option value="Diterima" {{ $permintaan->status_permintaan == 'Diterima' ? 'selected' : '' }}>
                                        📥 Diterima
                                    </option>
                                    <option value="Disposisi" {{ $permintaan->status_permintaan == 'Disposisi' ? 'selected' : '' }}>
                                        📤 Disposisi
                                    </option>
                                    <option value="Disalin" {{ $permintaan->status_permintaan == 'Disalin' ? 'selected' : '' }}>
                                        📑 Disalin
                                    </option>
                                    <option value="Selesai" {{ $permintaan->status_permintaan == 'Selesai' ? 'selected' : '' }}>
                                        ✅ Selesai
                                    </option>
                                </select>
                            </div>

                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:-translate-y-0.5 font-bold">
                                <i class="fa-solid fa-save mr-2"></i> Update Status
                            </button>
                        </form>

                        {{-- Timeline Status --}}
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 block">Timeline Status</label>
                            <div class="space-y-3">
                                @foreach(['Diajukan', 'Diterima', 'Disposisi', 'Disalin', 'Selesai'] as $status)
                                    @php
                                        $statusOrder = ['Diajukan' => 1, 'Diterima' => 2, 'Disposisi' => 3, 'Disalin' => 4, 'Selesai' => 5];
                                        $currentOrder = $statusOrder[$permintaan->status_permintaan] ?? 0;
                                        $thisOrder = $statusOrder[$status];
                                        $isActive = $thisOrder <= $currentOrder;
                                        $config = $statusConfig[$status];
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isActive ? 'bg-gradient-to-r ' . $config['gradient'] : 'bg-gray-200' }} transition-all duration-300">
                                            <i class="fa-solid {{ $config['icon'] }} {{ $isActive ? 'text-white' : 'text-gray-400' }} text-sm"></i>
                                        </div>
                                        <span class="text-sm {{ $isActive ? 'font-semibold text-gray-800' : 'text-gray-400' }}">{{ $status }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection