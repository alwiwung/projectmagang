<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Keterlambatan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .content {
            padding: 30px;
        }

        .alert-box {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }

        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-table td:first-child {
            font-weight: 600;
            color: #374151;
            width: 40%;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }

        .footer {
            background: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .overdue-badge {
            display: inline-block;
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-icon">⚠️</div>
            <h1 style="margin: 0; font-size: 28px;">Pengingat Keterlambatan</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Pengembalian Warkah</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p style="font-size: 16px; color: #374151;">
                Yth. <strong>{{ $peminjaman->nama_peminjam }}</strong>,
            </p>

            <div class="alert-box">
                <strong style="color: #dc2626;">⏰ Peminjaman warkah Anda telah melewati batas waktu!</strong>
                <p style="margin: 10px 0 0 0; color: #991b1b;">
                    Keterlambatan: <span class="overdue-badge">{{ $daysOverdue }} Hari</span>
                </p>
            </div>

            <h3 style="color: #1f2937; margin-top: 30px;">📋 Detail Peminjaman</h3>
            <table class="info-table">
                <tr>
                    <td>🔖 Kode Warkah</td>
                    <td><strong>{{ $peminjaman->warkah->kode_klasifikasi ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>📄 Uraian Informasi</td>
                    <td>{{ $peminjaman->warkah->uraian_informasi_arsip ?? '-' }}</td>
                </tr>
                <tr>
                    <td>📅 Tanggal Pinjam</td>
                    <td>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td>⏰ Batas Pengembalian</td>
                    <td style="color: #ef4444; font-weight: bold;">
                        {{ $peminjaman->batas_peminjaman->format('d M Y') }}
                    </td>
                </tr>
                <tr>
                    <td>🎯 Tujuan Pinjam</td>
                    <td>{{ $peminjaman->tujuan_pinjam }}</td>
                </tr>
            </table>

            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <strong style="color: #92400e;">📢 Tindakan yang Diperlukan:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #78350f;">
                    <li>Segera kembalikan warkah yang dipinjam</li>
                    <li>Hubungi bagian arsip jika ada kendala</li>
                    <li>Keterlambatan akan tercatat dalam sistem</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/peminjaman') }}" class="btn">
                    🔗 Lihat Detail Peminjaman
                </a>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                Jika Anda memiliki pertanyaan atau memerlukan bantuan, silakan hubungi:
            </p>
            <p style="color: #374151; margin: 5px 0;">
                📧 Email: arsip@example.com<br>
                📞 Telepon: (021) 1234-5678
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 5px 0;">Email otomatis dari Sistem Manajemen Arsip</p>
            <p style="margin: 5px 0;">{{ config('app.name') }} &copy; {{ date('Y') }}</p>
            <p style="margin: 5px 0; color: #9ca3af;">
                Harap tidak membalas email ini. Untuk pertanyaan, hubungi bagian arsip.
            </p>
        </div>
    </div>
</body>

</html>