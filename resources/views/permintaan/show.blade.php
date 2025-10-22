@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Detail Permintaan #{{ $permintaan->id }}</h3>

    <table class="table">
        <tr><th>Pemohon</th><td>{{ $permintaan->pemohon }}</td></tr>
        <tr><th>Instansi</th><td>{{ $permintaan->instansi }}</td></tr>
        <tr><th>Tanggal</th><td>{{ $permintaan->tanggal_permintaan?->format('d-m-Y') }}</td></tr>
        <tr><th>Kode Warkah</th><td>{{ $permintaan->kode_warkah }}</td></tr>
        <tr><th>Jumlah Salinan</th><td>{{ $permintaan->jumlah_salinan }}</td></tr>
        <tr><th>Status</th><td>{{ $permintaan->status }}</td></tr>
        <tr><th>Tahapan</th>
            <td><ol>@foreach($permintaan->tahapan ?? [] as $t) <li>{{ $t }}</li> @endforeach</ol></td>
        </tr>
        <tr><th>Barcode</th>
            <td>
                @if($permintaan->barcode_path)
                    <img src="{{ $permintaan->barcode_path }}" alt="barcode" style="max-width:220px;">
                @else
                    <span class="text-muted">Belum dibuat</span>
                @endif
            </td>
        </tr>
    </table>

    <a href="{{ route('permintaan.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
