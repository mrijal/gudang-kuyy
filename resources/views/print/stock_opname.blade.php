<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stock Opname</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sub-header {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <div class="header">
        Laporan Barang Keluar Gudang Kuy
    </div>
    <div class="sub-header">
        Periode: {{ $startDate }} - {{ $endDate }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Opname</th>
                <th>Petugas</th>
                <th>Nama Produk</th>
                <th>Stock Awal</th>
                <th>Stock Opname</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>{{ $row['No'] }}</td>
                <td>{{ $row['Tanggal Opname'] }}</td>
                <td>{{ $row['Petugas'] }}</td>
                <td>{{ $row['Nama Produk'] }}</td>
                <td>{{ $row['Stock Awal'] }}</td>
                <td>{{ $row['Stock Opname'] }}</td>
                <td>{{ $row['Catatan'] }}</td>
            </tr>
            @if (($loop->index + 1) % 25 === 0)
        </tbody>
    </table>
    <div class="page-break"></div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Opname</th>
                <th>Petugas</th>
                <th>Nama Produk</th>
                <th>Stock Awal</th>
                <th>Stock Opname</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @endif
            @endforeach
        </tbody>
    </table>

</body>

</html>