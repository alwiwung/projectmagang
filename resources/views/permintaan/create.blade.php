@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Tambah Permintaan Salinan</h3>

    <form action="{{ route('permintaan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Pemohon</label>
            <input type="text" name="pemohon" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Bidang / Kantah</label>
            <input type="text" name="instansi" class="form-control">
        </div>
        <div class="mb-3">
            <label>Tanggal Permintaan</label>
            <input type="date" name="tanggal_permintaan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kode Warkah</label>
            <input type="text" name="kode_warkah" class="form-control">
        </div>
        <div class="mb-3">
            <label>Jumlah Salinan</label>
            <input type="number" name="jumlah_salinan" class="form-control" value="1" min="1">
        </div>
        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Simpan Permintaan</button>
        <a href="{{ route('permintaan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
