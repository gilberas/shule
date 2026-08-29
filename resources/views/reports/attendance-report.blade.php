@extends('reports.layouts.pdf')

@section('content')
    <div class="title">Attendance Report</div>
    <div class="subtitle">{{ $data['term']['academic_year'] }} — {{ $data['term']['name'] }}</div>

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

    <div class="section-title">Attendance Summary</div>
    <div class="info-grid">
        <div class="info-box" style="text-align: center;">
            <div class="label">Attendance Rate</div>
            <div class="value {{ $data['summary']['attendance_rate'] >= 75 ? 'text-success' : ($data['summary']['attendance_rate'] >= 50 ? 'text-warning' : 'text-danger') }}" style="font-size: 24px;">
                {{ $data['summary']['attendance_rate'] }}%
            </div>
        </div>
        <div class="info-box" style="text-align: center;">
            <div class="label">Total Days</div>
            <div class="value" style="font-size: 24px;">{{ $data['summary']['total_days'] }}</div>
        </div>
        <div class="info-box" style="text-align: center;">
            <div class="label">Present</div>
            <div class="value text-success" style="font-size: 24px;">{{ $data['summary']['present'] }}</div>
        </div>
        <div class="info-box" style="text-align: center;">
            <div class="label">Absent</div>
            <div class="value text-danger" style="font-size: 24px;">{{ $data['summary']['absent'] }}</div>
        </div>
        <div class="info-box" style="text-align: center;">
            <div class="label">Excused</div>
            <div class="value text-warning" style="font-size: 24px;">{{ $data['summary']['excused'] }}</div>
        </div>
    </div>

    <div class="section-title">Attendance by Subject</div>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th class="text-center">Total</th>
                <th class="text-center">Present</th>
                <th class="text-center">Absent</th>
                <th class="text-center">Excused</th>
                <th class="text-center">Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_subject'] as $subject)
            <tr>
                <td><strong>{{ $subject['subject'] }}</strong></td>
                <td class="text-center">{{ $subject['total'] }}</td>
                <td class="text-center text-success">{{ $subject['present'] }}</td>
                <td class="text-center text-danger">{{ $subject['absent'] }}</td>
                <td class="text-center text-warning">{{ $subject['excused'] }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $subject['rate'] >= 75 ? 'success' : ($subject['rate'] >= 50 ? 'warning' : 'danger') }}">
                        {{ $subject['rate'] }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between;">
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Class Teacher</strong>
            </div>
        </div>
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Headmaster</strong>
            </div>
        </div>
    </div>
@endsection
