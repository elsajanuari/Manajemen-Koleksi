<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi — {{ now()->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9pt;
            color: #1e293b;
            background: #fff;
            line-height: 1.4;
        }

        /* ── HEADER ── */
        .header {
            background-color: #0b1d35;
            background-image: linear-gradient(135deg, #0b1d35 0%, #142744 60%, #1c3a68 100%);
            color: #fff;
            padding: 20px 28px 16px;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .header-meta-cell {
            text-align: right;
            width: 200px;
        }
        .header-badge {
            background: rgba(56,189,248,.15);
            border: 1px solid rgba(56,189,248,.3);
            color: #7dd3fc;
            font-size: 6.5pt;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 99px;
            display: inline-block;
            margin-bottom: 6px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
        }
        .header h1 span { color: #38bdf8; }
        .header-sub {
            font-size: 7.5pt;
            color: rgba(255,255,255,.5);
            margin-top: 4px;
        }
        .header-meta {
            text-align: right;
            font-size: 7pt;
            color: rgba(255,255,255,.55);
            line-height: 1.7;
        }
        .header-meta strong { color: rgba(255,255,255,.85); }

        /* Filter info */
        .filter-bar {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .filter-chip {
            display: inline-block;
            font-size: 7pt;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 6px;
            padding: 3px 9px;
            margin-right: 6px;
            margin-bottom: 4px;
            color: rgba(255,255,255,.7);
        }
        .filter-chip span { color: #38bdf8; font-weight: 700; }

        /* ── CONTENT ── */
        .content { padding: 0 24px 24px; }

        /* ── SECTION TITLE ── */
        .section-title {
            font-size: 9pt;
            font-weight: 700;
            color: #0b1d35;
            padding: 5px 0 5px 10px;
            border-left: 3px solid #1d4ed8;
            margin-bottom: 8px;
            margin-top: 18px;
        }
        .section-sub {
            font-size: 7pt;
            color: #64748b;
            margin-bottom: 10px;
            padding-left: 13px;
        }

        /* ── KPI GRID ── */
        .kpi-grid {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .kpi-grid-row { display: table-row; }
        .kpi-box {
            display: table-cell;
            width: 33.33%;
            padding: 4px;
            vertical-align: top;
        }
        .kpi-inner {
            border-radius: 8px;
            padding: 11px 14px;
            border-left: 3px solid;
            background: #f8fafc;
        }
        .kpi-inner.green  { border-color: #22c55e; background: #f0fdf4; }
        .kpi-inner.blue   { border-color: #3b82f6; background: #eff6ff; }
        .kpi-inner.violet { border-color: #7c3aed; background: #fdf4ff; }
        .kpi-inner.sky    { border-color: #38bdf8; background: #f0f9ff; }
        .kpi-inner.amber  { border-color: #f59e0b; background: #fffbeb; }
        .kpi-inner.indigo { border-color: #818cf8; background: #eef2ff; }
        .kpi-lbl { font-size: 6pt; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; margin-bottom: 4px; }
        .kpi-val { font-size: 13pt; font-weight: 700; color: #0b1d35; line-height: 1; }
        .kpi-inner.green  .kpi-val { color: #15803d; }
        .kpi-inner.blue   .kpi-val { color: #1d4ed8; }
        .kpi-inner.violet .kpi-val { color: #7c3aed; }
        .kpi-inner.sky    .kpi-val { color: #0369a1; }
        .kpi-inner.amber  .kpi-val { color: #b45309; }
        .kpi-inner.indigo .kpi-val { color: #4338ca; }
        .kpi-sub { font-size: 6.5pt; color: #94a3b8; margin-top: 3px; }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-bottom: 8px;
        }
        thead th {
            background: #0b1d35;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-size: 6.5pt;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        thead th.r { text-align: right; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: #fff; }
        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #f0f4f8;
            color: #1e293b;
            vertical-align: middle;
        }
        tbody td.r { text-align: right; font-weight: 600; }
        tbody td.muted { color: #64748b; }
        tfoot td {
            padding: 6px 8px;
            font-weight: 700;
            background: #f0f4ff;
            border-top: 2px solid #1d4ed8;
        }
        tfoot td.r { text-align: right; }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 99px;
            font-size: 6pt;
            font-weight: 700;
        }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-green  { background: #dcfce7; color: #14532d; }

        /* ── RANK TABLE ── */
        .rank-table { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
        .rank-table td { padding: 5px 8px; border-bottom: 1px solid #f0f4f8; }
        .rank-table tr:nth-child(even) td { background: #f8fafc; }
        .rank-num { font-weight: 700; color: #1d4ed8; width: 32px; text-align: center; }
        .rank-bar-wrap { height: 4px; background: #e2e8f0; border-radius: 99px; margin-top: 3px; width: 100%; }
        .status-dot-inline {
            display: inline-block;
            width: 8px; height: 8px;
            border-radius: 2px;
            margin-right: 6px;
            vertical-align: middle;
        }
        .status-pct-wrap {
            display: table-cell;
            vertical-align: middle;
            width: 90px;
        }
        .status-pct-bar-bg {
            height: 5px;
            background: #e2e8f0;
            border-radius: 99px;
            width: 100%;
        }
        .status-pct-bar {
            height: 5px;
            border-radius: 99px;
        }
        .rank-bar { height: 4px; background: linear-gradient(90deg, #1d4ed8, #38bdf8); border-radius: 99px; }
        .rank-count { font-weight: 700; color: #1d4ed8; text-align: right; white-space: nowrap; min-width: 40px; }

        /* ── STATUS GRID ── */
        .status-grid { display: table; width: 100%; }
        .status-row  { display: table-row; }
        .status-cell { display: table-cell; padding: 3px; width: 25%; }
        .status-inner {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 7px 9px;
            text-align: center;
        }
        .status-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; margin-bottom: 3px; }
        .status-lbl { font-size: 6pt; color: #64748b; display: block; }
        .status-cnt { font-size: 11pt; font-weight: 700; color: #0b1d35; display: block; }

        /* ── FOOTER ── */
        .page-footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            border-top: 1px solid #e2e8f0;
            padding: 5px 24px;
            font-size: 6.5pt;
            color: #94a3b8;
            background: #fff;
            display: flex;
            justify-content: space-between;
        }

        /* ── PAGE BREAK ── */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ── HEADER ── --}}
<div class="header">
    <table class="header-table">
        <tr>
            <td>
                <div class="header-badge">Manajemen Koleksi Museum</div>
                <h1>Dashboard <span>Transaksi</span></h1>
                <p class="header-sub">Laporan aktivitas penyewaan &amp; penjualan koleksi</p>
            </td>
            <td class="header-meta-cell">
                <div class="header-meta">
                    <strong>Diekspor oleh</strong><br>
                    {{ auth()->user()->name ?? 'Pengelola Museum' }}<br>
                    <strong>Tanggal</strong><br>
                    {{ now()->format('d M Y, H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    @if($activeFilters)
    <div class="filter-bar">
        @foreach($activeFilters as $label => $val)
        <span class="filter-chip">{{ $label }}: <span style="color:#38bdf8;font-weight:700;">{{ $val }}</span></span>
        @endforeach
    </div>
    @endif
</div>

<div class="content">

    {{-- ── SECTION 1: KPI Ringkasan ── --}}
    <div class="section-title">Ringkasan Keuangan & Transaksi</div>
    <div class="section-sub">Agregat seluruh transaksi berdasarkan filter aktif</div>

    <div class="kpi-grid">
        <div class="kpi-grid-row">
            <div class="kpi-box">
                <div class="kpi-inner green">
                    <div class="kpi-lbl">Total Pendapatan</div>
                    <div class="kpi-val">Rp {{ number_format($kpi['totalPendapatan'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">Dari transaksi selesai</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner blue">
                    <div class="kpi-lbl">Deposit Ditahan</div>
                    <div class="kpi-val">Rp {{ number_format($kpi['totalDepositDitahan'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">Penyewaan aktif</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner violet">
                    <div class="kpi-lbl">Refund Deposit</div>
                    <div class="kpi-val">Rp {{ number_format($kpi['refundDeposit'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">Dikembalikan ke penyewa</div>
                </div>
            </div>
        </div>
        <div class="kpi-grid-row">
            <div class="kpi-box">
                <div class="kpi-inner sky">
                    <div class="kpi-lbl">Total Transaksi</div>
                    <div class="kpi-val">{{ number_format($kpi['totalTransactions']) }}</div>
                    <div class="kpi-sub">Sewa + Jual</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner green">
                    <div class="kpi-lbl">Sewa Aktif</div>
                    <div class="kpi-val">{{ number_format($kpi['penyewaanAktif']) }}</div>
                    <div class="kpi-sub">Sedang berjalan</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner amber">
                    <div class="kpi-lbl">Perlu Verifikasi</div>
                    <div class="kpi-val">{{ number_format($kpi['menungguVerifikasi']) }}</div>
                    <div class="kpi-sub">Butuh tindakan</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 2: Tren Pendapatan Bulanan ── --}}
    @if(!empty($revenue['rows']))
    <div class="page-break"></div>
    <div class="section-title">Tren Pendapatan Bulanan</div>
    <div class="section-sub">Penyewaan vs penjualan — 12 bulan terakhir</div>

    <div class="kpi-grid" style="margin-bottom:10px;">
        <div class="kpi-grid-row">
            <div class="kpi-box">
                <div class="kpi-inner blue">
                    <div class="kpi-lbl">Total Pendapatan Sewa</div>
                    <div class="kpi-val" style="font-size:11pt;">Rp {{ number_format($revenue['totalSewa'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">12 bulan terakhir</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner green">
                    <div class="kpi-lbl">Total Pendapatan Jual</div>
                    <div class="kpi-val" style="font-size:11pt;">Rp {{ number_format($revenue['totalJual'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">12 bulan terakhir</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner sky">
                    <div class="kpi-lbl">Total Keseluruhan</div>
                    <div class="kpi-val" style="font-size:11pt;">Rp {{ number_format($revenue['totalSewa'] + $revenue['totalJual'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">Sewa + Jual</div>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="r">Pendapatan Sewa (Rp)</th>
                <th class="r">Pendapatan Jual (Rp)</th>
                <th class="r">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenue['rows'] as $row)
            <tr>
                <td>{{ $row['label'] }}</td>
                <td class="r" style="color:#1d4ed8;">{{ number_format($row['sewa'], 0, ',', '.') }}</td>
                <td class="r" style="color:#15803d;">{{ number_format($row['jual'], 0, ',', '.') }}</td>
                <td class="r">{{ number_format($row['total'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="r">{{ number_format($revenue['totalSewa'], 0, ',', '.') }}</td>
                <td class="r">{{ number_format($revenue['totalJual'], 0, ',', '.') }}</td>
                <td class="r">{{ number_format($revenue['totalSewa'] + $revenue['totalJual'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- ── SECTION 2B: Status Transaksi ── --}}
    @if(!empty($statusDistribution['rows']))
    <div class="page-break"></div>
    <div class="section-title">Status Transaksi</div>
    <div class="section-sub">Distribusi seluruh transaksi berdasarkan status — Total: {{ number_format($statusDistribution['total']) }}</div>

    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th class="r">Jumlah</th>
                <th class="r">Persentase</th>
                <th>Distribusi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statusDistribution['rows'] as $row)
            <tr>
                <td>
                    <span class="status-dot-inline" style="background:{{ $row['color'] }};"></span>
                    {{ $row['label'] }}
                </td>
                <td class="r">{{ number_format($row['count']) }}</td>
                <td class="r">{{ $row['percent'] }}%</td>
                <td>
                    <div class="status-pct-bar-bg">
                        <div class="status-pct-bar" style="width:{{ $row['percent'] }}%; background:{{ $row['color'] }};"></div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="r">{{ number_format($statusDistribution['total']) }}</td>
                <td class="r">100%</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- ── SECTION 2: Koleksi Terpopuler ── --}}
    @if(!empty($popular))
    <div class="page-break"></div>
    <div class="section-title">Koleksi Terpopuler</div>
    <div class="section-sub">Top {{ count($popular) }} koleksi berdasarkan frekuensi penyewaan</div>
    @php $maxPop = max(array_column($popular, 'rent_count') ?: [1]); @endphp
    <table class="rank-table">
        @foreach($popular as $i => $p)
        <tr>
            <td class="rank-num">{{ $i + 1 }}</td>
            <td>
                <div style="font-weight:700;font-size:7.5pt;">{{ $p['title'] ?? ('#'.$p['id']) }}</div>
                <div class="rank-bar-wrap">
                    <div class="rank-bar" style="width:{{ $maxPop > 0 ? round(($p['rent_count'] / $maxPop) * 100) : 0 }}%;"></div>
                </div>
            </td>
            <td class="rank-count">{{ number_format($p['rent_count']) }}×</td>
        </tr>
        @endforeach
    </table>
    @endif

    {{-- ── SECTION 3: Insight Penjualan ── --}}
    @if(!empty($salesRows))
    <div class="page-break"></div>
    <div class="section-title">Insight Penjualan Koleksi</div>
    <div class="section-sub">Koleksi terjual — diurutkan dari harga tertinggi</div>

    {{-- Summary strip --}}
    <div class="kpi-grid" style="margin-bottom:10px;">
        <div class="kpi-grid-row">
            <div class="kpi-box">
                <div class="kpi-inner green">
                    <div class="kpi-lbl">Total Terjual</div>
                    <div class="kpi-val">{{ $salesSummary['total_terjual'] }}</div>
                    <div class="kpi-sub">koleksi</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner blue">
                    <div class="kpi-lbl">Total Nilai Penjualan</div>
                    <div class="kpi-val" style="font-size:10pt;">Rp {{ number_format($salesSummary['total_nilai'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">termasuk ongkir</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner violet">
                    <div class="kpi-lbl">Koleksi Termahal</div>
                    <div class="kpi-val" style="font-size:8pt;line-height:1.3;">{{ $salesSummary['termahal_judul'] ?? '—' }}</div>
                    <div class="kpi-sub">{{ $salesSummary['termahal_harga'] ? 'Rp '.number_format($salesSummary['termahal_harga'], 0, ',', '.') : '' }}</div>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Koleksi</th>
                <th>Pembeli</th>
                <th>Tipe</th>
                <th class="r">Harga Beli</th>
                <th class="r">Ongkir</th>
                <th class="r">Total</th>
                <th>Tgl Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesRows as $r)
            <tr>
                <td style="font-weight:700;max-width:120px;word-break:break-word;">{{ $r['judul'] }}</td>
                <td class="muted">{{ $r['pembeli'] }}</td>
                <td><span class="badge {{ $r['buyer_type'] === 'b2b' ? 'badge-blue' : 'badge-gray' }}">{{ strtoupper($r['buyer_type']) }}</span></td>
                <td class="r" style="color:#15803d;">Rp {{ number_format($r['harga_beli'], 0, ',', '.') }}</td>
                <td class="r muted">Rp {{ number_format($r['shipping_cost'], 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format($r['total_bayar'], 0, ',', '.') }}</td>
                <td class="muted" style="white-space:nowrap;font-size:7pt;">{{ $r['completed_at'] ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td class="r">Rp {{ number_format(array_sum(array_column($salesRows, 'harga_beli')), 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format(array_sum(array_column($salesRows, 'shipping_cost')), 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format(array_sum(array_column($salesRows, 'total_bayar')), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- ── SECTION 4: Insight Penyewaan ── --}}
    @if(!empty($rentalRows))
    <div class="page-break"></div>
    <div class="section-title">Insight Penyewaan Koleksi</div>
    <div class="section-sub">Transaksi sewa selesai — diurutkan dari subtotal tertinggi</div>

    <div class="kpi-grid" style="margin-bottom:10px;">
        <div class="kpi-grid-row">
            <div class="kpi-box">
                <div class="kpi-inner blue">
                    <div class="kpi-lbl">Total Selesai</div>
                    <div class="kpi-val">{{ $rentalSummary['total_selesai'] }}</div>
                    <div class="kpi-sub">transaksi sewa</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner green">
                    <div class="kpi-lbl">Total Pendapatan Sewa</div>
                    <div class="kpi-val" style="font-size:10pt;">Rp {{ number_format($rentalSummary['total_pendapatan'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">dari payment berhasil</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-inner" style="border-color:#f97316;background:#fff7ed;">
                    <div class="kpi-lbl">Sewa Termahal</div>
                    <div class="kpi-val" style="font-size:8pt;line-height:1.3;color:#c2410c;">{{ $rentalSummary['termahal_judul'] ?? '—' }}</div>
                    <div class="kpi-sub">{{ $rentalSummary['termahal_subtotal'] ? 'Rp '.number_format($rentalSummary['termahal_subtotal'], 0, ',', '.') : '' }}</div>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Koleksi</th>
                <th>Penyewa</th>
                <th>Tipe</th>
                <th class="r">Durasi</th>
                <th class="r">Subtotal</th>
                <th class="r">Deposit</th>
                <th class="r">Ongkir</th>
                <th class="r">Total</th>
                <th>Periode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentalRows as $r)
            <tr>
                <td style="font-weight:700;max-width:110px;word-break:break-word;">{{ $r['judul'] }}</td>
                <td class="muted">{{ $r['penyewa'] }}</td>
                <td><span class="badge {{ $r['rental_type'] === 'instansi' ? 'badge-blue' : 'badge-gray' }}">{{ ucfirst($r['rental_type']) }}</span></td>
                <td class="r muted">{{ $r['duration_days'] }} hr</td>
                <td class="r" style="color:#1d4ed8;">Rp {{ number_format($r['subtotal'], 0, ',', '.') }}</td>
                <td class="r muted">Rp {{ number_format($r['deposit'], 0, ',', '.') }}</td>
                <td class="r muted">Rp {{ number_format($r['shipping_cost'], 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format($r['total_bayar'], 0, ',', '.') }}</td>
                <td class="muted" style="font-size:6.5pt;white-space:nowrap;">
                    {{ $r['start_date'] ?? '' }}@if($r['start_date'] && $r['end_date'])<br>{{ $r['end_date'] }}@endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td class="r">Rp {{ number_format(array_sum(array_column($rentalRows, 'subtotal')), 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format(array_sum(array_column($rentalRows, 'deposit')), 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format(array_sum(array_column($rentalRows, 'shipping_cost')), 0, ',', '.') }}</td>
                <td class="r">Rp {{ number_format(array_sum(array_column($rentalRows, 'total_bayar')), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @endif

</div>{{-- /content --}}

<div class="page-footer">
    <span>Museum Collection Management System &mdash; Laporan Transaksi</span>
    <span>Digenerate otomatis pada {{ now()->format('d M Y, H:i:s') }} WIB</span>
</div>

</body>
</html>