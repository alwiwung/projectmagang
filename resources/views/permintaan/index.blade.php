@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">

    {{-- Alert Error / Sukses --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Terjadi Kesalahan!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Judul --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Permintaan Salinan Warkah</h1>
            <p class="text-gray-500">Kelola dan catat semua permintaan salinan dokumen warkah dengan mudah</p>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
            + Tambah Permintaan
        </button>
    </div>

    {{-- Form Pencarian --}}
    <div class="bg-white rounded-xl shadow p-4 mb-6">
        <form action="{{ route('permintaan.index') }}" method="GET" class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 mb-1">Cari Berdasarkan</label>
                <input type="text" name="keyword" placeholder="Masukkan nama / kode arsip..."
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full border border-gray-300 rounded-lg p-2">
                    <option value="">Semua Tahun</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg mr-2 hover:bg-blue-700 transition">Cari</button>
                <a href="{{ route('permintaan.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full table-auto text-left">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Tanggal Permintaan</th>
                    <th class="px-4 py-2">Pemohon</th>
                    <th class="px-4 py-2">Uraian Informasi Arsip</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($permintaan as $index => $p)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $p->tanggal }}</td>
                        <td class="px-4 py-2">{{ $p->pemohon }}</td>
                        <td class="px-4 py-2">{{ $p->kode_arsip }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-sm 
                                {{ $p->status == 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('permintaan.show', $p->id) }}" 
                               class="text-blue-600 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada permintaan salinan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Alur Permintaan --}}
    <div class="mt-10 bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4">📜 Alur Permintaan Salinan Warkah</h2>
        <ol class="list-decimal ml-6 text-gray-700 space-y-2">
            <li>Nota Dinas / Permohonan permintaan salinan warkah dari Bidang / Kantah.</li>
            <li>Surat diterima → disposisi Kakanwil → Kabag → Kasubag Umum → Pengelola Arsip.</li>
            <li>Pencatatan arsip di spreadsheet.</li>
            <li>Pencarian arsip.</li>
            <li>Fotokopi / salin arsip yang diminta.</li>
            <li>Pemberian barcode pada salinan warkah.</li>
            <li>Balasan Nota Dinas untuk diserahkan salinan.</li>
        </ol>
    </div>

</div>
@endsection
