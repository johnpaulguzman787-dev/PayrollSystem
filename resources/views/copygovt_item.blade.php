<!DOCTYPE html>
<html>
<head>
<title>Government Contributions</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

</head>

<style>
        .content {margin-left: 280px;padding: 25px;min-height: 100vh;transition: all 0.3s ease;}
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin:0; padding:0; }
    </style>

<body>

@include('partials.sidebar')

<div class="content">

<h2>Government Contributions</h2>

<div class="row mt-5">

<!-- ================= PHILHEALTH ================= -->

<div class="col-md-6">

<h3>PhilHealth</h3>

<table class="table table-bordered">

<tr>
<th>Rate</th>
<th>Employee</th>
<th>Employer</th>
<th>Min Salary</th>
<th>Max Salary</th>
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

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editPhilhealth{{ $row->id }}">
Edit
</button>

</td>

</tr>

@endforeach

</table>

</div>


<!-- ================= PAGIBIG ================= -->

<div class="col-md-6">

<h3>Pag-IBIG</h3>

<table class="table table-bordered">

<tr>
<th>Salary Cap</th>
<th>Rate ≤ Threshold</th>
<th>Rate > Threshold</th>
<th>Threshold</th>
<th>Action</th>
</tr>

@foreach($pagibig as $row)

<tr>

<td>{{ $row->salary_cap }}</td>
<td>{{ $row->employee_rate_low }}%</td>
<td>{{ $row->employee_rate_high }}%</td>
<td>{{ $row->salary_threshold }}</td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editPagibig{{ $row->id }}">
Edit
</button>

</td>

</tr>

@endforeach

</table>

</div>

</div>


<!-- ================= MODALS ================= -->

@foreach($philhealth as $row)

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



@foreach($pagibig as $row)

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


<!-- ================= SSS ================= -->

<h3 class="mt-5">SSS Contributions</h3>

<button class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#addSSS">
Add SSS
</button>

<table class="table table-bordered" id="sssTable">

<tr>
<th>Salary From</th>
<th>Salary To</th>
<th>MSC</th>
<th>Employee</th>
<th>Employer</th>
<th>Total</th>
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

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editSSS{{ $row->id }}">
Edit
</button>

<form method="POST" action="{{ route('sss.delete',$row->id) }}" style="display:inline;">
@csrf
<button class="btn btn-danger btn-sm">Delete</button>
</form>

</td>
</tr>


<!-- ================= EDIT SSS MODAL ================= -->

<div class="modal fade" id="editSSS{{ $row->id }}">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="{{ route('sss.update',$row->id) }}">
@csrf

<div class="modal-header">
<h5>Edit SSS</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
<nav>
    <ul class="pagination justify-content-end mt-3" id="sssPagination"></ul>
</nav>

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

<!-- ================= TAX ================= -->

<h3 class="mt-5">Withholding Tax</h3>

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
    

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#editTax{{ $row->id }}">
Edit
</button>

<form method="POST" action="{{ route('tax.delete',$row->id) }}" style="display:inline;">
@csrf
<button class="btn btn-danger btn-sm">Delete</button>
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
<script>

document.addEventListener("DOMContentLoaded", function () {

const ROWS_PER_PAGE = 10;

const table = document.getElementById("sssTable");
const tbody = table.querySelector("tbody");
const rows = Array.from(tbody.querySelectorAll("tr"));
const pagination = document.getElementById("sssPagination");

let currentPage = 1;
let filteredRows = [...rows];

function renderTable() {

tbody.innerHTML = "";

const start = (currentPage - 1) * ROWS_PER_PAGE;
const end = start + ROWS_PER_PAGE;

filteredRows.slice(start, end).forEach(row => {
tbody.appendChild(row);
});

renderPagination();
}

function renderPagination() {

pagination.innerHTML = "";

const pageCount = Math.ceil(filteredRows.length / ROWS_PER_PAGE);

if (pageCount <= 1) return;

for (let i = 1; i <= pageCount; i++) {

const li = document.createElement("li");

li.className = "page-item " + (i === currentPage ? "active" : "");

li.innerHTML = `<a class="page-link" href="#">${i}</a>`;

li.onclick = function (e) {

e.preventDefault();

currentPage = i;

renderTable();

};

pagination.appendChild(li);

}

}

renderTable();

});

</script>
</body>
</html>