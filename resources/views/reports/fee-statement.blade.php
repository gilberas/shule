@extends('reports.layouts.pdf')

@section('content')
    <div class="title">Fee Statement</div>
    <div class="subtitle">Period: {{ $data['period']['from'] }} to {{ $data['period']['to'] }}</div>

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

    <div class="section-title">Transaction History</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Receipt No.</th>
                <th class="text-right">Debit (TSh)</th>
                <th class="text-right">Credit (TSh)</th>
                <th class="text-right">Balance (TSh)</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['entries'] as $entry)
            <tr>
                <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>
                <td>{{ $entry['description'] }}</td>
                <td>{{ $entry['receipt'] }}</td>
                <td class="text-right">{{ number_format($entry['debit'], 0, '.', ',') }}</td>
                <td class="text-right text-success">{{ $entry['credit'] > 0 ? number_format($entry['credit'], 0, '.', ',') : '-' }}</td>
                <td class="text-right font-bold">{{ number_format($entry['balance'], 0, '.', ',') }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $entry['status'] === 'Paid' ? 'success' : ($entry['status'] === 'Pending' ? 'warning' : 'info') }}">
                        {{ $entry['status'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background: #f3f4f6;">
                <td colspan="3">TOTAL</td>
                <td class="text-right">{{ number_format($data['summary']['total_debit'], 0, '.', ',') }}</td>
                <td class="text-right text-success">{{ number_format($data['summary']['total_credit'], 0, '.', ',') }}</td>
                <td class="text-right">{{ number_format($data['summary']['closing_balance'], 0, '.', ',') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="info-grid">
        <div class="info-box" style="text-align: center; border: 2px solid {{ $data['summary']['closing_balance'] > 0 ? '#d97706' : '#059669' }};">
            <div class="label">Outstanding Balance</div>
            <div class="value {{ $data['summary']['closing_balance'] > 0 ? 'text-warning' : 'text-success' }}" style="font-size: 20px;">
                {{ number_format($data['summary']['closing_balance'], 0, '.', ',') }} TSh
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; display: flex; justify-content: space-between;">
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Prepared By</strong>
            </div>
        </div>
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Accountant Signature & Stamp</strong>
            </div>
        </div>
    </div>
@endsection
