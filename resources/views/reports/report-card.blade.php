@extends('reports.layouts.pdf')

@section('content')
    <div class="title">Report Card</div>
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

    <div class="section-title">Subject Results</div>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th class="text-center">Score (%)</th>
                <th class="text-center">Grade</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['subjects'] as $subject)
            <tr>
                <td><strong>{{ $subject['subject'] }}</strong></td>
                <td class="text-center">{{ $subject['average'] }}%</td>
                <td class="text-center">
                    <span class="badge badge-{{ $subject['grade'] === 'F' ? 'danger' : ($subject['grade'] === 'A' ? 'success' : 'info') }}">
                        {{ $subject['grade'] }}
                    </span>
                </td>
                <td>{{ $subject['remark'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Summary</div>
    <div class="info-grid">
        <div class="info-box" style="text-align: center;">
            <div class="label">Overall Average</div>
            <div class="value" style="font-size: 24px;">{{ $data['summary']['overall_average'] }}%</div>
            <div class="label" style="margin-top: 3px;">Grade: {{ $data['summary']['overall_grade'] }}</div>
        </div>
        <div class="info-box" style="text-align: center;">
            <div class="label">Class Average</div>
            <div class="value" style="font-size: 24px;">{{ $data['summary']['class_average'] }}%</div>
        </div>
        @if($data['summary']['position'])
        <div class="info-box" style="text-align: center;">
            <div class="label">Class Position</div>
            <div class="value" style="font-size: 24px;">{{ $data['summary']['position'] }}<small style="font-size: 12px;">/{{ $data['summary']['total_students'] }}</small></div>
            <div class="label" style="margin-top: 3px;">{{ $data['summary']['remark'] }}</div>
        </div>
        @endif
    </div>

    <div style="margin-top: 20px;">
        <div class="section-title">Teacher's Comments</div>
        <div style="border: 1px solid #e5e7eb; padding: 10px; min-height: 60px; font-size: 11px; color: #666;">
            .........................................................................................................
        </div>
    </div>

    <div style="margin-top: 15px;">
        <div class="section-title">Headmaster's Comments</div>
        <div style="border: 1px solid #e5e7eb; padding: 10px; min-height: 60px; font-size: 11px; color: #666;">
            .........................................................................................................
        </div>
    </div>

    <div style="margin-top: 30px; display: flex; justify-content: space-between;">
        <div style="text-align: center; width: 30%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Teacher</strong>
            </div>
        </div>
        <div style="text-align: center; width: 30%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Parent/Guardian</strong>
            </div>
        </div>
        <div style="text-align: center; width: 30%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Headmaster</strong>
            </div>
        </div>
    </div>
@endsection
