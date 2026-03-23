<!DOCTYPE html>
<html>
<head>

<title>Payroll Item Settings</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:30px;
}

.content{
margin-left:280px;
padding:25px;
min-height:100vh;
}

table{
width:100%;
border-collapse:collapse;
background:white;
}

th,td{
border:1px solid #ddd;
padding:10px;
text-align:center;
}

th{
background:#007BFF;
color:white;
}

</style>

</head>

<body class="content">

@include('partials.sidebar')

<h3 class="mb-4">Payroll Item Settings</h3>

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

<table class="table">

<thead>

<tr>

<th>Code</th>
<th>Name</th>
<th>Category</th>
<th>Type</th>
<th>Basis</th>
<th>Multiplier</th>
<th>Paid</th>
<th>Active</th>
<th>Action</th>

</tr>

</thead>

<tbody>

@foreach($items as $item)

<tr>

<td>{{ $item->code }}</td>
<td>{{ $item->name }}</td>
<td>{{ ucfirst($item->category) }}</td>
<td>{{ ucfirst($item->type) }}</td>
<td>{{ $item->basis }}</td>

<td>
<span class="badge bg-success">
{{ $item->multiplier }} x
</span>
</td>

<td>
@if($item->is_paid)
<span class="badge bg-primary">Paid</span>
@else
<span class="badge bg-secondary">Unpaid</span>
@endif
</td>

<td>
@if($item->is_active)
<span class="badge bg-success">Active</span>
@else
<span class="badge bg-danger">Inactive</span>
@endif
</td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editModal{{ $item->id }}">
Edit
</button>

</td>

</tr>

@endforeach

</tbody>

</table>


<!-- ================= MODALS ================= -->

@foreach($items as $item)

<div class="modal fade" id="editModal{{ $item->id }}">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST" action="/payroll-items-settings/update/{{ $item->id }}">

@csrf

<div class="modal-header">

<h5 class="modal-title">

Edit {{ $item->name }}

</h5>

<button type="button" class="btn-close" data-bs-dismiss="modal"></button>

</div>


<div class="modal-body">


<label class="fw-bold">Multiplier</label>

<input type="number"
step="0.01"
name="multiplier"
value="{{ $item->multiplier }}"
class="form-control mb-3"
required>


<label class="fw-bold">Is Paid</label>

<select name="is_paid" class="form-control mb-3">

<option value="1" {{ $item->is_paid ? 'selected' : '' }}>
Yes
</option>

<option value="0" {{ !$item->is_paid ? 'selected' : '' }}>
No
</option>

</select>


<label class="fw-bold">Is Active</label>

<select name="is_active" class="form-control">

<option value="1" {{ $item->is_active ? 'selected' : '' }}>
Active
</option>

<option value="0" {{ !$item->is_active ? 'selected' : '' }}>
Inactive
</option>

</select>


</div>


<div class="modal-footer">

<button class="btn btn-secondary" data-bs-dismiss="modal">
Cancel
</button>

<button class="btn btn-primary">
Save Changes
</button>

</div>

</form>

</div>

</div>

</div>

@endforeach


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>