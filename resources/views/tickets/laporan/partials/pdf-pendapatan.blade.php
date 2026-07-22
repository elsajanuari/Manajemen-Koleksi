<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Museum</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            padding: 30px 40px;
            color: #1a1a2e;
            line-height: 1.5;
            background: #ffffff;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #1a1a2e;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo {
            width: 70px;
            height: 70px;
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .museum-name h1 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
            letter-spacing: 1px;
        }
        .museum-name p {
            font-size: 9px;
            color: #4a4a6a;
            margin: 2px 0 0;
        }
        .header-right {
            text-align: right;
        }
        .header-right .doc-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header-right .doc-number {
            font-size: 11px;
            color: #4a4a6a;
            font-weight: 600;
            margin-top: 3px;
        }
        .header-right .doc-date {
            font-size: 10px;
            color: #6a6a8a;
            margin-top: 2px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: #f0f0f5;
            padding: 8px 12px;
            margin: 15px 0 10px;
            border-left: 4px solid #1a1a2e;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 15px 0 20px;
        }
        .summary-card {
            padding: 15px 20px;
            border-radius: 6px;
            color: #ffffff;
        }
        .summary-card.green { background: #22c55e; }
        .summary-card.blue { background: #3b82f6; }
        .summary-card.purple { background: #8b5cf6; }
        .summary-card .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        .summary-card .value {
            font-size: 18px;
            font-weight: 700;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin: 10px 0 15px;
        }
        table thead th {
            background: #1a1a2e;
            color: #ffffff;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e8e8ee;
        }
        .text-right { text-align: right; }
        .total-row {
            background: #f8f8fc;
            font-weight: 700;
        }
        .total-row td {
            padding: 10px;
            border-top: 2px solid #1a1a2e;
        }
        .footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #e8e8ee;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #6a6a8a;
        }
        .stamp {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #d0d0dd;
        }
        .stamp-text {
            text-align: center;
            font-size: 9px;
            color: #6a6a8a;
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
            <div class="doc-title">Laporan Pendapatan</div>
            <div class="doc-number">No. LAP/{{ date('Ymd') }}/{{ rand(100, 999) }}</div>
            <div class="doc-date">Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</div>
            <div class="doc-date">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="section-title">Rincian Pendapatan</div>
    <table>
        <thead>
            <tr>
                <th width="60%">Periode</th>
                <th width="40%" class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chart_labels as $index => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-right">Rp {{ number_format($chart_data[$index], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td><strong>TOTAL KESELURUHAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <span>Dokumen ini dicetak secara elektronik</span>
        <span>Museum MK. Lesmana • {{ date('Y') }}</span>
    </div>

    <div class="stamp">
        <div class="stamp-text">Laporan ini merupakan dokumen resmi Museum MK. Lesmana</div>
    </div>

</body>
</html>