@extends('layouts.app')

@section('title', 'Tambah Permintaan Salinan Arsip')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-md mt-8 border border-gray-200">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📄 Tambah Permintaan Salinan Arsip</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('permintaan.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Pilihan Warkah --}}
        <div>
            <label for="warkah_id" class="block text-gray-700 font-medium mb-1">Pilih Warkah</label>
            <select id="warkah_id" name="warkah_id" required 
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Warkah --</option>
                @foreach ($warkah as $item)
                    <option value="{{ $item->id }}" data-uraian="{{ $item->uraian_informasi_arsip }}">
                        {{ $item->uraian_informasi_arsip }}
                    </option>
                @endforeach
            </select>
            @error('warkah_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Preview Uraian Arsip Otomatis --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Uraian Informasi Arsip</label>
            <input type="text" id="uraian_informasi_arsip" name="uraian_informasi_arsip" 
                class="w-full p-3 border border-gray-200 rounded-lg bg-gray-100 text-gray-700" 
                placeholder="Otomatis terisi saat warkah dipilih" readonly>
        </div>

        {{-- Data Pemohon --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Nama Pemohon</label>
                <input type="text" name="pemohon" value="{{ old('pemohon') }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                @error('pemohon')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Instansi</label>
                <input type="text" name="instansi" value="{{ old('instansi') }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        {{-- Tanggal dan Jumlah --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Tanggal Permintaan</label>
                <input type="date" name="tanggal_permintaan" value="{{ old('tanggal_permintaan') }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Jumlah Salinan</label>
                <input type="number" name="jumlah_salinan" value="{{ old('jumlah_salinan', 1) }}" min="1"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        {{-- Catatan --}}
        <div>
            <label class="block text-gray-700 font-medium mb-1">Catatan Tambahan</label>
            <textarea name="catatan" rows="3" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Tambahkan catatan jika ada">{{ old('catatan') }}</textarea>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('permintaan.index') }}" 
                class="inline-block px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                ← Kembali
            </a>

            <button type="submit"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                💾 Simpan Permintaan
            </button>
        </div>
    </form>
</div>

{{-- Script Dinamis --}}
<script>
    const warkahSelect = document.getElementById('warkah_id');
    const uraianInput = document.getElementById('uraian_informasi_arsip');

    warkahSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const uraian = selectedOption.getAttribute('data-uraian');
        uraianInput.value = uraian || '';
    });
</script>
@endsection
