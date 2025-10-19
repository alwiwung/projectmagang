@extends('layouts.app')

@section('title', 'Peminjaman Barang')

@section('content')
<div x-data="peminjamanApp()" class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Peminjaman Barang</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola data peminjaman dan pengembalian warkah</p>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-gradient-to-r from-white via-gray-50 to-white rounded-xl shadow-lg border border-gray-200 p-4 transform transition-all duration-300 hover:shadow-xl">
        <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
            <!-- Tombol Tambah -->
            <button
                @click="openModal = true"
                class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                <i class="fa-solid fa-plus mr-2 text-lg"></i>
                <span>Tambah Peminjaman Baru</span>
            </button>

            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <!-- Search -->
                <form method="GET" action="{{ route('peminjaman.index') }}" class="relative flex-1 sm:flex-none">
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fa-solid fa-search text-gray-400 group-focus-within:text-blue-600"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari Peminjaman..."
                            class="w-full sm:w-64 pl-11 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 bg-white shadow-md hover:shadow-lg" />
                    </div>
                </form>

                <!-- Filter Status -->
                <form method="GET" action="{{ route('peminjaman.index') }}" class="relative flex-1 sm:flex-none">
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 transition-all duration-200 group-focus-within:text-blue-600">
                            <i class="fa-solid fa-filter text-gray-400 group-focus-within:text-blue-600"></i>
                        </span>
                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="w-full sm:w-56 pl-11 pr-10 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 appearance-none bg-white transition-all duration-200 cursor-pointer font-semibold text-gray-700 shadow-md hover:shadow-lg">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>🔵 Dipinjam</option>
                            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>🔴 Terlambat</option>
                            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>🟢 Dikembalikan</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-xl border border-gray-200 overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
        <!-- Table Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 px-6 py-4">
            <h2 class="text-lg font-bold text-white flex items-center">
                <i class="fa-solid fa-table mr-2"></i>
                Data Peminjaman Barang
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 via-gray-100 to-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">No</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Kode Warkah</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Uraian Informasi</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Nama</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">No HP</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Email</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Tanggal Pinjam</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Tujuan Pinjam</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Batas Peminjaman</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Status</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-b-2 border-blue-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($peminjaman as $index => $item)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200 transform hover:scale-[1.01] hover:shadow-md">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xs shadow-md">
                                {{ $peminjaman->firstItem() + $index }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md">
                                    <i class="fa-solid fa-hashtag mr-1.5 text-xs"></i>
                                    {{ $item->id_warkah }}
                                </span>
                                @if($item->warkah)
                                <span class="text-xs text-gray-500 mt-1 font-medium">
                                    {{ $item->warkah->kode_klasifikasi ?? '-' }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            <div class="max-w-xs">
                                @if($item->warkah)
                                <p class="font-semibold text-gray-900 line-clamp-2" title="{{ $item->warkah->uraian_informasi_arsip }}">
                                    {{ Str::limit($item->warkah->uraian_informasi_arsip, 60) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                    {{ $item->warkah->ruang_penyimpanan_rak ?? 'Lokasi tidak tersedia' }}
                                </p>
                                @else
                                <p class="text-gray-400 italic">Data tidak tersedia</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md mr-3">
                                    {{ strtoupper(substr($item->nama_peminjam, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $item->nama_peminjam }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->no_hp }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->email }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->tanggal_pinjam->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            <div class="max-w-xs truncate" title="{{ $item->tujuan_pinjam }}">
                                {{ $item->tujuan_pinjam }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->batas_peminjaman->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($item->status == 'Dipinjam')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
                                <i class="fa-solid fa-clock mr-1.5"></i>
                                Dipinjam
                            </span>
                            @elseif($item->status == 'Dikembalikan')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg">
                                <i class="fa-solid fa-circle-check mr-1.5"></i>
                                Dikembalikan
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg animate-pulse">
                                <i class="fa-solid fa-circle-exclamation mr-1.5"></i>
                                Terlambat
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    @click="showDetail({{ $item->id }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-bold rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <i class="fa-solid fa-eye mr-1.5"></i>
                                    Lihat Detail
                                </button>
                                @if($item->status != 'Dikembalikan')
                                <form method="POST" action="{{ route('peminjaman.kembalikan', $item->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin mengembalikan barang ini?')"
                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-xs font-bold rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                        <i class="fa-solid fa-arrow-rotate-left mr-1.5"></i>
                                        Kembalikan
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-4 shadow-lg">
                                    <i class="fa-solid fa-inbox text-gray-400 text-5xl"></i>
                                </div>
                                <p class="text-gray-600 text-xl font-bold mb-2">Belum ada data peminjaman</p>
                                <p class="text-gray-400 text-sm mb-4">Klik tombol "Tambah Peminjaman Baru" untuk memulai</p>
                                <button
                                    @click="openModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <i class="fa-solid fa-plus mr-2"></i>
                                    Tambah Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($peminjaman->hasPages())
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t-2 border-blue-500">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-sm font-semibold text-gray-700 bg-white px-4 py-2 rounded-lg shadow-md">
                    Menampilkan
                    <span class="text-blue-600 font-bold">{{ $peminjaman->firstItem() }}</span>
                    -
                    <span class="text-blue-600 font-bold">{{ $peminjaman->lastItem() }}</span>
                    dari
                    <span class="text-blue-600 font-bold">{{ $peminjaman->total() }}</span>
                    data
                </div>
                <div class="pagination-wrapper">
                    {{ $peminjaman->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Detail Section -->
    <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg border-2 border-blue-200 p-6 transform transition-all duration-300 hover:shadow-2xl">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-md mr-4">
                <i class="fa-solid fa-info-circle text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Detail Peminjaman</h3>
                <p class="text-sm text-gray-600">Informasi lengkap tentang peminjaman</p>
            </div>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-inner border border-gray-200">
            <p class="text-sm text-gray-600 flex items-center">
                <i class="fa-solid fa-hand-pointer text-blue-600 mr-2"></i>
                Klik tombol <span class="mx-1 px-2 py-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-md text-xs font-bold shadow-md">"Lihat Detail"</span> pada tabel untuk menampilkan informasi lengkap peminjaman
            </p>
        </div>
    </div>

    <!-- Modal Tambah Peminjaman -->
    <div
        x-show="openModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div
                x-show="openModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="openModal = false"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true">
            </div>

            <!-- Modal panel -->
            <div
                x-show="openModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                            <i class="fa-solid fa-plus-circle text-blue-600 mr-2"></i>
                            Tambah Peminjaman Baru
                        </h3>
                        <button
                            @click="openModal = false"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-600">Form tambah peminjaman akan dibuat di tutorial selanjutnya...</p>
                        <p class="text-sm text-gray-500 mt-2">Fitur ini akan mencakup input untuk semua field peminjaman dengan validasi lengkap.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button
                        type="button"
                        @click="openModal = false"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function peminjamanApp() {
        return {
            openModal: false,

            showDetail(id) {
                fetch(`/peminjaman/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        let warkahInfo = data.warkah ?
                            `Kode: ${data.warkah.kode_klasifikasi}-${data.warkah.nomor_item_arsip}\n` +
                            `Uraian: ${data.warkah.uraian_informasi_arsip}\n` +
                            `Lokasi: ${data.warkah.ruang_penyimpanan_rak || '-'}` :
                            'Data warkah tidak ditemukan';

                        alert(
                            `Detail Peminjaman:\n\n` +
                            `=== INFORMASI WARKAH ===\n${warkahInfo}\n\n` +
                            `=== INFORMASI PEMINJAM ===\n` +
                            `Nama: ${data.nama_peminjam}\n` +
                            `No HP: ${data.no_hp}\n` +
                            `Email: ${data.email}\n\n` +
                            `=== INFORMASI PEMINJAMAN ===\n` +
                            `Tanggal Pinjam: ${data.tanggal_pinjam}\n` +
                            `Batas Kembali: ${data.batas_peminjaman}\n` +
                            `Tujuan: ${data.tujuan_pinjam}\n` +
                            `Status: ${data.status}`
                        );
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail peminjaman');
                    });
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection