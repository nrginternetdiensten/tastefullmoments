<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factuur {{ $invoice->invoice_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.6;
        }

        .container {
            padding: 40px;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: #0ea5e9;
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 18pt;
            font-weight: bold;
            margin-top: 20px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }

        .info-value {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table thead {
            background-color: #f3f4f6;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #e5e7eb;
        }

        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        table th.text-right,
        table td.text-right {
            text-align: right;
        }

        table th.text-center,
        table td.text-center {
            text-align: center;
        }

        .totals {
            margin-top: 30px;
            float: right;
            width: 300px;
        }

        .totals table {
            margin: 0;
        }

        .totals table td {
            border: none;
            padding: 8px 12px;
        }

        .totals .total-row {
            font-weight: bold;
            font-size: 12pt;
            border-top: 2px solid #0ea5e9;
        }

        .footer {
            margin-top: 80px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8pt;
            color: #666;
            clear: both;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name">{{ config('app.name') }}</div>
            <div class="invoice-title">FACTUUR {{ $invoice->invoice_id }}</div>
        </div>

        <div class="info-grid">
            <div class="info-column">
                <div class="info-section">
                    <div class="info-label">Factuur Aan:</div>
                    <div class="info-value">
                        @if($invoice->account)
                            <strong>{{ $invoice->account->name }}</strong><br>
                            @if($invoice->account->address)
                                {{ $invoice->account->address }}<br>
                            @endif
                            @if($invoice->account->city || $invoice->account->postal_code)
                                {{ $invoice->account->postal_code }} {{ $invoice->account->city }}<br>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-column">
                <div class="info-section">
                    <div class="info-label">Factuurdatum:</div>
                    <div class="info-value">{{ $invoice->created_at->format('d-m-Y') }}</div>

                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        @if($invoice->status)
                            {{ $invoice->status->name }}
                        @else
                            -
                        @endif
                    </div>

                    @if($invoice->user)
                        <div class="info-label">Behandelaar:</div>
                        <div class="info-value">{{ $invoice->user->name }}</div>
                    @endif
                </div>
            </div>
        </div>

        @if($invoice->lines && $invoice->lines->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Omschrijving</th>
                        <th class="text-center">Aantal</th>
                        <th class="text-right">Prijs</th>
                        <th class="text-right">BTW</th>
                        <th class="text-right">Totaal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->lines as $line)
                        <tr>
                            <td>
                                <strong>{{ $line->name }}</strong>
                                @if($line->description)
                                    <br><small>{{ $line->description }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $line->quantity }}</td>
                            <td class="text-right">€ {{ number_format($line->unit_price, 2, ',', '.') }}</td>
                            <td class="text-right">
                                @if($line->tax)
                                    {{ $line->tax->name }} ({{ $line->tax->percentage }}%)
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">€ {{ number_format($line->total, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotaal (excl. BTW):</td>
                    <td class="text-right">€ {{ number_format($invoice->total_exc, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>BTW:</td>
                    <td class="text-right">€ {{ number_format($invoice->total_tax, 2, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Totaal (incl. BTW):</td>
                    <td class="text-right">€ {{ number_format($invoice->total, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>{{ config('app.name') }} - Gegenereerd op {{ now()->format('d-m-Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
