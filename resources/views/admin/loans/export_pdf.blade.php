<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Buku</title>
    <style>
        /* styling sederhana */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        h4 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h4>Laporan Data Peminjaman Buku Sungokong Book Bulan {{ $bulanTahun }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Status</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $index => $loan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ ucfirst($loan->status) }}</td>
                <td>{{ $loan->borrowed_at ? \Carbon\Carbon::parse($loan->borrowed_at)->format('d-m-Y') : '-' }}</td>
                <td>{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d-m-Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
