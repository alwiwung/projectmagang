<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF Permintaan Salinan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h2>Detail Permintaan Salinan</h2>

    <div class="section">
        <h4>Informasi Pemohon</h4>
        <table>
            <tr><th>Nama</th><td>{{ $permintaan->pemohon }}</td></tr>
            <tr><th>Instansi</th><td>{{ $permintaan->instansi }}</td></tr>
            <tr><th>Jumlah Salinan</th><td>{{ $permintaan->jumlah_salinan }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $permintaan->tanggal_permintaan }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h4>Informasi Arsip</h4>
        <table>
            <tr><th>Uraian Arsip</th><td>{{ $permintaan->uraian_informasi_arsip }}</td></tr>
            <tr><th>Nomor Item</th><td>{{ $permintaan->warkah->nomor_item_arsip ?? '-' }}</td></tr>
            <tr><th>Kode Klasifikasi</th><td>{{ $permintaan->warkah->kode_klasifikasi ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h4>Status: {{ ucfirst($permintaan->status) }}</h4>
        <div>
            {!! DNS1D::getBarcodeHTML($permintaan->barcode_path, 'C128', 1.5, 40) !!}
        </div>
    </div>
</body>
</html>
