<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Borrowings Export</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
            font-family: 'Times New Roman', Times, serif;
        }
        .kop-header {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: left;
        }
        .kop-logo img {
            width: 70px;
            height: auto;
        }
        .kop-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .kop-text h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            line-height: 1.3;
            font-family: 'Times New Roman', Times, serif;
        }
        .kop-text h2 {
            margin: 2px 0;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.3;
            font-family: 'Times New Roman', Times, serif;
        }
        .kop-text p {
            margin: 5px 0 0 0;
            font-size: 11px;
            line-height: 1.2;
            font-family: 'Times New Roman', Times, serif;
        }
        h2 { margin: 20px 0 6px 0; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 6px 8px; }
        th { background: #f1f1f1; text-align: left; }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="kop-header">
            <div class="kop-logo">
                <img src="{{ public_path('picture1.png') }}" alt="Logo">
            </div>
            <div class="kop-text">
                <h1>PEMERINTAH KABUPATEN GARUT</h1>
                <h2>DINAS PERPUSTAKAAN DAN KEARSIPAN</h2>
                <p>Jalan RSU dr. Slamet No. 08 & 17 Garut Kode Pos 44151 Telp. (0262) 233748-236197</p>
            </div>
        </div>
    </div>

    <h2>Borrowings Export</h2>

<div class="meta">
  <strong>Generated:</strong> {{ now()->format('d M Y H:i') }}<br>
  @php
    $rf = !empty($from) ? \Carbon\Carbon::parse($from)->format('d M Y') : '—';
    $rt = !empty($to)   ? \Carbon\Carbon::parse($to)->format('d M Y')   : '—';
  @endphp
  <strong>Date Range (Borrow OR Due):</strong> {{ $rf }} s/d {{ $rt }}
</div>


    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Member</th>
                <th>Book</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse($borrowings as $i => $b)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $b->user->name }}</td>
                <td>{{ $b->book->title }}</td>
                <td>{{ optional($b->borrow_date)->format('d M Y') }}</td>
                <td>{{ optional($b->due_date)->format('d M Y') }}</td>
                <td style="text-transform:capitalize">{{ $b->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center">No data</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
