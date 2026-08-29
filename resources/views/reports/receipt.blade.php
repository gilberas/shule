@extends('reports.layouts.pdf')

@section('content')
    <div class="title">Payment Receipt</div>
    <div class="subtitle">Receipt #: {{ $data['receipt_number'] }}</div>

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
            <div class="label">Payment Date</div>
            <div class="value">{{ \Carbon\Carbon::parse($data['date'])->format('d M Y') }}</div>
        </div>
        <div class="info-box">
            <div class="label">Payment Method</div>
            <div class="value">{{ $data['payment']['method'] }}</div>
        </div>
        <div class="info-box">
            <div class="label">Status</div>
            <div class="value">
                <span class="badge badge-{{ $data['payment']['status'] === 'Paid' ? 'success' : ($data['payment']['status'] === 'Pending' ? 'warning' : 'info') }}">
                    {{ $data['payment']['status'] }}
                </span>
            </div>
        </div>
    </div>

    <div class="section-title">Fee Details</div>
    <table>
        <thead>
            <tr>
                <th>Fee Type</th>
                <th>Term</th>
                <th>Academic Year</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $data['fee']['name'] }}</td>
                <td>{{ $data['fee']['term'] }}</td>
                <td>{{ $data['fee']['academic_year'] }}</td>
                <td class="text-right font-bold">{{ number_format($data['fee']['amount'], 0, '.', ',') }} TSh</td>
            </tr>
        </tbody>
    </table>

    <div class="info-grid">
        <div class="info-box" style="text-align: center; border: 2px solid #059669;">
            <div class="label">Amount Paid</div>
            <div class="value text-success" style="font-size: 20px;">{{ number_format($data['payment']['amount'], 0, '.', ',') }} TSh</div>
        </div>
    </div>

    @if($data['notes'])
    <div class="section-title">Notes</div>
    <p style="font-size: 11px; color: #555;">{{ $data['notes'] }}</p>
    @endif

    <div class="section-title">Parent/Guardian</div>
    <p style="font-size: 11px;">{{ $data['student']['parent_name'] }}</p>

    <div style="margin-top: 30px; display: flex; justify-content: space-between;">
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Student Signature</strong>
            </div>
        </div>
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Authorized Signature & Stamp</strong>
            </div>
        </div>
    </div>
@endsection
