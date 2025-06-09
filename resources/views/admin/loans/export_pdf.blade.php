<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }
        h4 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 8px 10px;
        }
        td {
            padding: 8px 10px;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h4>Laporan Data Peminjaman Buku<br>Sungokong Book - Bulan {{ $bulanTahun }}</h4>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 20%;">Nama Peminjam</th>
                <th style="width: 30%;">Judul Buku</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 12%;">Tanggal Pinjam</th>
                <th style="width: 12%;">Tanggal Kembali</th>
                <th style="width: 12%;">Denda</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPenalty = 0; @endphp
            @forelse ($loans as $index => $loan)
                @php $totalPenalty += $loan->penalty ?? 0; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ optional($loan->user)->name ?? '-' }}</td>
                    <td>{{ optional($loan->book)->title ?? '-' }}</td>
                    <td>{{ ucfirst($loan->status) }}</td>
                    <td>{{ $loan->borrowed_at ? \Carbon\Carbon::parse($loan->borrowed_at)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d-m-Y') : '-' }}</td>
                    <td class="text-right">Rp {{ number_format($loan->penalty ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Data peminjaman tidak ditemukan.</td>
                </tr>
            @endforelse

            @if(count($loans))
                <tr>
                    <td colspan="6" class="text-right"><strong>Total Denda</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($totalPenalty, 0, ',', '.') }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
