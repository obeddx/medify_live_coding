<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kategori {{ $kategori->nama }}</title>
    <style>
        @page { margin: 30px 30px 60px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h2 { margin-bottom: 4px; }
        .info td { padding: 2px 8px 2px 0; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.data th, table.data td { border: 1px solid #444; padding: 6px; }
        table.data th { background: #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer {
            position: fixed;
            bottom: -35px;
            left: 0; right: 0;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #999;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <h2>Data Kategori Item</h2>

    <table class="info">
        <tr><td><strong>Nama Kategori</strong></td><td>: {{ $kategori->nama }}</td></tr>
        <tr><td><strong>Kode Kategori</strong></td><td>: {{ $kategori->kode }}</td></tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Kode</th>
                <th>Nama Item</th>
                <th>Supplier</th>
                <th>Harga Beli</th>
                <th>Laba</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($kategori->masterItems as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->supplier }}</td>
                <td class="text-right">{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->laba }}%</td>
                <td class="text-right">{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Belum ada item pada kategori ini.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $printedAt }}
    </div>
</body>
</html>