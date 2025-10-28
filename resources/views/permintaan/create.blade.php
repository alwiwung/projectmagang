@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Tambah Permintaan Salinan Arsip')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-10 px-4">
    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Permintaan Salinan Arsip</h1>
            <p class="text-gray-600">Lengkapi formulir di bawah untuk mengajukan permintaan salinan arsip</p>
        </div>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div id="alert-success" class="mb-6 p-4 rounded-xl bg-green-50 border-l-4 border-green-500 shadow-sm transition-all duration-500">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    const alert = document.getElementById('alert-success');
                    if(alert) {
                        alert.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 4000);
            </script>
        @endif

        {{-- Main Form Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <form action="{{ route('permintaan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-8 space-y-8">
                    {{-- Section 1: Pilih Warkah --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">1</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Pilih Warkah</h3>
                        </div>
                        
                        <label for="id_warkah" class="block text-sm font-medium text-gray-700 mb-2">
   
<label for="id_warkah" class="block text-sm font-medium text-gray-700 mb-2">
    🗂️ Dokumen Warkah <span class="text-red-500">*</span>
</label>
<div 
    x-data="{
        open: false,
        search: '',
        loading: false,
        selected: null,
        warkahList: @js($warkah->toArray()),

        getStatusClass(status) {
            switch (status?.toLowerCase()) {
                case 'tersedia':
                    return 'bg-green-100 text-green-700 border-green-200';
                case 'dipinjam':
                    return 'bg-red-100 text-red-700 border-red-200';
                case 'proses':
                    return 'bg-yellow-100 text-yellow-700 border-yellow-200';
                case 'rusak':
                    return 'bg-gray-200 text-gray-700 border-gray-300';
                default:
                    return 'bg-blue-100 text-blue-700 border-blue-200';
            }
        },

        searchWarkah() {
            this.open = true; // auto buka saat ketik
            const all = @js($warkah->toArray());
            if (this.search.trim() === '') {
                this.warkahList = all;
                return;
            }

            const searchLower = this.search.toLowerCase();
            this.warkahList = all.filter(item =>
                item.uraian_informasi_arsip?.toLowerCase().includes(searchLower) ||
                item.kode_klasifikasi?.toLowerCase().includes(searchLower) ||
                item.ruang_penyimpanan_rak?.toLowerCase().includes(searchLower) ||
                item.kurun_waktu_berkas?.toLowerCase().includes(searchLower)
            );
        },

        selectItem(item) {
            this.selected = item;
            this.open = false;
            this.search = '';
        },

        clearSelection() {
            this.selected = null;
            this.search = '';
        }
    }" 
    class="mb-6 relative"
>
    <!-- Label -->
    <label 
        class="block text-sm font-semibold text-gray-700 mb-2 flex justify-between items-center cursor-pointer"
        @click="open = true"
    >
        <span>🗂️ Cari dan Pilih Warkah <span class="text-red-500">*</span></span>
        <!-- Status dinamis di ujung kanan label -->
        <template x-if="selected">
            <span :class="'text-xs font-semibold px-2 py-1 rounded-full border ' + getStatusClass(selected.status)">
                <span x-text="selected.status || 'Tidak diketahui'"></span>
            </span>
        </template>
    </label>

    <!-- Input Pencarian -->
    <div class="relative" @click.away="open = false">
        <div 
            class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg hover:border-blue-500 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100 transition-all"
            @click="open = true"
        >
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <input
                        type="text"
                        x-model="search"
                        @input.debounce.300ms="searchWarkah()"
                        @focus="open = true"
                        placeholder="Ketik untuk mencari warkah (kode, uraian, lokasi, tahun)..."
                        class="w-full outline-none text-gray-900 placeholder-gray-400 bg-transparent"
                    />
                </div>
                <div class="flex items-center space-x-2">
                    <template x-if="selected">
                        <button
                            type="button"
                            @click.stop="clearSelection()"
                            class="text-gray-400 hover:text-red-500 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </template>
                    <svg 
                        class="w-5 h-5 text-gray-400 transition-transform" 
                        :class="open ? 'rotate-180' : ''" 
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <!-- Selected Item -->
            <template x-if="selected">
                <div class="mt-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 break-words" x-text="selected.uraian_informasi_arsip"></p>
                        <p class="text-xs text-gray-600 mt-1 flex flex-wrap gap-2">
                            <span x-text="selected.kode_klasifikasi || '-'"></span>
                            <span x-text="selected.ruang_penyimpanan_rak"></span>
                            <span x-text="selected.kurun_waktu_berkas"></span>
                        </p>
                    </div>
                    <div class="ml-3 flex-shrink-0">
                        <span :class="'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border ' + getStatusClass(selected.status)">
                            <span x-text="selected.status || 'Tidak diketahui'"></span>
                        </span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Dropdown -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1"
            class="absolute z-50 mt-2 w-full bg-white border-2 border-gray-200 rounded-xl shadow-2xl max-h-96 overflow-y-auto"
        >
            <template x-if="warkahList.length > 0">
                <div>
                    <template x-for="item in warkahList" :key="item.id">
                        <div
                            @click="selectItem(item)"
                            class="px-5 py-4 hover:bg-blue-50 cursor-pointer border-b border-gray-100 flex justify-between items-start transition-all duration-150"
                        >
                            <div>
                                <p class="text-sm font-medium text-gray-900 leading-snug" x-text="item.uraian_informasi_arsip"></p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="px-2 py-1 text-xs bg-gray-100 rounded-md border border-gray-200" x-text="item.kode_klasifikasi || '-'"></span>
                                    <template x-if="item.ruang_penyimpanan_rak">
                                        <span class="px-2 py-1 text-xs bg-purple-50 text-purple-700 rounded-md border border-purple-200" x-text="item.ruang_penyimpanan_rak"></span>
                                    </template>
                                    <template x-if="item.kurun_waktu_berkas">
                                        <span class="px-2 py-1 text-xs bg-amber-50 text-amber-700 rounded-md border border-amber-200" x-text="item.kurun_waktu_berkas"></span>
                                    </template>
                                </div>
                            </div>
                            <div class="ml-3 flex-shrink-0">
                                <span :class="'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border ' + getStatusClass(item.status)">
                                    <span x-text="item.status || 'Tidak diketahui'"></span>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <template x-if="warkahList.length === 0">
                <div class="p-6 text-center text-gray-500">Tidak ada warkah ditemukan.</div>
            </template>
        </div>
    </div>

    <!-- Hidden input -->
    <input type="hidden" name="id_warkah" :value="selected ? selected.id : ''" required>

    <p class="mt-2 text-xs text-gray-600 flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Ketik untuk mencari berdasarkan kode klasifikasi, uraian informasi, lokasi, tahun, atau nomor item arsip.
    </p>
</div>



                  {{-- Section 2: Data Pemohon --}}
<div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
    <div class="flex items-center mb-4">
        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
            <span class="text-white text-sm font-bold">2</span>
        </div>
        <h3 class="text-lg font-semibold text-gray-800">Data Pemohon</h3>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Nama Pemohon --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                👤 Nama Pemohon <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_pemohon" placeholder="Masukkan nama lengkap"
                value="{{ old('nama_pemohon') }}" required
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('nama_pemohon') border-red-500 @enderror">
            @error('nama_pemohon')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Instansi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                🏢 Instansi
            </label>
            <input type="text" name="instansi" placeholder="Masukkan nama instansi"
                value="{{ old('instansi') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('instansi') border-red-500 @enderror">
            @error('instansi')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor Identitas --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                🪪 Nomor Identitas (KTP/SIM)
            </label>
            <input type="text" name="nomor_identitas" placeholder="Masukkan nomor identitas"
                value="{{ old('nomor_identitas') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('nomor_identitas') border-red-500 @enderror">
            @error('nomor_identitas')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor Telepon --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                📞 Nomor Telepon
            </label>
            <input type="text" name="nomor_telepon" placeholder="Masukkan nomor telepon aktif"
                value="{{ old('nomor_telepon') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('nomor_telepon') border-red-500 @enderror">
            @error('nomor_telepon')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ✉️ Alamat Email
            </label>
            <input type="email" name="email" placeholder="Masukkan alamat email aktif"
                value="{{ old('email') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat Lengkap --}}
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                🏠 Alamat Lengkap
            </label>
            <textarea name="alamat_lengkap" rows="3" placeholder="Masukkan alamat lengkap sesuai identitas"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none">{{ old('alamat_lengkap') }}</textarea>
            @error('alamat_lengkap')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


                    {{-- Section 3: Detail Permintaan --}}
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 border border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">3</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Detail Permintaan</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    📅 Tanggal Permintaan
                                </label>
                                <input type="date" name="tanggal_permintaan" value="{{ old('tanggal_permintaan') }}"
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('tanggal_permintaan') border-red-500 @enderror">
                                @error('tanggal_permintaan')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    📄 Jumlah Salinan
                                </label>
                                <input type="number" name="jumlah_salinan" min="1"
                                    value="{{ old('jumlah_salinan', 1) }}"
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('jumlah_salinan') border-red-500 @enderror">
                                @error('jumlah_salinan')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                📝 Catatan Tambahan
                            </label>
                            <textarea name="catatan_tambahan" rows="3" placeholder="Tambahkan catatan jika diperlukan..."
                                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none">{{ old('catatan_tambahan') }}</textarea>
                        </div>
                    </div>

                    {{-- Section 4: Upload Dokumen --}}
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">4</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Dokumen Pendukung</h3>
                        </div>
                        
                        {{-- Nota Dinas --}}
                        <div class="mb-6 p-5 bg-white rounded-lg border border-yellow-300">
                            <h4 class="font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Nota Dinas
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📎 Nomor Nota Dinas</label>
                                    <input type="text" name="nota_dinas_masuk_no" value="{{ old('nota_dinas_masuk_no') }}"
                                        placeholder="Contoh: ND/001/2024"
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📁 File Nota Dinas</label>
                                    <input type="file" name="nota_dinas_masuk_file" id="nota_dinas_masuk_file"
                                        accept=".pdf,.doc,.docx" class="hidden" onchange="showFileName('nota_dinas_masuk_file', 'file-name-nota')">
                                    <label for="nota_dinas_masuk_file"
                                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-medium rounded-lg cursor-pointer transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Pilih File
                                    </label>
                                    <p id="file-name-nota" class="text-sm text-gray-500 mt-2 italic">Belum ada file dipilih</p>
                                    @error('nota_dinas_masuk_file')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Surat Disposisi --}}
                        <div class="p-5 bg-white rounded-lg border border-orange-300">
                            <h4 class="font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Surat Disposisi
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📎 Nomor Surat Disposisi</label>
                                    <input type="text" name="nomor_surat_disposisi" value="{{ old('nomor_surat_disposisi') }}"
                                        placeholder="Contoh: SD/001/2024"
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📁 File Disposisi</label>
                                    <input type="file" name="file_disposisi" id="file_disposisi"
                                        accept=".pdf,.doc,.docx" class="hidden" onchange="showFileName('file_disposisi', 'file-name-disposisi')">
                                    <label for="file_disposisi"
                                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-medium rounded-lg cursor-pointer transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Pilih File
                                    </label>
                                    <p id="file-name-disposisi" class="text-sm text-gray-500 mt-2 italic">Belum ada file dipilih</p>
                                    @error('file_disposisi')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 5: Status --}}
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">5</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Status Permintaan</h3>
                        </div>
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            📊 Pilih Status
                        </label>
                        <select name="status"
                            class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <option value="Diajukan" {{ old('status') == 'Diajukan' ? 'selected' : '' }}>🟡 Diajukan</option>
                            <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>🟢 Diterima</option>
                            {{-- <option value="Disposisi" {{ old('status') == 'Disposisi' ? 'selected' : '' }}>🔵 Disposisi</option> --}}
                            <option value="Disalin" {{ old('status') == 'Disalin' ? 'selected' : '' }}>🟣 Disalin</option>
                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 flex flex-col sm:flex-row justify-between items-center gap-4 border-t">
                    <a href="{{ route('permintaan.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-lg shadow-md border-2 border-gray-300 transition-all hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>

                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
function showFileName(inputId, labelId) {
    const input = document.getElementById(inputId);
    const label = document.getElementById(labelId);

    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024).toFixed(2);
        
        label.innerHTML = `
            <span class="inline-flex items-center">
                <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-green-700 font-medium">${fileName}</span>
                <span class="text-gray-500 ml-2">(${fileSize} KB)</span>
            </span>
        `;
        label.classList.remove('text-gray-500', 'italic');
        label.classList.add('text-green-700');
    } else {
        label.textContent = 'Belum ada file dipilih';
        label.classList.remove('text-green-700');
        label.classList.add('text-gray-500', 'italic');
    }
}

// Auto scroll ke error
document.addEventListener('DOMContentLoaded', function() {
    const firstError = document.querySelector('.border-red-500');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection