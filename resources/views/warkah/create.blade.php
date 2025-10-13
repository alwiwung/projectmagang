@extends('layouts.app')

@section('title', 'Tambah Data - Sistem Arsip Warkah')

@section('header')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Data Arsip</h1>
            <p class="mt-2 text-sm text-gray-600">Isi form di bawah untuk menambahkan data arsip baru</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-6">
        <!-- Form Section -->
        <div>
            <form action="{{ route('warkah.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
                @csrf

                <!-- No. Warkah (Auto-generated) -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-hashtag mr-1"></i> No. Warkah (Otomatis)
                    </label>
                    <input 
                        type="text" 
                        value="{{ $noWarkah }}"
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed"
                        disabled>
                    <p class="text-xs text-gray-500 mt-2">Nomor warkah akan dibuat otomatis berdasarkan tahun</p>
                </div>

                <!-- Row 1: Tahun & No. SK -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tahun -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-calendar mr-1"></i> Tahun
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="tahun" 
                            value="{{ old('tahun', date('Y')) }}"
                            min="1900" 
                            max="{{ date('Y') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('tahun') border-red-500 @enderror"
                            placeholder="2024"
                            required>
                        @error('tahun')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No. SK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-file-contract mr-1"></i> No. SK
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="no_sk" 
                            value="{{ old('no_sk') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('no_sk') border-red-500 @enderror"
                            placeholder="SK-2024-001"
                            required>
                        @error('no_sk')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Nama & Lokasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-heading mr-1"></i> Nama Arsip
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama" 
                            value="{{ old('nama') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nama') border-red-500 @enderror"
                            placeholder="Nama arsip..."
                            required>
                        @error('nama')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-map-pin mr-1"></i> Lokasi
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="lokasi" 
                            value="{{ old('lokasi') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('lokasi') border-red-500 @enderror"
                            placeholder="Provinsi, Kecamatan, Kota..."
                            required>
                        @error('lokasi')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Kode Klasifikasi & Jenis Arsip Vital -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kode Klasifikasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-barcode mr-1"></i> Kode Klasifikasi
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="kode_klasifikasi" 
                            value="{{ old('kode_klasifikasi') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kode_klasifikasi') border-red-500 @enderror"
                            placeholder="001.01"
                            required>
                        @error('kode_klasifikasi')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Arsip Vital -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-star mr-1"></i> Jenis Arsip Vital
                            <span class="text-red-600">*</span>
                        </label>
                        <input
                        type="text" 
                            name="jenis_arsip_vital" 
                            value="{{ old('jenis_arsip_vital') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kode_klasifikasi') border-red-500 @enderror"
                            placeholder="Surat Keputusan Gubernur Kepala Daerah......."
                            required>
                        @error('jenis_arsip_vital')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Uraian Informasi Arsip (Full Width) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-align-left mr-1"></i> Uraian Informasi Arsip
                        <span class="text-red-600">*</span>
                    </label>
                    <textarea 
                        name="uraian_informasi_arsip" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('uraian_informasi_arsip') border-red-500 @enderror"
                        placeholder="Deskripsi lengkap arsip..."
                        required>{{ old('uraian_informasi_arsip') }}</textarea>
                    @error('uraian_informasi_arsip')
                        <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row 5: Jumlah & Tingkat Perkembangan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-box mr-1"></i> Jumlah
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="jumlah" 
                            value="{{ old('jumlah') }}"
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('jumlah') border-red-500 @enderror"
                            placeholder="1"
                            required>
                        @error('jumlah')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tingkat Perkembangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-chart-line mr-1"></i> Tingkat Perkembangan
                            <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="tingkat_perkembangan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('tingkat_perkembangan') border-red-500 @enderror"
                            required>
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="Aktif" {{ old('tingkat_perkembangan') == 'Asli' ? 'selected' : '' }}>Asli</option>
                        </select>
                        @error('tingkat_perkembangan')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 6: Ruang Penyimpanan & No. Boks -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Ruang Penyimpanan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-warehouse mr-1"></i> Ruang Penyimpanan/Rak
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="ruang_penyimpanan_rak" 
                            value="{{ old('ruang_penyimpanan_rak') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('ruang_penyimpanan_rak') border-red-500 @enderror"
                            placeholder="Ruang A - Rak 1"
                            required>
                        @error('ruang_penyimpanan_rak')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No. Boks Definitif -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-box mr-1"></i> No. Boks Definitif
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="no_boks_definitif" 
                            value="{{ old('no_boks_definitif') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('no_boks_definitif') border-red-500 @enderror"
                            placeholder="BX-001"
                            required>
                        @error('no_boks_definitif')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 7: No. Folder & Metode Perlindungan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- No. Folder -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-folder mr-1"></i> No. Folder
                            <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="no_folder" 
                            value="{{ old('no_folder') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('no_folder') border-red-500 @enderror"
                            placeholder="FLD-001"
                            required>
                        @error('no_folder')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Metode Perlindungan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-shield mr-1"></i> Metode Perlindungan
                            <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="metode_perlindungan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('metode_perlindungan') border-red-500 @enderror"
                            required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Lemari Baja" {{ old('metode_perlindungan') == 'Lemari Baja' ? 'selected' : '' }}>Lemari Baja</option>
                            <option value="Rak Stainless" {{ old('metode_perlindungan') == 'Rak Stainless' ? 'selected' : '' }}>Rak Stainless</option>
                            <option value="Brankas" {{ old('metode_perlindungan') == 'Brankas' ? 'selected' : '' }}>Brankas</option>
                            <option value="Ruang Ber-AC" {{ old('metode_perlindungan') == 'Ruang Ber-AC' ? 'selected' : '' }}>Ruang Ber-AC</option>
                            <option value="Lainnya" {{ old('metode_perlindungan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('metode_perlindungan')
                            <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 8: Keterangan (Full Width) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-note-sticky mr-1"></i> Keterangan
                    </label>
                    <textarea 
                        name="keterangan" 
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('keterangan') border-red-500 @enderror"
                        placeholder="Keterangan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-600 text-sm mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('warkah.index') }}" 
                       class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition text-center">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Batal
                    </a>
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection