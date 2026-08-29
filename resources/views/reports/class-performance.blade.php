@extends('reports.layouts.pdf')

@section('content')
    <div class="title">Class Performance Report</div>
    <div class="subtitle">{{ $data['class_level']['name'] }} — {{ $data['term']['academic_year'] }} {{ $data['term']['name'] }}</div>

    <div class="info-grid">
        <div class="info-box" style="text-align: center;">
            <div class="label">Class Average</div>
            <div class="value" style="font-size: 24px;">{{ $data['summary']['class_average'] }}%</div>
            <div class="label" style="margin-top: 3px;">Grade: {{ $data['summary']['class_grade'] }}</div>
        </div>
        <div class="info-box" style="text-align: center;">
            <div class="label">Pass Rate</div>
            <div class="value" style="font-size: 24px;">{{ $data['summary']['pass_rate'] }}%</div>
        </div>
        <div class="info-box" style="text-align: center;">
            <div class="label">Students Graded</div>
            <div class="value" style="font-size: 24px;">{{ $data['summary']['students_graded'] }}/{{ $data['summary']['total_students'] }}</div>
        </div>
    </div>

    <div class="section-title">Subject Performance</div>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th class="text-center">Average (%)</th>
                <th class="text-center">Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['subject_performance'] as $subject)
            <tr>
                <td><strong>{{ $subject['subject'] }}</strong></td>
                <td class="text-center">{{ $subject['average'] }}%</td>
                <td class="text-center">
                    <span class="badge badge-{{ $subject['grade'] === 'F' ? 'danger' : ($subject['grade'] === 'A' ? 'success' : 'info') }}">
                        {{ $subject['grade'] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Student Rankings</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Admission No.</th>
                <th>Stream</th>
                <th class="text-center">Average (%)</th>
                <th class="text-center">Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['students'] as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $student['name'] }}</strong></td>
                <td>{{ $student['admission_number'] ?? 'N/A' }}</td>
                <td>{{ $student['stream'] }}</td>
                <td class="text-center">{{ $student['average'] ?? 'N/A' }}%</td>
                <td class="text-center">
                    @if($student['average'] !== null)
                    <span class="badge badge-{{ $student['grade'] === 'F' ? 'danger' : ($student['grade'] === 'A' ? 'success' : 'info') }}">
                        {{ $student['grade'] }}
                    </span>
                    @else
                    N/A
                    @endif
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
