<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport 360 - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 16px; }
        .header h4 { margin: 5px 0 0 0; font-weight: normal; font-size: 13px; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; }
        .score-box {
            border: 2px solid #2563eb;
            background-color: #f0f7ff;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .score-box h3 { margin: 0; font-size: 24px; color: #2563eb; }
        .score-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .score-table th, .score-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .score-table th { background-color: #f8fafc; }
        .catatan-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            Cetak / Download PDF
        </button>
    </div>

    <div class="header">
        <h2>Sistem Penilaian Kinerja 360° ASN</h2>
        <h4>RAPORT HASIL EVALUASI EVALUATOR 360°</h4>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 15%; font-weight: bold;">NAMA PEGAWAI</td>
            <td style="width: 35%;">: {{ $user->name }}</td>
            <td style="width: 15%; font-weight: bold;">PERIODE</td>
            <td style="width: 35%;">: {{ $periode->nama_periode }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">NIP</td>
            <td>: {{ $user->nip }}</td>
            <td style="font-weight: bold;">Position</td>
            <td>: {{ $user->Position?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Department KERJA</td>
            <td>: {{ $user->Department?->name ?? '-' }}</td>
            <td style="font-weight: bold;">STATUS PERIODE</td>
            <td>: {{ strtoupper($periode->status) }}</td>
        </tr>
    </table>

    <div class="score-box">
        <div>NILAI AKHIR EVALUASI 360°</div>
        <h3>{{ number_format($hasil->nilai_akhir ?? 0, 2) }}</h3>
        <div style="font-weight: bold; margin-top: 5px;">PREDIKAT: {{ strtoupper($hasil->kategori ?? 'BELUM DIKALKULASI') }}</div>
    </div>

    <h4>RINCIAN PERHITUNGAN BOBOT PENILAIAN</h4>
    <table class="score-table">
        <thead>
            <tr>
                <th>PERSPEKTIF EVALUATOR</th>
                <th>BOBOT ASPEK</th>
                <th>RATA-RATA SKOR (100)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nilai Atasan Langsung</td>
                <td>50%</td>
                <td>{{ $hasil->nilai_atasan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nilai Rekan Kerja (Peers)</td>
                <td>{{ $user->Position?->level === 'staff' ? '50%' : '30%' }}</td>
                <td>{{ $hasil->nilai_rekan ?? '-' }}</td>
            </tr>
            @if($user->Position?->level !== 'staff')
                <tr>
                    <td>Nilai Bawahan</td>
                    <td>20%</td>
                    <td>{{ $hasil->nilai_bawahan ?? '-' }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <h4>MASUKAN DAN CATATAN KONSTRUKTIF EVALUATOR</h4>
    @forelse($penilaians as $p)
        @if($p->catatan)
            <div class="catatan-box">
                <strong>Penilai ({{ strtoupper($p->jenis_penilai) }}):</strong>
                <p style="margin: 5px 0 0 0; font-style: italic;">"{{ $p->catatan }}"</p>
            </div>
        @endif
    @empty
        <p style="color: #666;">Tidak ada catatan masukan evaluator.</p>
    @endforelse

    <div style="margin-top: 40px; text-align: right;">
        <p>Dicetak secara otomatis oleh SI-PK360 ASN pada {{ date('d F Y') }}</p>
    </div>
</body>
</html>

