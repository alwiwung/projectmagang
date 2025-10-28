@extends('layouts.app')

@section('title', 'Edit Data Arsip - Sistem Arsip Warkah')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Data Warkah</h1>
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

    {{-- Jika status Dipinjam --}}
    @if ($warkah->status == 'Dipinjam')
        <div class="mb-6 p-4 bg-yellow-100 border border-yellow-300 rounded-lg text-yellow-800">
            ⚠️ Arsip ini sedang <strong>Dipinjam</strong>. Data tidak dapat diubah sampai arsip dikembalikan.
        </div>
    @endif

    <form action="{{ route('warkah.update', $warkah->id) }}" method="POST"
          class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')

        @php
            $disabled = $warkah->status == 'Dipinjam' ? 'disabled' : '';
            $readonly = $warkah->status == 'Dipinjam' ? 'readonly' : '';
        @endphp

        {{-- 1. Kode Klasifikasi & Jenis Arsip Vital --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Klasifikasi</label>
                <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', $warkah->kode_klasifikasi) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" 
                    placeholder="Contoh : HP.02" required/>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Arsip Vital</label>
                <input type="text" name="jenis_arsip_vital" value="{{ old('jenis_arsip_vital', $warkah->jenis_arsip_vital) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: Surat Keputusan Gubernur..." required/>
            </div>
        </div>

        {{-- 2. Nomor Item Arsip --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Item Arsip</label>
            <input type="text" name="nomor_item_arsip" value="{{ old('nomor_item_arsip', $warkah->nomor_item_arsip) }}"
                {{ $readonly }}
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400" 
                placeholder="Contoh : 001/HP/2001"/>
        </div>

        {{-- 3. Uraian Informasi Arsip --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Uraian Informasi Arsip</label>
            <textarea name="uraian_informasi_arsip" rows="3"
                {{ $readonly }}
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                placeholder="Contoh: Surat Keputusan Gubernur..." required>{{ old('uraian_informasi_arsip', $warkah->uraian_informasi_arsip) }}</textarea>
        </div>

        {{-- 4. Kurun Waktu Berkas & Media --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kurun Waktu Berkas</label>
                <input type="text" name="kurun_waktu_berkas" value="{{ old('kurun_waktu_berkas', $warkah->kurun_waktu_berkas) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: 1998–2003" required/>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Media</label>
                <input type="text" name="media" value="{{ old('media', $warkah->media) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: Kertas, Digital, Mikrofilm"/>
            </div>
        </div>

        {{-- 5. Jumlah, Jangka Simpan Aktif & Inaktif --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Item)</label>
                <input type="text" name="jumlah" value="{{ old('jumlah', $warkah->jumlah) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: 1 Berkas"/>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jangka Simpan Aktif</label>
                <input type="text" name="aktif" value="{{ old('aktif', $warkah->aktif) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: 5 Tahun"/>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jangka Simpan Inaktif</label>
                <input type="text" name="inaktif" value="{{ old('inaktif', $warkah->inaktif) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: Permanen"/>
            </div>
        </div>

        {{-- 6. Tingkat Perkembangan & Ruang Penyimpanan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tingkat Perkembangan</label>
                <input type="text" name="tingkat_perkembangan" value="{{ old('tingkat_perkembangan', $warkah->tingkat_perkembangan) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: Asli"/>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ruang Penyimpanan / Rak</label>
                <input type="text" name="ruang_penyimpanan_rak" value="{{ old('ruang_penyimpanan_rak', $warkah->ruang_penyimpanan_rak) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: Rak 1 Baris 1"/>
            </div>
        </div>

        {{-- 7. No. Boks & No. Folder --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">No. Boks Definitif</label>
                <input type="text" name="no_boks_definitif" value="{{ old('no_boks_definitif', $warkah->no_boks_definitif) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: BX-001"/>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">No. Folder</label>
                <input type="text" name="no_folder" value="{{ old('no_folder', $warkah->no_folder) }}"
                    {{ $readonly }}
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                    placeholder="Contoh: B / 099 - 158 / 1972"/>
            </div>
        </div>

        {{-- 8. Metode Perlindungan --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Metode Perlindungan</label>
            <select name="metode_perlindungan" {{ $disabled }}
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400 bg-white">
                <option value="">-- Pilih Metode --</option>
                <option value="Lemari Baja" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Lemari Baja' ? 'selected' : '' }}>CCTV</option>
                <option value="Rak Stainless" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Rak Stainless' ? 'selected' : '' }}>Rak Stainless</option>
                <option value="Brankas" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Brankas' ? 'selected' : '' }}>Brankas</option>
                <option value="Ruang Ber-AC" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Ruang Ber-AC' ? 'selected' : '' }}>Ruang Ber-AC</option>
                <option value="Lainnya" {{ old('metode_perlindungan', $warkah->metode_perlindungan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        {{-- 9. Keterangan --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
            <textarea name="keterangan" rows="2"
                {{ $readonly }}
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-400"
                placeholder="Contoh: Lengkap">{{ old('keterangan', $warkah->keterangan) }}</textarea>
        </div>

        {{-- 10. Status Arsip --}}
        <div class="mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Status Arsip</label>
            <input type="text" value="{{ $warkah->status }}" readonly
                class="w-full border border-gray-300 bg-gray-100 rounded-lg px-4 py-2.5 text-gray-700 cursor-not-allowed" />
            <input type="hidden" name="status" value="{{ $warkah->status }}">
            <p class="text-xs text-gray-500 mt-1">Status arsip hanya dapat berubah melalui fitur peminjaman/pengembalian.</p>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
            <a href="{{ route('warkah.index') }}"
                class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition">
                Batal
            </a>

            @if ($warkah->status != 'Dipinjam')
                <button type="submit"
                    class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition">
                    Edit Data Warkah
                </button>
            @endif
        </div>
    </form>
</div>
@endsection
