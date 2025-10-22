@extends('layouts.app')
@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-2">
        📄 Detail Permintaan Salinan
    </h2>

    {{-- Informasi Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Informasi Pemohon -->
        <div class="bg-white shadow rounded-lg p-6 border">
            <h3 class="text-lg font-semibold text-blue-600 mb-4">🧑 Informasi Pemohon</h3>
            <div class="space-y-3 text-gray-700 text-sm">
                <div class="flex justify-between"><span class="font-medium">Nama Pemohon:</span> <span>{{ $permintaan->pemohon }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Instansi:</span> <span>{{ $permintaan->instansi }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Jumlah Salinan:</span> <span>{{ $permintaan->jumlah_salinan }}</span></div>
                <div class="flex justify-between"><span class="font-medium">Catatan:</span> <span>{{ $permintaan->catatan ?? '-' }}</span></div>
            </div>
        </div>

        <!-- Informasi Waktu & Status -->
        <div class="bg-white shadow rounded-lg p-6 border">
            <h3 class="text-lg font-semibold text-blue-600 mb-4">⏰ Informasi Waktu</h3>
            <div class="space-y-3 text-gray-700 text-sm">
                <div class="flex justify-between">
                    <span class="font-medium">Tanggal Permintaan:</span>
                    <span>{{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d M Y') }}</span>
                </div>

                {{-- Status --}}
                <div class="flex justify-between items-center">
                    <span class="font-medium">Status:</span>
                    <span class="px-3 py-1 rounded-full text-white text-xs font-semibold
                        @if($permintaan->status == 'selesai') bg-green-500 
                        @elseif($permintaan->status == 'diproses') bg-yellow-500 
                        @else bg-gray-500 
                        @endif">
                        {{ ucfirst($permintaan->status) }}
                    </span>
                </div>

                            {{-- Barcode --}}


<div>
    {!! QrCode::size(200)->generate($permintaan->nomor_perm ?? 'Data tidak tersedia') !!}
</div>



            </div>
        </div>
    </div>

    {{-- Informasi Arsip --}}
    <div class="bg-white shadow rounded-lg p-6 border mb-8">
        <h3 class="text-lg font-semibold text-blue-600 mb-4">📁 Informasi Arsip</h3>
        <div class="space-y-3 text-gray-700 text-sm">
            <div class="flex justify-between"><span class="font-medium">Nomor Item Arsip:</span> <span>{{ $permintaan->warkah->nomor_item_arsip ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="font-medium">Kode Klasifikasi:</span> <span>{{ $permintaan->warkah->kode_klasifikasi ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="font-medium">Kurun Waktu Berkas:</span> <span>{{ $permintaan->warkah->kurun_waktu_berkas ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="font-medium">Uraian Informasi Arsip:</span> <span class="text-right">{{ $permintaan->uraian_informasi_arsip }}</span></div>
            <div class="flex justify-between"><span class="font-medium">Lokasi Penyimpanan:</span> 
                <span>Rak {{ $permintaan->warkah->rak ?? '-' }}, Baris {{ $permintaan->warkah->baris ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Status Box --}}
    <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded shadow mb-8">
        <p class="text-sm">
            Permintaan salinan ini saat ini berstatus: 
            <strong>{{ ucfirst($permintaan->status) }}</strong>.
        </p>
    </div>

    {{-- Tombol Navigasi --}}
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('permintaan.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium px-4 py-2 rounded shadow">
            ← Kembali ke Daftar
        </a>
        <a href="{{ route('permintaan.cetak', $permintaan->id) }}" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded shadow">
            🖨️ Cetak PDF
        </a>
    </div>
</div>
@endsection
