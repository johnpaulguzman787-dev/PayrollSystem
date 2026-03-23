<!DOCTYPE html>
<html>
<head>
<title>Government Contributions</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .content {margin-left: 280px;padding: 25px;min-height: 100vh;transition: all 0.3s ease;}
        body { font-family: Arial; background:#f4f4f4; margin:0; padding:0;}
    </style>
</head>

<body class="content">

 @include('partials.sidebar')

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Government Contributions</h2>

</div>

<div class="card mb-4">
<div class="card-header bg-dark text-white">
<h4>Government Report Summary</h4>
</div>

<div class="card-body">

<div class="row">

<div class="col-md-3">
<div class="card border-primary">
<div class="card-body">
<h5>Total Employees</h5>
<h3>{{ $totalEmployees }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card border-success">
<div class="card-body">
<h5>Total SSS Employee Share</h5>
<h3>₱ {{ number_format($totalSSSEmployee,2) }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card border-warning">
<div class="card-body">
<h5>Total SSS Employer Share</h5>
<h3>₱ {{ number_format($totalSSSEmployer,2) }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card border-info">
<div class="card-body">
<h5>PhilHealth Total Contribution</h5>
<h3>₱ {{ number_format($totalPhilhealth,2) }}</h3>
</div>
</div>
</div>

</div>

</div>
</div>

<ul class="nav nav-tabs">

<li class="nav-item">
<button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sss">SSS</button>
</li>

<li class="nav-item">
<button class="nav-link" data-bs-toggle="tab" data-bs-target="#philhealth">PhilHealth</button>
</li>

<li class="nav-item">
<button class="nav-link" data-bs-toggle="tab" data-bs-target="#pagibig">Pag-IBIG</button>
</li>

<li class="nav-item">
<button class="nav-link" data-bs-toggle="tab" data-bs-target="#tax">Withholding Tax</button>
</li>

</ul>

<div class="tab-content mt-4">

<!-- ================= SSS TAB ================= -->

<div class="tab-pane fade show active" id="sss">

<h4>SSS Contributions</h4>

<button class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#addSSS">
Add SSS
</button>

<table class="table table-bordered">

<tr>
<th>Salary From</th>
<th>Salary To</th>
<th>MSC</th>
<th>Employee</th>
<th>Employer</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

@foreach($sss as $row)

<tr>

<td>{{ $row->salary_from }}</td>
<td>{{ $row->salary_to }}</td>
<td>{{ $row->monthly_salary_credit }}</td>
<td>{{ $row->employee_share }}</td>
<td>{{ $row->employer_share }}</td>
<td>{{ $row->total }}</td>

<td>
@if($row->status == 'active')
<span class="badge bg-success">Active</span>
@else
<span class="badge bg-secondary">Inactive</span>
@endif
</td>
<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editSSS{{ $row->id }}">
Edit
</button>

<form method="POST" action="{{ route('sss.toggle',$row->id) }}" style="display:inline;">
@csrf

@if($row->status == 'active')
<button class="btn btn-danger btn-sm">Deactivate</button>
@else
<button class="btn btn-success btn-sm">Activate</button>
@endif

</form>

</td>

</tr>

<!-- EDIT MODAL -->

<div class="modal fade" id="editSSS{{ $row->id }}">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST" action="{{ route('sss.update',$row->id) }}">
@csrf

<div class="modal-header">
<h5>Edit SSS</h5>
</div>

<div class="modal-body">

<input type="number" step="0.01" name="salary_from" value="{{ $row->salary_from }}" class="form-control mb-2">

<input type="number" step="0.01" name="salary_to" value="{{ $row->salary_to }}" class="form-control mb-2">

<input type="number" step="0.01" name="monthly_salary_credit" value="{{ $row->monthly_salary_credit }}" class="form-control mb-2">

<input type="number" step="0.01" name="employee_share" value="{{ $row->employee_share }}" class="form-control mb-2">

<input type="number" step="0.01" name="employer_share" value="{{ $row->employer_share }}" class="form-control">

</div>

<div class="modal-footer">

<button class="btn btn-success">Update</button>

</div>

</form>

</div>
</div>
</div>

@endforeach

</table>

</div>

<!-- ================= ADD SSS MODAL ================= -->

<div class="modal fade" id="addSSS">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="{{ route('sss.store') }}">
@csrf

<div class="modal-header">
<h5>Add SSS Contribution</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="number" step="0.01" name="salary_from" class="form-control mb-2" placeholder="Salary From">

<input type="number" step="0.01" name="salary_to" class="form-control mb-2" placeholder="Salary To">

<input type="number" step="0.01" name="monthly_salary_credit" class="form-control mb-2" placeholder="MSC">

<input type="number" step="0.01" name="employee_share" class="form-control mb-2" placeholder="Employee Share">

<input type="number" step="0.01" name="employer_share" class="form-control" placeholder="Employer Share">

</div>

<div class="modal-footer">
<button class="btn btn-success">Save</button>
</div>

</form>

</div>
</div>
</div>

<!-- ================= PHILHEALTH TAB ================= -->

<div class="tab-pane fade" id="philhealth">

<h4>PhilHealth Settings</h4>

<table class="table table-bordered">

<tr>
<th>Rate</th>
<th>Employee</th>
<th>Employer</th>
<th>Min Salary</th>
<th>Max Salary</th>
<th>Status</th>
<th>Action</th>
</tr>

@foreach($philhealth as $row)

<tr>

<td>{{ $row->contribution_rate }}%</td>
<td>{{ $row->employee_share }}%</td>
<td>{{ $row->employer_share }}%</td>
<td>{{ $row->min_salary }}</td>
<td>{{ $row->max_salary }}</td>

<td>
@if($row->status == 'active')
<span class="badge bg-success">Active</span>
@else
<span class="badge bg-secondary">Inactive</span>
@endif
</td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editPhilhealth{{ $row->id }}">
Edit
</button>

<form method="POST" action="{{ route('philhealth.toggle',$row->id) }}" style="display:inline;">
@csrf

@if($row->status == 'active')
<button class="btn btn-danger btn-sm">Deactivate</button>
@else
<button class="btn btn-success btn-sm">Activate</button>
@endif

</form>
</td>

</tr>

<!-- EDIT PHILHEALTH MODAL -->

<div class="modal fade" id="editPhilhealth{{ $row->id }}">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="{{ route('philhealth.update',$row->id) }}">
@csrf

<div class="modal-header">
<h5>Edit PhilHealth</h5>
</div>

<div class="modal-body">

<input type="number" step="0.01" name="contribution_rate" value="{{ $row->contribution_rate }}" class="form-control mb-2">

<input type="number" step="0.01" name="employee_share" value="{{ $row->employee_share }}" class="form-control mb-2">

<input type="number" step="0.01" name="employer_share" value="{{ $row->employer_share }}" class="form-control mb-2">

<input type="number" step="0.01" name="min_salary" value="{{ $row->min_salary }}" class="form-control mb-2">

<input type="number" step="0.01" name="max_salary" value="{{ $row->max_salary }}" class="form-control">

</div>

<div class="modal-footer">
<button class="btn btn-success">Update</button>
</div>

</form>

</div>
</div>
</div>

@endforeach

</table>

</div>

<!-- ================= PAGIBIG TAB ================= -->

<div class="tab-pane fade" id="pagibig">

<h4>Pag-IBIG Settings</h4>

<table class="table table-bordered">

<tr>
<th>Salary Cap</th>
<th>Rate ≤ Threshold</th>
<th>Rate > Threshold</th>
<th>Threshold</th>
<th>Status</th>
<th>Action</th>
</tr>

@foreach($pagibig as $row)

<tr>

<td>{{ $row->salary_cap }}</td>
<td>{{ $row->employee_rate_low }}%</td>
<td>{{ $row->employee_rate_high }}%</td>
<td>{{ $row->salary_threshold }}</td>

<td>
@if($row->status == 'active')
<span class="badge bg-success">Active</span>
@else
<span class="badge bg-secondary">Inactive</span>
@endif
</td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editPagibig{{ $row->id }}">
Edit
</button>

<form method="POST" action="{{ route('pagibig.toggle',$row->id) }}" style="display:inline;">
@csrf

@if($row->status == 'active')
<button class="btn btn-danger btn-sm">Deactivate</button>
@else
<button class="btn btn-success btn-sm">Activate</button>
@endif

</form>

</td>

</tr>

<!-- EDIT PAGIBIG MODAL -->

<div class="modal fade" id="editPagibig{{ $row->id }}">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="{{ route('pagibig.update',$row->id) }}">
@csrf

<div class="modal-header">
<h5>Edit Pag-IBIG</h5>
</div>

<div class="modal-body">

<input type="number" step="0.01" name="salary_cap" value="{{ $row->salary_cap }}" class="form-control mb-2">

<input type="number" step="0.01" name="employee_rate_low" value="{{ $row->employee_rate_low }}" class="form-control mb-2">

<input type="number" step="0.01" name="employee_rate_high" value="{{ $row->employee_rate_high }}" class="form-control mb-2">

<input type="number" step="0.01" name="salary_threshold" value="{{ $row->salary_threshold }}" class="form-control">

</div>

<div class="modal-footer">
<button class="btn btn-success">Update</button>
</div>

</form>

</div>
</div>
</div>

@endforeach

</table>

</div>

<!-- ================= TAX TAB ================= -->

<div class="tab-pane fade" id="tax">

<h4>Withholding Tax</h4>

<button class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#addTax">
Add Tax
</button>

<table class="table table-bordered">

<tr>
<th>Income From</th>
<th>Income To</th>
<th>Base Tax</th>
<th>Tax Rate</th>
<th>Excess Over</th>
<th>Status</th>
<th>Action</th>
</tr>

@foreach($tax as $row)

<tr>

<td>{{ $row->income_from }}</td>
<td>{{ $row->income_to }}</td>
<td>{{ $row->base_tax }}</td>
<td>{{ $row->tax_rate }}</td>
<td>{{ $row->excess_over }}</td>

<td>
@if($row->status == 'active')
<span class="badge bg-success">Active</span>
@else
<span class="badge bg-secondary">Inactive</span>
@endif
</td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editTax{{ $row->id }}">
Edit
</button>

<form method="POST" action="{{ route('tax.toggle',$row->id) }}" style="display:inline;">
@csrf

@if($row->status == 'active')
<button class="btn btn-danger btn-sm">Deactivate</button>
@else
<button class="btn btn-success btn-sm">Activate</button>
@endif

</form>

</td>

</tr>

<!-- ================= EDIT TAX MODAL ================= -->

<div class="modal fade" id="editTax{{ $row->id }}">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="{{ route('tax.update',$row->id) }}">
@csrf

<div class="modal-header">
<h5>Edit Tax</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="number" step="0.01" name="income_from" value="{{ $row->income_from }}" class="form-control mb-2">

<input type="number" step="0.01" name="income_to" value="{{ $row->income_to }}" class="form-control mb-2">

<input type="number" step="0.01" name="base_tax" value="{{ $row->base_tax }}" class="form-control mb-2">

<input type="number" step="0.0001" name="tax_rate" value="{{ $row->tax_rate }}" class="form-control mb-2">

<input type="number" step="0.01" name="excess_over" value="{{ $row->excess_over }}" class="form-control">

</div>

<div class="modal-footer">
<button class="btn btn-success">Update</button>
</div>

</form>


</div>
</div>
</div>

@endforeach

</table>

<!-- ================= ADD TAX MODAL ================= -->

<div class="modal fade" id="addTax">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="{{ route('tax.store') }}">
@csrf

<div class="modal-header">
<h5>Add Tax</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<input type="number" step="0.01" name="income_from" class="form-control mb-2" placeholder="Income From">

<input type="number" step="0.01" name="income_to" class="form-control mb-2" placeholder="Income To">

<input type="number" step="0.01" name="base_tax" class="form-control mb-2" placeholder="Base Tax">

<input type="number" step="0.0001" name="tax_rate" class="form-control mb-2" placeholder="Tax Rate">

<input type="number" step="0.01" name="excess_over" class="form-control" placeholder="Excess Over">

</div>

<div class="modal-footer">
<button class="btn btn-success">Save</button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>