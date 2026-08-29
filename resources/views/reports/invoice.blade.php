@extends('reports.layouts.pdf')

@section('content')
    <div class="title">Fee Invoice</div>
    <div class="subtitle">Invoice #: {{ $data['invoice_number'] }} | Date: {{ $data['date'] }}</div>

    <div class="info-grid">
        <div class="info-box">
            <div class="label">Student Name</div>
            <div class="value">{{ $data['student']['name'] }}</div>
        </div>
        <div class="info-box">
            <div class="label">Admission No.</div>
            <div class="value">{{ $data['student']['admission_number'] ?? 'N/A' }}</div>
        </div>
        <div class="info-box">
            <div class="label">Class</div>
            <div class="value">{{ $data['student']['class'] }} — {{ $data['student']['stream'] }}</div>
        </div>
    </div>

    <div class="two-col">
        <div class="info-box">
            <div class="label">Parent/Guardian</div>
            <div class="value" style="font-size: 12px;">{{ $data['student']['parent_name'] }}</div>
        </div>
        <div class="info-box">
            <div class="label">Contact</div>
            <div class="value" style="font-size: 12px;">{{ $data['student']['parent_contact'] }}</div>
        </div>
    </div>

    <div class="section-title">Fee Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Fee Type</th>
                <th class="text-right">Total Amount</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['line_items'] as $item)
            <tr>
                <td>
                    <strong>{{ $item['name'] }}</strong>
                    @if($item['description'])
                        <br><small style="color: #666;">{{ $item['description'] }}</small>
                    @endif
                </td>
                <td class="text-right">{{ number_format($item['amount'], 0, '.', ',') }} TSh</td>
                <td class="text-right text-success">{{ number_format($item['paid'], 0, '.', ',') }} TSh</td>
                <td class="text-right {{ $item['balance'] > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($item['balance'], 0, '.', ',') }} TSh
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background: #f3f4f6;">
                <td>TOTAL</td>
                <td class="text-right">{{ number_format($data['total_expected'], 0, '.', ',') }} TSh</td>
                <td class="text-right text-success">{{ number_format($data['total_paid'], 0, '.', ',') }} TSh</td>
                <td class="text-right {{ $data['total_balance'] > 0 ? 'text-danger' : 'text-success' }}">
                    {{ number_format($data['total_balance'], 0, '.', ',') }} TSh
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="info-grid">
        <div class="info-box" style="text-align: center; border: 2px solid {{ $data['total_balance'] > 0 ? '#d97706' : '#059669' }};">
            <div class="label">Amount Due</div>
            <div class="value {{ $data['total_balance'] > 0 ? 'text-warning' : 'text-success' }}" style="font-size: 20px;">
                {{ number_format($data['total_balance'], 0, '.', ',') }} TSh
            </div>
            <div class="label" style="margin-top: 5px;">{{ $data['status'] }}</div>
        </div>
    </div>

    <div class="section-title">Payment Instructions</div>
    <p style="font-size: 11px; color: #555;">
        Please make payment via bank transfer, cash, or mobile money. 
        Retain this invoice and present the payment receipt for confirmation.
    </p>

    <div style="margin-top: 30px; display: flex; justify-content: space-between;">
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Parent/Guardian Signature</strong>
            </div>
        </div>
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Accountant Signature & Stamp</strong>
            </div>
        </div>
    </div>
@endsection
