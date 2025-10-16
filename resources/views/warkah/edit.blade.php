@extends('layouts.app')

@section('title', 'Edit Data Arsip - Sistem Arsip Warkah')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Data Arsip</h1>
    <p class="text-gray-500 mb-6">Perbarui informasi arsip dengan benar pada form di bawah ini.</p>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg border border-red-300">
            <strong>Terjadi Kesalahan:</strong>
            <ul class="list-disc pl-5 mt-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit --}}
    <form action="{{ route('warkah.update', $warkah->id) }}" method="POST"
          class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')

        {{-- Informasi Dasar --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kurun Waktu (Tahun)</label>
                <input type="text" name="kurun_waktu_berkas" value="{{ old('kurun_waktu_berkas', $warkah->kurun_waktu_berkas) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ruang Penyimpanan / Rak</label>
                <input type="text" name="ruang_penyimpanan_rak" value="{{ old('ruang_penyimpanan_rak', $warkah->ruang_penyimpanan_rak) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
        </div>

        {{-- Klasifikasi & Jenis Arsip --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Klasifikasi</label>
                <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', $warkah->kode_klasifikasi) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Arsip Vital</label>
                <input type="text" name="jenis_arsip_vital" value="{{ old('jenis_arsip_vital', $warkah->jenis_arsip_vital) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
        </div>

        {{-- Uraian Informasi --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Uraian Informasi Arsip</label>
            <textarea name="uraian_informasi_arsip" rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400">{{ old('uraian_informasi_arsip', $warkah->uraian_informasi_arsip) }}</textarea>
        </div>

        {{-- Detail Penyimpanan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Item)</label>
                <input type="text" name="jumlah" value="{{ old('jumlah', $warkah->jumlah) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tingkat Perkembangan</label>
                <input type="text" name="tingkat_perkembangan" value="{{ old('tingkat_perkembangan', $warkah->tingkat_perkembangan) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">No. Boks Definitif</label>
                <input type="text" name="no_boks_definitif" value="{{ old('no_boks_definitif', $warkah->no_boks_definitif) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">No. Folder</label>
                <input type="text" name="no_folder" value="{{ old('no_folder', $warkah->no_folder) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Metode Perlindungan</label>
                <select name="metode_perlindungan"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400 bg-white">
                    <option value="">-- Pilih Metode --</option>
                    <option value="Lemari Baja" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Lemari Baja' ? 'selected' : '' }}>Lemari Baja</option>
                    <option value="Rak Stainless" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Rak Stainless' ? 'selected' : '' }}>Rak Stainless</option>
                    <option value="Brankas" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Brankas' ? 'selected' : '' }}>Brankas</option>
                    <option value="Ruang Ber-AC" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Ruang Ber-AC' ? 'selected' : '' }}>Ruang Ber-AC</option>
                    <option value="Lainnya" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
        </div>

        {{-- Keterangan --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
            <textarea name="keterangan" rows="2"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400">{{ old('keterangan', $warkah->keterangan) }}</textarea>
        </div>

        {{-- Status Arsip --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Status Arsip</label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400 bg-white">
                <option value="Tersedia" {{ old('status', $warkah->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Dipinjam" {{ old('status', $warkah->status) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-8 flex items-center justify-between">
            <a href="{{ route('warkah.index') }}"
                class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition">
                ← Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition">
                💾 Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
