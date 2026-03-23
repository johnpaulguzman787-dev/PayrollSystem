<!DOCTYPE html>
<html>
<head>
    <title>Attendance Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Attendance Logs</h3>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
<div class="card-header fw-bold">Add Attendance</div>
<div class="card-body">
<form method="POST" action="/attendance-logs/store" class="row g-3">
@csrf

<div class="col-md-3">
<select name="employee_id" class="form-control" required>
<option value="">Select Employee</option>
@foreach($employees as $emp)
<option value="{{ $emp->employee_id }}">
{{ $emp->fname }} {{ $emp->lname }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<select name="shift_id" class="form-control" required>
<option value="">Select Shift</option>
@foreach($shifts as $shift)
<option value="{{ $shift->id }}">
{{ $shift->name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<input type="date" name="attendance_date" class="form-control" required>
</div>

<div class="col-md-2">
<input type="time" name="time_in" class="form-control">
</div>

<div class="col-md-2">
<input type="time" name="time_out" class="form-control">
</div>

<div class="col-md-1">
<button class="btn btn-primary w-100">Save</button>
</div>

</form>
</div>
</div>

<table class="table table-bordered">
<thead>
<tr>
<th>Date</th>
<th>Employee</th>
<th>Shift</th>
<th>Time In</th>
<th>Time Out</th>
<th>Late (min)</th>
<th>Undertime</th>
<th>Overtime</th>
<th>Absent</th>
</tr>
</thead>

<tbody>
@foreach($logs as $log)
<tr>
<td>{{ $log->attendance_date }}</td>
<td>{{ $log->fname }} {{ $log->lname }}</td>
<td>{{ $log->shift_name }}</td>
<td>{{ $log->time_in }}</td>
<td>{{ $log->time_out }}</td>
<td>{{ $log->late_minutes }}</td>
<td>{{ $log->undertime_minutes }}</td>
<td>{{ $log->overtime_minutes }}</td>
<td>
@if($log->is_absent)
<span class="badge bg-danger">Absent</span>
@else
<span class="badge bg-success">Present</span>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>

</body>
</html>