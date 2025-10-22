@extends('layouts.app')

@section('title', 'Daftar Permintaan Salinan Arsip')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">

    {{-- ALERT NOTIFIKASI --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <strong class="font-semibold">Terjadi Kesalahan!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            <strong class="font-semibold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <div class="text-center md:text-left mb-4 md:mb-0">
            <h1 class="text-2xl font-bold text-gray-800">📁 Daftar Permintaan Salinan Arsip</h1>
            <p class="text-gray-500">Kelola dan pantau seluruh permintaan salinan dokumen dengan mudah</p>
        </div>
        <a href="{{ route('permintaan.create') }}" 
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            + Tambah Permintaan
        </a>
    </div>

    {{-- FORM FILTER --}}
    <div class="bg-white rounded-xl shadow p-5 mb-6">
        <form action="{{ route('permintaan.index') }}" method="GET" class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Cari Berdasarkan</label>
                <input type="text" name="keyword" placeholder="Masukkan nama / instansi / uraian..."
                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-gray-700 mb-1 font-medium">Tahun</label>
                <select name="tahun" class="w-full border border-gray-300 rounded-lg p-2.5">
                    <option value="">Semua Tahun</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg mr-2 hover:bg-blue-700 transition">Cari</button>
                <a href="{{ route('permintaan.index') }}" 
                   class="bg-gray-300 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-400 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full table-auto text-left">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Tanggal Permintaan</th>
                    <th class="px-4 py-3">Pemohon</th>
                    <th class="px-4 py-3">Instansi</th>
                    <th class="px-4 py-3">Uraian Arsip</th>
                    <th class="px-4 py-3">Jumlah</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($permintaan as $index => $p)
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($p->tanggal_permintaan)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2 font-medium">{{ $p->pemohon }}</td>
                        <td class="px-4 py-2">{{ $p->instansi ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $p->uraian_informasi_arsip }}</td>
                        <td class="px-4 py-2 text-center">{{ $p->jumlah_salinan }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-sm font-medium rounded-lg
                                @if($p->status == 'selesai') bg-green-100 text-green-700
                                @elseif($p->status == 'diproses') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center space-x-2">
                            <a href="{{ route('permintaan.show', $p->id) }}" 
                               class="text-blue-600 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-gray-500">Belum ada permintaan salinan arsip.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ALUR PERMINTAAN --}}
    <div class="mt-10 bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📜 Alur Permintaan Salinan Arsip</h2>
        <ol class="list-decimal ml-6 text-gray-700 space-y-2">
            <li>Nota Dinas atau surat permohonan permintaan salinan dari bidang/kantah.</li>
            <li>Surat diterima dan didisposisikan oleh Kakanwil → Kabag → Kasubag Umum → Pengelola Arsip.</li>
            <li>Pencatatan arsip permintaan.</li>
            <li>Pencarian arsip oleh petugas arsip.</li>
            <li>Fotokopi atau salin arsip sesuai permintaan.</li>
            <li>Pemberian barcode pada salinan warkah.</li>
            <li>Balasan Nota Dinas untuk penyerahan salinan.</li>
        </ol>
    </div>
</div>
@endsection
