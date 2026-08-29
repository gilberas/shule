<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Report' }} — {{ config('school.short_name', 'TSMS') }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            color: #1e3a5f;
            margin-bottom: 2px;
        }
        .header .motto {
            font-style: italic;
            color: #666;
            font-size: 11px;
        }
        .header .contact {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #1e3a5f;
            margin: 15px 0;
            text-transform: uppercase;
        }
        .subtitle {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #1e3a5f;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        table td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .info-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .info-box {
            flex: 1;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        .info-box .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            font-weight: bold;
        }
        .info-box .value {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a5f;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a5f;
            margin: 15px 0 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-success { color: #059669; }
        .text-danger { color: #dc2626; }
        .text-warning { color: #d97706; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0,0,0,0.03);
            font-weight: bold;
            z-index: -1;
            white-space: nowrap;
        }
        .two-col { display: flex; gap: 15px; }
        .two-col > div { flex: 1; }
    </style>
</head>
<body>
    <div class="watermark">{{ config('school.short_name', 'TSMS') }}</div>

    <div class="header">
        <h1>{{ config('school.name', 'Tanzania School Management System') }}</h1>
        <div class="motto">{{ config('school.motto', 'Knowledge for Excellence') }}</div>
        <div class="contact">
            {{ config('school.address') }} | Tel: {{ config('school.phone') }} | Email: {{ config('school.email') }}
        </div>
    </div>

    @yield('content')

    <div class="footer">
        <div>Generated: {{ now()->format('d M Y H:i') }}</div>
        <div>{{ config('school.short_name', 'TSMS') }} — {{ config('school.registration_number') }}</div>
    </div>
</body>
</html>
