<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Tiket</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            padding: 25px 35px;
            color: #1a1a2e;
            line-height: 1.5;
            background: #ffffff;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #1a1a2e;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo {
            width: 60px;
            height: 60px;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .museum-name h1 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
            letter-spacing: 1px;
        }
        .museum-name p {
            font-size: 8px;
            color: #4a4a6a;
            margin: 1px 0 0;
        }
        .header-right {
            text-align: right;
        }
        .header-right .doc-title {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header-right .doc-number {
            font-size: 10px;
            color: #4a4a6a;
            font-weight: 600;
            margin-top: 2px;
        }
        .header-right .doc-date {
            font-size: 9px;
            color: #6a6a8a;
            margin-top: 2px;
        }
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: #f0f0f5;
            padding: 6px 10px;
            margin: 12px 0 8px;
            border-left: 4px solid #1a1a2e;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin: 10px 0 15px;
        }
        .summary-card {
            padding: 8px 12px;
            border-radius: 4px;
            color: #ffffff;
            text-align: center;
        }
        .summary-card.blue { background: #3b82f6; }
        .summary-card.green { background: #22c55e; }
        .summary-card.red { background: #ef4444; }
        .summary-card.yellow { background: #eab308; }
        .summary-card.orange { background: #f97316; }
        .summary-card .label {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        .summary-card .value {
            font-size: 13px;
            font-weight: 700;
            margin-top: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            margin: 8px 0 12px;
        }
        table thead th {
            background: #1a1a2e;
            color: #ffffff;
            padding: 5px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table tbody td {
            padding: 4px 6px;
            border-bottom: 1px solid #e8e8ee;
            vertical-align: middle;
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-mono { font-family: 'Courier New', monospace; font-size: 8px; }
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 6.5px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #e8e8ee;
            display: flex;
            justify-content: space-between;
            font-size: 8px;
            color: #6a6a8a;
        }
        .stamp {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px dashed #d0d0dd;
        }
        .stamp-text {
            text-align: center;
            font-size: 8px;
            color: #6a6a8a;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            <div class="logo">
                <img src="{{ public_path('images/logo-museum.png') }}" alt="Museum Logo">
            </div>
            <div class="museum-name">
                <h1>Museum MK. Lesmana</h1>
                <p>Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175</p>
            </div>
        </div>
        <div class="header-right">
            <div class="doc-title">Laporan Transaksi</div>
            <div class="doc-number">No. LAP/{{ date('Ymd') }}/{{ rand(100, 999) }}</div>
            <div class="doc-date">Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</div>
            <div class="doc-date">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    
    {{-- TABLE --}}
    <div class="section-title">Rincian Transaksi</div>
    <table>
        <thead>
            <tr>
                <th width="10%">No. Transaksi</th>
                <th width="15%">Pembeli</th>
                <th width="15%">Tanggal</th>
                <th width="18%">Tiket</th>
                <th width="7%" class="text-center">Jml</th>
                <th width="15%" class="text-right">Total</th>
                <th width="10%">Metode</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $item)
                <tr>
                    <td class="text-mono">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pemesanan)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->ticket->nama_tiket ?? '-' }}</td>
                    <td class="text-center">{{ $item->jumlah_tiket }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->metode_pembayaran ?? '-')) }}</td>
                    <td class="text-center">
                        @php
                            if ($item->status === 'lunas') {
                                $badgeClass = 'badge-success';
                                $label = 'Lunas';
                            } elseif ($item->status === 'dibatalkan') {
                                $badgeClass = 'badge-danger';
                                $label = 'Dibatalkan';
                            } elseif ($item->status === 'pengembalian_berhasil') {
                                $badgeClass = 'badge-warning';
                                $label = 'Refund';
                            } elseif ($item->status === 'proses_pembatalan') {
                                $badgeClass = 'badge-orange';
                                $label = 'Proses Refund';
                            } else {
                                $badgeClass = 'badge-info';
                                $label = ucfirst(str_replace('_', ' ', $item->status));
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <span>Dokumen ini dicetak secara elektronik</span>
        <span>Museum MK. Lesmana • {{ date('Y') }}</span>
    </div>

    <div class="stamp">
        <div class="stamp-text">Laporan ini merupakan dokumen resmi Museum MK. Lesmana</div>
        <div class="stamp-text" style="margin-top: 3px;">Status: {{ $status != 'all' ? ucfirst(str_replace('_', ' ', $status)) : 'Semua' }} | Metode: {{ $metode != 'all' ? ucfirst(str_replace('_', ' ', $metode)) : 'Semua' }}</div>
    </div>

</body>
</html>