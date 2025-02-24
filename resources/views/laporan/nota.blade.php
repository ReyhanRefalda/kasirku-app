<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; text-align: center; }
        .nota-container { width: 300px; margin: auto; border: 1px solid black; padding: 10px; }
        .nota-header { font-size: 16px; font-weight: bold; margin-bottom: 10px; }
        .nota-body { text-align: left; }
        .nota-footer { margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px; text-align: left; }
        hr { border: 0; border-top: 1px dashed black; }
    </style>
</head>
<body>
    <div class="nota-container">
        <div class="nota-header">Struk Pembelian</div>
        <div class="nota-body">
            <p>Nama Kasir: {{ $penjualan->nama_kasir }}</p>
            <p>Nama Pembeli: {{ $penjualan->nama_pembeli }}</p>
       
            <p><strong>Tanggal:</strong> {{ $penjualan->created_at->format('d M Y H:i:s') }}</p>
            <hr>
            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan->detailPenjualan as $detail)
                        <tr>
                            <td>{{ $detail->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->quantity ?? 0 }}</td>
                            <td>Rp {{ number_format($detail->subtotal_harga, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
            <p><strong>Subtotal:</strong> Rp {{ number_format($penjualan->total_pembelanjaan, 2) }}</p>
            <p><strong>Diskon:</strong> Rp {{ number_format(($penjualan->total_pembelanjaan * ($penjualan->diskon_persen ?? 0)) / 100, 2) }}</p>
            <p><strong>Poin Digunakan:</strong> {{ number_format($penjualan->used_poin ?? 0) }} poin</p>
            <p><strong>Poin Didapat:</strong> {{ number_format($penjualan->poin_didapat, 0) }} poin</p>
            <p><strong>PPN (12%):</strong> Rp {{ number_format($penjualan->total_pembelanjaan * 0.12, 2) }}</p>
            <p><strong>Total Akhir:</strong> Rp {{ number_format($penjualan->total_akhir, 2) }}</p>
            <p><strong>Uang Masuk:</strong> Rp {{ number_format($penjualan->uang_masuk, 2) }}</p>
            <p><strong>Kembalian:</strong> Rp {{ number_format($penjualan->uang_kembalian, 2) }}</p>
        </div>
        <div class="nota-footer">
            <p>Terima kasih telah berbelanja!</p>
        </div>
    </div>
</body>
</html>