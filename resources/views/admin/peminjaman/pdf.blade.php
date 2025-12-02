<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Borrowings Export</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 6px 0; }
        .meta { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 6px 8px; }
        th { background: #f1f1f1; text-align: left; }
    </style>
</head>
<body>
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
