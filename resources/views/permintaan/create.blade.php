@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Tambah Permintaan Salinan Arsip')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-10 px-4">
    <div class="max-w-6xl mx-auto">
        {{-- Header Section --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Permintaan Salinan Arsip</h1>
            <p class="text-gray-600">Lengkapi formulir di bawah untuk mengajukan permintaan salinan arsip</p>
        </div>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div id="alert-success" class="mb-6 p-4 rounded-xl bg-green-50 border-l-4 border-green-500 shadow-sm transition-all duration-500">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    const alert = document.getElementById('alert-success');
                    if(alert) {
                        alert.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 4000);
            </script>
        @endif

        {{-- Main Form Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <form action="{{ route('permintaan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-8 space-y-8">
                    {{-- Section 1: Pilih Warkah --}}
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">1</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Pilih Warkah</h3>
                        </div>
                        
                        <label for="id_warkah" class="block text-sm font-medium text-gray-700 mb-2">
   
<label for="id_warkah" class="block text-sm font-medium text-gray-700 mb-2">
    🗂️ Dokumen Warkah <span class="text-red-500">*</span>
</label>

<div 
    x-data="{
        open: false,
        search: '',
        selectOption(id, text) {
            this.open = false;
            document.getElementById('id_warkah').value = id;
            this.$refs.selected_text.innerText = text;
        },
        get filteredOptions() {
            return Array.from(this.$refs.options.children).filter(el => 
                el.dataset.text.includes(this.search.toLowerCase())
            );
        }
    }" 
    class="relative"
>
    {{-- Hidden Select for Form Submission --}}
    <select id="id_warkah" name="id_warkah" required class="hidden">
        <option value="">-- Pilih Dokumen Warkah --</option>
        @foreach ($warkah as $item)
            <option value="{{ $item->id }}">{{ $item->uraian_informasi_arsip }}</option>
        @endforeach
    </select>

    {{-- Custom Dropdown --}}
    <button type="button" 
        @click="open = !open" 
        class="w-full px-4 py-4 pr-10 bg-white border-2 border-gray-300 rounded-lg 
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all 
               hover:border-blue-400 hover:shadow-md text-left text-gray-700 font-medium 
               flex items-center justify-between">
        <span x-ref="selected_text" class="text-gray-400">-- Pilih Dokumen Warkah --</span>
        <svg class="w-5 h-5 text-blue-500 transition-transform duration-300"
             :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown Panel --}}
    <div 
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute z-50 w-full mt-2 bg-white border-2 border-gray-300 rounded-lg shadow-xl max-h-96 overflow-hidden"
    >
        {{-- Search Input --}}
        <div class="p-3 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="relative">
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="🔍 Cari dokumen warkah..." 
                    class="w-full px-4 py-2 pr-10 bg-white border border-gray-300 rounded-lg 
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                >
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Options List --}}
        <div x-ref="options" class="overflow-y-auto max-h-80 custom-scrollbar">
            @foreach ($warkah as $item)
                <div
                    class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 transition-colors"
                    data-value="{{ $item->id }}"
                    data-text="{{ strtolower($item->uraian_informasi_arsip) }}"
                    x-show="'{{ strtolower($item->uraian_informasi_arsip) }}'.includes(search.toLowerCase())"
                    @click="selectOption('{{ $item->id }}', '{{ addslashes($item->uraian_informasi_arsip) }}')"
                >
                    <div class="flex items-start">
                        <span class="text-blue-500 mr-2 mt-1">📄</span>
                        <span class="text-sm text-gray-700 leading-relaxed">
                            {{ strlen($item->uraian_informasi_arsip) > 120 
                                ? substr($item->uraian_informasi_arsip, 0, 120) . '...' 
                                : $item->uraian_informasi_arsip }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- No Results --}}
        <div x-show="filteredOptions.length === 0" class="px-4 py-8 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 
                         11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">Tidak ada hasil yang ditemukan</p>
            <p class="text-sm mt-1">Coba kata kunci lain</p>
        </div>
    </div>
</div>

<p class="text-xs text-gray-500 mt-2 flex items-center">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    Pilih dokumen warkah yang akan dimintakan salinan
</p>


                  {{-- Section 2: Data Pemohon --}}
<div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
    <div class="flex items-center mb-4">
        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
            <span class="text-white text-sm font-bold">2</span>
        </div>
        <h3 class="text-lg font-semibold text-gray-800">Data Pemohon</h3>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Nama Pemohon --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                👤 Nama Pemohon <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_pemohon" placeholder="Masukkan nama lengkap"
                value="{{ old('nama_pemohon') }}" required
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('nama_pemohon') border-red-500 @enderror">
            @error('nama_pemohon')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Instansi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                🏢 Instansi
            </label>
            <input type="text" name="instansi" placeholder="Masukkan nama instansi"
                value="{{ old('instansi') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('instansi') border-red-500 @enderror">
            @error('instansi')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor Identitas --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                🪪 Nomor Identitas (KTP/SIM)
            </label>
            <input type="text" name="nomor_identitas" placeholder="Masukkan nomor identitas"
                value="{{ old('nomor_identitas') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('nomor_identitas') border-red-500 @enderror">
            @error('nomor_identitas')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor Telepon --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                📞 Nomor Telepon
            </label>
            <input type="text" name="nomor_telepon" placeholder="Masukkan nomor telepon aktif"
                value="{{ old('nomor_telepon') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('nomor_telepon') border-red-500 @enderror">
            @error('nomor_telepon')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                ✉️ Alamat Email
            </label>
            <input type="email" name="email" placeholder="Masukkan alamat email aktif"
                value="{{ old('email') }}"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat Lengkap --}}
        <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                🏠 Alamat Lengkap
            </label>
            <textarea name="alamat_lengkap" rows="3" placeholder="Masukkan alamat lengkap sesuai identitas"
                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none">{{ old('alamat_lengkap') }}</textarea>
            @error('alamat_lengkap')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


                    {{-- Section 3: Detail Permintaan --}}
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-6 border border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">3</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Detail Permintaan</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    📅 Tanggal Permintaan
                                </label>
                                <input type="date" name="tanggal_permintaan" value="{{ old('tanggal_permintaan') }}"
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('tanggal_permintaan') border-red-500 @enderror">
                                @error('tanggal_permintaan')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    📄 Jumlah Salinan
                                </label>
                                <input type="number" name="jumlah_salinan" min="1"
                                    value="{{ old('jumlah_salinan', 1) }}"
                                    class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all @error('jumlah_salinan') border-red-500 @enderror">
                                @error('jumlah_salinan')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                📝 Catatan Tambahan
                            </label>
                            <textarea name="catatan_tambahan" rows="3" placeholder="Tambahkan catatan jika diperlukan..."
                                class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none">{{ old('catatan_tambahan') }}</textarea>
                        </div>
                    </div>

                    {{-- Section 4: Upload Dokumen --}}
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">4</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Dokumen Pendukung</h3>
                        </div>
                        
                        {{-- Nota Dinas --}}
                        <div class="mb-6 p-5 bg-white rounded-lg border border-yellow-300">
                            <h4 class="font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Nota Dinas
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📎 Nomor Nota Dinas</label>
                                    <input type="text" name="nota_dinas_masuk_no" value="{{ old('nota_dinas_masuk_no') }}"
                                        placeholder="Contoh: ND/001/2024"
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📁 File Nota Dinas</label>
                                    <input type="file" name="nota_dinas_masuk_file" id="nota_dinas_masuk_file"
                                        accept=".pdf,.doc,.docx" class="hidden" onchange="showFileName('nota_dinas_masuk_file', 'file-name-nota')">
                                    <label for="nota_dinas_masuk_file"
                                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-medium rounded-lg cursor-pointer transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Pilih File
                                    </label>
                                    <p id="file-name-nota" class="text-sm text-gray-500 mt-2 italic">Belum ada file dipilih</p>
                                    @error('nota_dinas_masuk_file')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Surat Disposisi --}}
                        <div class="p-5 bg-white rounded-lg border border-orange-300">
                            <h4 class="font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Surat Disposisi
                            </h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📎 Nomor Surat Disposisi</label>
                                    <input type="text" name="nomor_surat_disposisi" value="{{ old('nomor_surat_disposisi') }}"
                                        placeholder="Contoh: SD/001/2024"
                                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📁 File Disposisi</label>
                                    <input type="file" name="file_disposisi" id="file_disposisi"
                                        accept=".pdf,.doc,.docx" class="hidden" onchange="showFileName('file_disposisi', 'file-name-disposisi')">
                                    <label for="file_disposisi"
                                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-medium rounded-lg cursor-pointer transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Pilih File
                                    </label>
                                    <p id="file-name-disposisi" class="text-sm text-gray-500 mt-2 italic">Belum ada file dipilih</p>
                                    @error('file_disposisi')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 5: Status --}}
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-200">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-bold">5</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Status Permintaan</h3>
                        </div>
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            📊 Pilih Status
                        </label>
                        <select name="status"
                            class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            <option value="Diajukan" {{ old('status') == 'Diajukan' ? 'selected' : '' }}>🟡 Diajukan</option>
                            <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>🟢 Diterima</option>
                            {{-- <option value="Disposisi" {{ old('status') == 'Disposisi' ? 'selected' : '' }}>🔵 Disposisi</option> --}}
                            <option value="Disalin" {{ old('status') == 'Disalin' ? 'selected' : '' }}>🟣 Disalin</option>
                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 flex flex-col sm:flex-row justify-between items-center gap-4 border-t">
                    <a href="{{ route('permintaan.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-lg shadow-md border-2 border-gray-300 transition-all hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>

                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-lg shadow-lg transition-all hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
function showFileName(inputId, labelId) {
    const input = document.getElementById(inputId);
    const label = document.getElementById(labelId);

    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024).toFixed(2);
        
        label.innerHTML = `
            <span class="inline-flex items-center">
                <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-green-700 font-medium">${fileName}</span>
                <span class="text-gray-500 ml-2">(${fileSize} KB)</span>
            </span>
        `;
        label.classList.remove('text-gray-500', 'italic');
        label.classList.add('text-green-700');
    } else {
        label.textContent = 'Belum ada file dipilih';
        label.classList.remove('text-green-700');
        label.classList.add('text-gray-500', 'italic');
    }
}

// Auto scroll ke error
document.addEventListener('DOMContentLoaded', function() {
    const firstError = document.querySelector('.border-red-500');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection