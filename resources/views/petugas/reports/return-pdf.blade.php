<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengembalian</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pengembalian Alat</h1>
        <p>SportLend System</p>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Qty Kembali</th>
                <th>Tanggal Kembali</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $key => $return)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $return->borrowing->user->name }}</td>
                    <td>{{ $return->borrowing->tool->name }}</td>
                    <td>{{ $return->quantity_returned }}</td>
                    <td>{{ $return->return_date->format('d-m-Y') }}</td>
                    <td>{{ $return->condition }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
