@extends('reports.layouts.pdf')

@section('content')
    <div class="title">Student Register</div>
    <div class="subtitle">Complete list of enrolled students</div>

    <div class="info-grid">
        <div class="info-box">
            <div class="label">Total Students</div>
            <div class="value">{{ count($data['students']) }}</div>
        </div>
        <div class="info-box">
            <div class="label">Generated On</div>
            <div class="value">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Admission No.</th>
                <th>Student Name</th>
                <th>Class</th>
                <th>Stream</th>
                <th>Parent/Guardian</th>
                <th>Contact</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['students'] as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student['admission_number'] ?? 'N/A' }}</td>
                <td><strong>{{ $student['name'] }}</strong></td>
                <td>{{ $student['class'] }}</td>
                <td>{{ $student['stream'] }}</td>
                <td>{{ $student['parent_name'] }}</td>
                <td>{{ $student['contact'] }}</td>
                <td>
                    <span class="badge badge-{{ $student['status'] === 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($student['status']) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: space-between;">
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Prepared By</strong>
            </div>
        </div>
        <div style="text-align: center; width: 40%;">
            <div style="border-top: 1px solid #1a1a1a; margin-top: 40px; padding-top: 5px;">
                <strong>Headmaster Signature & Stamp</strong>
            </div>
        </div>
    </div>
@endsection
