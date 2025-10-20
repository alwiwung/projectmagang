@extends('layouts.app')

@section('title', 'Tambah Data Arsip Vital')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Tambah Data Arsip Vital</h1>
    <p class="text-gray-500 mb-6">Isi form di bawah untuk menambahkan data arsip vital ke dalam sistem.</p>

    <form action="{{ route('warkah.store') }}" method="POST"
        class="bg-gradient-to-br from-white via-blue-50 to-white backdrop-blur-sm 
               rounded-2xl shadow-lg p-8 space-y-6 border border-blue-100 transition-all duration-300">
        @csrf

        {{-- Row 1: Kurun Waktu dan Kode Klasifikasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kurun Waktu Berkas <span class="text-red-600">*</span>
                </label>
                <input type="text" name="kurun_waktu_berkas" value="{{ old('kurun_waktu_berkas') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="Contoh: 1998–2003" required>
            </div>

            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kode Klasifikasi <span class="text-red-600">*</span>
                </label>
                <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="001.01" required>
            </div>
        </div>

        {{-- Row 2: Jenis Arsip Vital dan Nomor Item Arsip --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jenis Arsip Vital <span class="text-red-600">*</span>
                </label>
                <input type="text" name="jenis_arsip_vital" value="{{ old('jenis_arsip_vital') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="Contoh: Surat Keputusan Gubernur..." required>
            </div>

            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Item Arsip</label>
                <input type="text" name="nomor_item_arsip" value="{{ old('nomor_item_arsip') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="Contoh: SK/01/1972">
            </div>
        </div>

        {{-- Row 3: Uraian Informasi Arsip --}}
        <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Uraian Informasi Arsip <span class="text-red-600">*</span>
            </label>
            <textarea name="uraian_informasi_arsip" rows="4"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                placeholder="Deskripsi lengkap arsip..." required>{{ old('uraian_informasi_arsip') }}</textarea>
        </div>

        {{-- Row 4: Media dan Jumlah --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Media</label>
                <input type="text" name="media" value="{{ old('media') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="Kertas / Digital / Mikrofilm">
            </div>

            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                <input type="text" name="jumlah" value="{{ old('jumlah') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="1 berkas">
            </div>
        </div>

        {{-- Row 5: Aktif dan Inaktif --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jangka Simpan Aktif</label>
                <input type="text" name="aktif" value="{{ old('aktif') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="Contoh: 5 Tahun">
            </div>

            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jangka Simpan Inaktif</label>
                <input type="text" name="inaktif" value="{{ old('inaktif') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="Contoh: Permanen">
            </div>
        </div>

        {{-- Row 6: Tingkat Perkembangan dan Ruang Penyimpanan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tingkat Perkembangan</label>
                <select name="tingkat_perkembangan"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition">
                    <option value="">-- Pilih Tingkat --</option>
                    <option value="Asli" {{ old('tingkat_perkembangan') == 'Asli' ? 'selected' : '' }}>Asli</option>
                    <option value="Copy" {{ old('tingkat_perkembangan') == 'Copy' ? 'selected' : '' }}>Copy</option>
                </select>
            </div>

            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Ruang Penyimpanan / Rak</label>
                <input type="text" name="ruang_penyimpanan_rak" value="{{ old('ruang_penyimpanan_rak') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="Ruang A / Rak 1">
            </div>
        </div>

        {{-- Row 7: No Boks, No Folder, Metode Perlindungan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. Boks Definitif</label>
                <input type="text" name="no_boks_definitif" value="{{ old('no_boks_definitif') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="BX-001">
            </div>

            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. Folder</label>
                <input type="text" name="no_folder" value="{{ old('no_folder') }}"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                    placeholder="FLD-001">
            </div>

            <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Perlindungan</label>
                <select name="metode_perlindungan"
                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition">
                    <option value="">-- Pilih --</option>
                    <option value="Lemari Baja" {{ old('metode_perlindungan') == 'Lemari Baja' ? 'selected' : '' }}>Lemari Baja</option>
                    <option value="Rak Stainless" {{ old('metode_perlindungan') == 'Rak Stainless' ? 'selected' : '' }}>Rak Stainless</option>
                    <option value="Brankas" {{ old('metode_perlindungan') == 'Brankas' ? 'selected' : '' }}>Brankas</option>
                    <option value="Ruang Ber-AC" {{ old('metode_perlindungan') == 'Ruang Ber-AC' ? 'selected' : '' }}>Ruang Ber-AC</option>
                    <option value="Lainnya" {{ old('metode_perlindungan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
        </div>

        {{-- Row 8: Keterangan --}}
        <div class="bg-white/70 p-4 rounded-xl border border-gray-100 shadow-md hover:shadow-lg transition">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3"
                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-400 focus:ring focus:ring-blue-100 transition"
                placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
            <a href="{{ route('warkah.index') }}"
               class="px-5 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
               Batal
            </a>
            <button type="submit"
               class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition">
               Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
