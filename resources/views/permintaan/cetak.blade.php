<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Salinan Arsip - {{ $permintaan->nama_pemohon }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #2c3e50;
            background: #ffffff;
        }
        
        .container {
            max-width: 21cm;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }

        /* Header dengan Logo/Kop Surat */
        .header-kop {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px double #34495e;
            margin-bottom: 30px;
        }

        .header-kop .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .header-kop h1 {
            font-size: 18pt;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-kop .subtitle {
            font-size: 10pt;
            color: #7f8c8d;
            margin-bottom: 3px;
        }

        .header-kop .address {
            font-size: 9pt;
            color: #95a5a6;
        }

        /* Title Dokumen */
        .doc-title {
            text-align: center;
            margin: 30px 0 25px;
        }

        .doc-title h2 {
            font-size: 14pt;
            font-weight: 700;
            color: #2c3e50;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .doc-title .doc-number {
            font-size: 10pt;
            color: #7f8c8d;
        }

        /* Content Section */
        .section {
            margin-bottom: 25px;
        }

        .section-header {
            font-size: 11pt;
            font-weight: 700;
            color: #2c3e50;
            padding: 8px 12px;
            background: #ecf0f1;
            border-left: 4px solid #667eea;
            margin-bottom: 15px;
        }

        /* Table Style */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .info-table tr {
            border-bottom: 1px solid #ecf0f1;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 180px;
            font-weight: 600;
            color: #34495e;
        }

        .info-table td:nth-child(2) {
            width: 20px;
            text-align: center;
            color: #95a5a6;
        }

        .info-table td:last-child {
            color: #2c3e50;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-diajukan {
            background: #fff4e6;
            color: #e67e22;
            border: 1px solid #f39c12;
        }

        .status-diterima {
            background: #e3f2fd;
            color: #2196f3;
            border: 1px solid #42a5f5;
        }

        .status-disposisi {
            background: #f3e5f5;
            color: #9c27b0;
            border: 1px solid #ba68c8;
        }

        .status-disalin {
            background: #e8eaf6;
            color: #5e35b1;
            border: 1px solid #7e57c2;
        }

        .status-selesai {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }

        /* Note Box */
        .note-box {
            background: #fffbeb;
            border: 1px solid #fbbf24;
            border-left: 4px solid #f59e0b;
            padding: 12px 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .note-box strong {
            color: #92400e;
            display: block;
            margin-bottom: 5px;
        }

        .note-box p {
            color: #78350f;
            margin: 0;
        }

        /* Signature Section */
        .signature-wrapper {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            text-align: center;
            width: 40%;
            padding: 10px;
        }

        .signature-box.right {
            float: right;
        }

        .signature-box .location-date {
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .signature-box .title {
            font-weight: 600;
            margin-bottom: 60px;
            font-size: 10pt;
        }

        .signature-box .name {
            font-weight: 700;
            border-top: 1px solid #2c3e50;
            padding-top: 5px;
            display: inline-block;
            min-width: 200px;
        }

        .signature-box .nip {
            font-size: 9pt;
            color: #7f8c8d;
            margin-top: 3px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 15px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            font-size: 8pt;
            color: #95a5a6;
        }

        .footer .doc-info {
            margin-bottom: 8px;
        }

        /* Print Button */
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 10pt;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-back {
            background: #95a5a6;
            color: white;
        }

        .btn-back:hover {
            background: #7f8c8d;
        }

        /* Hide controls when printing */
        @media print {
            .print-controls {
                display: none !important;
            }

            body {
                background: white;
            }

            .container {
                padding: 0;
            }

            @page {
                margin: 1.5cm;
            }
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .signature-wrapper {
                display: block;
            }

            .signature-box {
                display: block;
                width: 100%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="print-controls">
        <button onclick="window.print()" class="btn btn-print">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
            </svg>
            Cetak PDF
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-back">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="container">
        <!-- Header Kop Surat -->
        <div class="header-kop">
            <div class="logo">A</div>
            <h1>Sistem Informasi Arsip</h1>
            <p class="subtitle">Lembaga/Instansi Pengelola Arsip</p>
            <p class="address">Jl. Contoh Alamat No. 123, Kota, Provinsi 12345 | Telp: (021) 1234567 | Email: info@arsip.go.id</p>
        </div>

        <!-- Document Title -->
        <div class="doc-title">
            <h2>Formulir Permintaan Salinan Arsip</h2>
            @if($permintaan->nomor_permintaan)
            <p class="doc-number">Nomor: {{ $permintaan->nomor_permintaan }}</p>
            @endif
        </div>

        <!-- Section 1: Data Pemohon -->
        <div class="section">
            <div class="section-header">I. DATA PEMOHON</div>
            <table class="info-table">
                <tr>
                    <td>Nama Pemohon</td>
                    <td>:</td>
                    <td>{{ $permintaan->nama_pemohon }}</td>
                </tr>
                <tr>
                    <td>Instansi/Organisasi</td>
                    <td>:</td>
                    <td>{{ $permintaan->instansi ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nomor Identitas (KTP/SIM)</td>
                    <td>:</td>
                    <td>{{ $permintaan->nomor_identitas ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat Lengkap</td>
                    <td>:</td>
                    <td>{{ $permintaan->alamat_lengkap ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nomor Telepon</td>
                    <td>:</td>
                    <td>{{ $permintaan->nomor_telepon ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $permintaan->email ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Section 2: Detail Permintaan -->
        <div class="section">
            <div class="section-header">II. DETAIL PERMINTAAN</div>
            <table class="info-table">
                <tr>
                    <td>Tanggal Permintaan</td>
                    <td>:</td>
                    <td>{{ $permintaan->tanggal_permintaan ? \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td>Status Permintaan</td>
                    <td>:</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($permintaan->status_permintaan) }}">
                            {{ $permintaan->status_permintaan }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Jumlah Salinan</td>
                    <td>:</td>
                    <td><strong>{{ $permintaan->jumlah_salinan }} Berkas</strong></td>
                </tr>
                <tr>
                    <td>Tujuan Penggunaan</td>
                    <td>:</td>
                    <td>{{ $permintaan->tujuan_penggunaan ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Section 3: Informasi Arsip/Warkah -->
        @if($permintaan->warkah)
        <div class="section">
            <div class="section-header">III. INFORMASI ARSIP YANG DIMINTA</div>
            <table class="info-table">
                <tr>
                    <td>Kode Klasifikasi</td>
                    <td>:</td>
                    <td>{{ $permintaan->warkah->kode_klasifikasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nomor Folder</td>
                    <td>:</td>
                    <td>{{ $permintaan->warkah->no_folder ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Uraian Informasi Arsip</td>
                    <td>:</td>
                    <td>{{ $permintaan->warkah->uraian_informasi_arsip ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Rak Penyimpanan</td>
                    <td>:</td>
                    <td>{{ $permintaan->warkah->ruang_penyimpanan_rak ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jumlah Berkas</td>
                    <td>:</td>
                    <td>{{ $permintaan->warkah->jumlah ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tingkat Perkembangan</td>
                    <td>:</td>
                    <td>{{ $permintaan->warkah->tingkat_perkembangan ?? '-' }}</td>
                </tr>
            </table>
        </div>
        @endif

        <!-- Catatan -->
        @if($permintaan->catatan)
        <div class="note-box">
            <strong>Catatan:</strong>
            <p>{{ $permintaan->catatan }}</p>
        </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-wrapper">
            {{-- <div class="signature-box">
                <div class="location-date">Mengetahui,<br>Petugas Arsip</div>
                <div class="title">&nbsp;</div>
                <div class="name">(_________________)</div>
                <div class="nip">NIP. </div>
            </div> --}}
            
            <div class="signature-box right">
                <div class="location-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                <div class="title">Pemohon,</div>
                <div class="name">{{ $permintaan->nama_pemohon }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="doc-info">
                Dokumen ID: {{ $permintaan->id }} | Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB
            </div>
            <p>Dokumen ini sah dan dihasilkan secara elektronik oleh Sistem Informasi Arsip</p>
        </div>
    </div>
</body>
</html>