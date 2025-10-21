@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white/90 backdrop-blur-md shadow-2xl rounded-2xl border border-gray-200 p-8">

        <!-- HEADER -->
        <div class="flex items-center mb-8 pb-3 border-b-2 border-blue-100">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-3 rounded-xl text-white shadow-md mr-3">
                <i class="fa-solid fa-folder-open text-2xl"></i>
            </div>
            <h4 class="text-2xl font-bold text-gray-800">Detail Peminjaman Warkah</h4>
        </div>

        <!-- GRID UTAMA -->
        <div class="grid md:grid-cols-2 gap-8">

            {{-- INFORMASI PEMINJAM --}}
            <div class="bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white font-semibold text-sm">
                    <i class="fa-solid fa-user mr-2"></i> Informasi Peminjam
                </div>
                <table class="w-full text-sm text-gray-700">
                    <tbody>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold w-1/3 border-b border-gray-200">Nama</td>
                            <td class="p-3 border-b border-gray-200">{{ $peminjaman->nama_peminjam }}</td>
                        </tr>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold border-b border-gray-200">No HP</td>
                            <td class="p-3 border-b border-gray-200">{{ $peminjaman->no_hp }}</td>
                        </tr>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold border-b border-gray-200">Email</td>
                            <td class="p-3 border-b border-gray-200">{{ $peminjaman->email }}</td>
                        </tr>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold">Tujuan Pinjam</td>
                            <td class="p-3">{{ $peminjaman->tujuan_pinjam }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- INFORMASI WAKTU --}}
            <div class="bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white font-semibold text-sm">
                    <i class="fa-solid fa-calendar-days mr-2"></i> Informasi Waktu
                </div>
                <table class="w-full text-sm text-gray-700">
                    <tbody>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold w-1/3 border-b border-gray-200">Tanggal Pinjam</td>
                            <td class="p-3 border-b border-gray-200">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                            </td>
                        </tr>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold border-b border-gray-200">Batas Pengembalian</td>
                            <td class="p-3 border-b border-gray-200">
                                {{ \Carbon\Carbon::parse($peminjaman->batas_peminjaman)->format('d M Y') }}
                            </td>
                        </tr>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-3 font-semibold">Tanggal Kembali</td>
                            <td class="p-3">
                                {{ $peminjaman->tanggal_kembali 
                                    ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') 
                                    : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- INFORMASI WARKAH --}}
<div class="mt-10 bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white font-semibold text-sm">
        <i class="fa-solid fa-file-lines mr-2"></i> Informasi Warkah
    </div>
    <table class="w-full text-sm text-gray-700">
        <tbody>
            <tr class="hover:bg-blue-50 transition">
                <td class="p-3 font-semibold w-1/3 border-b border-gray-200">Nomor Item Arsip</td>
                <td class="p-3 border-b border-gray-200">{{ $peminjaman->warkah->nomor_item_arsip ?? '-' }}</td>
            </tr>
            <tr class="hover:bg-blue-50 transition">
                <td class="p-3 font-semibold border-b border-gray-200">Kode Klasifikasi</td>
                <td class="p-3 border-b border-gray-200">{{ $peminjaman->warkah->kode_klasifikasi ?? '-' }}</td>
            </tr>
            <tr class="hover:bg-blue-50 transition">
                <td class="p-3 font-semibold border-b border-gray-200">Kurun Waktu Berkas</td>
                <td class="p-3 border-b border-gray-200">{{ $peminjaman->warkah->kurun_waktu_berkas ?? '-' }}</td>
            </tr>
            <tr class="hover:bg-blue-50 transition">
                <td class="p-3 font-semibold border-b border-gray-200">Uraian Informasi Arsip</td>
                <td class="p-3 border-b border-gray-200">{{ $peminjaman->warkah->uraian_informasi_arsip ?? '-' }}</td>
            </tr>
            <tr class="hover:bg-blue-50 transition">
                <td class="p-3 font-semibold border-b border-gray-200">Lokasi Penyimpanan</td>
                <td class="p-3 border-b border-gray-200">{{ $peminjaman->warkah->lokasi ?? '-' }}</td>
            </tr>
            <tr class="hover:bg-blue-50 transition">
                <td class="p-3 font-semibold">Ruang Penyimpanan / Rak</td>
                <td class="p-3">{{ $peminjaman->warkah->ruang_penyimpanan_rak ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>


        {{-- STATUS INFORMASI --}}
        <div class="mt-10">
            @if ($peminjaman->status == 'Dikembalikan')
                <div class="bg-gradient-to-r from-green-50 via-white to-green-50 border-l-4 border-green-600 shadow-md p-5 rounded-xl">
                    <div class="flex items-center mb-3">
                        <i class="fa-solid fa-circle-check text-green-600 text-xl mr-2"></i>
                        <h5 class="text-lg font-semibold text-green-700">Warkah Telah Dikembalikan</h5>
                    </div>
                    <p class="text-gray-700 text-sm mb-4">
                        Warkah ini telah dikembalikan pada
                        <strong>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</strong>.
                    </p>

                    <!-- DETAIL PENGEMBALIAN -->
                    <div class="bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl p-4 shadow-sm">
                        <table class="w-full text-sm text-gray-700">
                            <tbody>
                                <tr class="hover:bg-green-50 transition">
                                    <td class="p-3 font-semibold w-1/3 border-b border-gray-200">Kondisi</td>
                                    <td class="p-3 border-b border-gray-200">
                                        <span class="@if($peminjaman->kondisi == 'Baik') text-green-600 
                                                     @elseif($peminjaman->kondisi == 'Rusak') text-yellow-600 
                                                     @else text-red-600 @endif font-bold">
                                            {{ $peminjaman->kondisi }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-green-50 transition">
                                    <td class="p-3 font-semibold border-b border-gray-200">Catatan</td>
                                    <td class="p-3 border-b border-gray-200">{{ $peminjaman->catatan ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @if ($peminjaman->bukti)
                            <div class="mt-4">
                                <h6 class="font-semibold text-gray-700 mb-2">Bukti Pengembalian:</h6>
                                <a href="{{ asset('storage/' . $peminjaman->bukti) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $peminjaman->bukti) }}" 
                                         alt="Bukti Pengembalian" 
                                         class="rounded-lg shadow-lg border hover:scale-105 transition-transform max-h-72 mx-auto">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            @elseif ($peminjaman->status == 'Terlambat')
                <div class="bg-gradient-to-r from-red-50 via-white to-red-50 border-l-4 border-red-600 shadow-md p-5 rounded-xl">
                    <div class="flex items-center mb-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl mr-2"></i>
                        <h5 class="text-lg font-semibold text-red-700">Terlambat Dikembalikan</h5>
                    </div>
                    <p class="text-gray-700 text-sm">
                        Pengembalian melewati batas waktu 
                        <strong>{{ \Carbon\Carbon::parse($peminjaman->batas_peminjaman)->format('d M Y') }}</strong>.
                    </p>
                </div>

            @else
                <div class="bg-gradient-to-r from-yellow-50 via-white to-yellow-50 border-l-4 border-yellow-600 shadow-md p-5 rounded-xl">
                    <div class="flex items-center mb-2">
                        <i class="fa-solid fa-clock text-yellow-600 text-xl mr-2"></i>
                        <h5 class="text-lg font-semibold text-yellow-700">Masih Dipinjam</h5>
                    </div>
                    <p class="text-gray-700 text-sm">
                        Warkah ini masih dalam status <strong>{{ $peminjaman->status }}</strong>.  
                        Harap dikembalikan sebelum 
                        <strong>{{ \Carbon\Carbon::parse($peminjaman->batas_peminjaman)->format('d M Y') }}</strong>.
                    </p>
                </div>
            @endif
        </div>

        {{-- TOMBOL KEMBALI --}}
        <div class="mt-10 flex justify-end">
            <a href="{{ route('peminjaman.index') }}"
                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-300">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

    </div>
</div>
@endsection
