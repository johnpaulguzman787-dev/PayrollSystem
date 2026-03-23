<!DOCTYPE html>
<html>
<head>
    <title>All Payroll Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial;
            background:#f4f6f9;
            padding:30px;
        }
        .content {margin-left: 280px;padding: 25px;min-height: 100vh;transition: all 0.3s ease;}

        /* MAIN PAYROLL TABLE ONLY */
        table.payroll-table {
            width:100%;
            border-collapse: collapse;
            background:white;
        }

        .payroll-table th,
        .payroll-table td {
            padding:10px;
            border:1px solid #ddd;
            text-align:center;
        }

        .payroll-table th {
            background:#007BFF;
            color:white;
        }

        /* MODAL TABLES */
        .modal table {
            width:100%;
            border-collapse: collapse;
        }

        .modal th,
        .modal td {
            border:1px solid #ccc;
            padding:6px;
            text-align:center;
        }

        .modal th {
            background:#f1f1f1;
        }
    </style>
</head>
<body class="content">
    
        @include('partials.sidebar')

<form method="GET" action="/payslip" class="mb-3">
    <div class="row align-items-center">

        <!-- Search -->
        <div class="col-md-3 search-container">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="search" placeholder="Search payroll periods..." class="form-control">
            </div>
        </div>

        <!-- Payroll Period Filter -->
        <div class="col-md-2">
            <select name="period" class="form-control" onchange="this.form.submit()">
                <option value="all">All Payroll Periods</option>
                @foreach($periods as $period)
                    @if($period->status != 'draft' && $period->status != 'open')
                        <option value="{{ $period->id }}"
                            {{ request('period') == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div class="col-md-2">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="all">All Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                <option value="approval" {{ request('status') == 'approval' ? 'selected' : '' }}>Approval</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>

        <!-- Department Filter -->
        <div class="col-md-3">
            <select name="department" class="form-control" onchange="this.form.submit()">
                <option value="all">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}"
                        {{ request('department') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Add Payslip Button -->
        <div class="col-md-2 text-end">
            <a href="/payslip/add" class="btn btn-primary">+ Add Payslip</a>
        </div>

    </div>
</form>

<table class="table payroll-table" id="payslipTable">
<thead>
<tr>
<th><input type="checkbox" id="selectAll"></th>
<th>Payroll Code</th>
<th>Employee</th>
<th>Gross Pay</th>
<th>Net Pay</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>
@foreach($records as $record)

<tr>
<td>
<input type="checkbox"class="recordCheckbox"value="{{ $record->id }}"data-status="{{ $record->status }}"{{ $record->status == 'paid' ? 'disabled' : '' }}></td>
<td>{{ $record->payroll_code }}</td>
<td>{{ $record->fname }} {{ $record->lname }}</td>
<td>{{ number_format($record->gross_pay,2) }}</td>
<td><strong>{{ number_format($record->net_pay,2) }}</strong></td>
<td>
@if($record->status == 'draft')
<span class="badge bg-secondary">Draft</span>

@elseif($record->status == 'review')
<span class="badge bg-warning text-dark">Review</span>

@elseif($record->status == 'approval')
<span class="badge bg-info text-dark">Approval</span>

@elseif($record->status == 'paid')
<span class="badge bg-success">Paid</span>
@endif
</td>
<td>
    <button class="btn btn-info btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#viewModal{{ $record->id }}">
        View
    </button>
    @if($record->status != 'paid' && $record->period_status != 'closed')
    <button class="btn btn-success btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#editModal{{ $record->id }}">
        Edit
    </button>
    @endif

    @if($record->status == 'paid')
    <a href="/payslip/export/{{ $record->id }}"
    class="btn btn-danger btn-sm"
    target="_blank">
    Export
    </a>
    @endif

    @if($record->status == 'draft')
    <form method="POST" action="/payslip/delete/{{ $record->id }}" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this payslip?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
    </form>
    @endif
</td>
</tr>

@endforeach
</tbody>
</table>
<div class="mt-3">

<button class="btn btn-warning btn-sm" onclick="bulkAction('review')">
Review All
</button>

<button class="btn btn-info btn-sm" onclick="bulkAction('approval')">
Approve All
</button>

<button class="btn btn-success btn-sm" onclick="bulkAction('paid')">
Paid All
</button>

<button class="btn btn-danger btn-sm" onclick="bulkAction('delete')">
Delete All
</button>

</div>
<nav>
    <ul class="pagination justify-content-end mt-3" id="payslipPagination"></ul>
</nav>

<!-- ===================== MODALS (OUTSIDE TABLE) ===================== -->
@foreach($records as $record)
<div class="modal fade" id="editModal{{ $record->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form method="POST" action="/payslip/update/{{ $record->id }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit Payroll – {{ $record->fname }} {{ $record->lname }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="fw-bold">Payroll Period</label>
                    <select name="payroll_period_id" class="form-control mb-3">
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}"
                            {{ $record->payroll_period_id == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}
                        </option>
                        @endforeach
                    </select>

                    <label class="fw-bold mt-3">Payroll Status</label>
                    <select name="status" class="form-control mb-3">
                    <option value="draft" {{ $record->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="review" {{ $record->status == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="approval" {{ $record->status == 'approval' ? 'selected' : '' }}>Approval</option>
                    <option value="paid" {{ $record->status == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>

                    <!-- EARNINGS -->
                    <h6 class="fw-bold">Earnings</h6>
                    <table id="earnings{{ $record->id }}">
                        <tr><th>Name</th><th>Amount</th><th></th></tr>
                        @foreach($record->earnings as $i => $e)
                        <tr>
                            <td><input class="form-control" name="earnings[{{ $i }}][name]" value="{{ $e->name }}"></td>
                            <td><input type="number" step="0.01" class="form-control" name="earnings[{{ $i }}][amount]" value="{{ $e->amount }}"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✕</button></td>
                        </tr>
                        @endforeach
                    </table>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                        onclick="addRow('earnings{{ $record->id }}','earnings')">
                        + Add Earning
                    </button>

                    <!-- DEDUCTIONS -->
                    <h6 class="fw-bold mt-4">Deductions</h6>
                    <table id="deductions{{ $record->id }}">
                        <tr><th>Name</th><th>Amount</th><th></th></tr>
                        @foreach($record->deductions as $i => $d)
                        <tr>
                            <td><input class="form-control" name="deductions[{{ $i }}][name]" value="{{ $d->name }}"></td>
                            <td><input type="number" step="0.01" class="form-control" name="deductions[{{ $i }}][amount]" value="{{ $d->amount }}"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✕</button></td>
                        </tr>
                        @endforeach
                    </table>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                        onclick="addRow('deductions{{ $record->id }}','deductions')">
                        + Add Deduction
                    </button>

                    <!-- 🏛️ STATUTORY CONTRIBUTIONS -->
<h6 class="fw-bold mt-4">Statutory Contributions</h6>

<table class="table table-sm">
    <tr>
        <th>Type</th>
        <th>Amount</th>
    </tr>

    @foreach($record->statutory as $s)
    <tr>
        <td>{{ $s->name }}</td>
        <td>
            <input class="form-control"
                   value="{{ number_format($s->amount,2) }}"
                   readonly>
        </td>
    </tr>
    @endforeach
</table>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Save Changes</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach
<!-- =================== END MODALS =================== -->

@foreach($records as $record)
<div class="modal fade" id="viewModal{{ $record->id }}" tabindex="-1">
<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">
Payroll Details – {{ $record->fname }} {{ $record->lname }}
</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="row mb-3">
<div class="col-md-6">
<strong>Employee:</strong><br>
{{ $record->fname }} {{ $record->lname }}
</div>

<div class="col-md-6">
<strong>Payroll Period:</strong><br>
{{ $record->period_name }}
</div>

<div class="col-md-6 mt-2">
<strong>Payroll Code:</strong><br>
{{ $record->payroll_code }}
</div>
</div>

<div class="row mb-3">
<div class="col-md-6">
<strong>Gross Pay:</strong><br>
₱ {{ number_format($record->gross_pay,2) }}
</div>

<div class="col-md-6">
<strong>Status:</strong><br>
{{ ucfirst($record->status) }}
</div>
</div>

<hr>

<h6 class="fw-bold">Earnings</h6>

<table class="table table-sm">
<tr>
<th>Name</th>
<th>Amount</th>
</tr>

@foreach($record->earnings as $e)
<tr>
<td>{{ $e->name }}</td>
<td>₱ {{ number_format($e->amount,2) }}</td>
</tr>
@endforeach

</table>

<h6 class="fw-bold mt-3">Deductions</h6>

<table class="table table-sm">
<tr>
<th>Name</th>
<th>Amount</th>
</tr>

@foreach($record->deductions as $d)
<tr>
<td>{{ $d->name }}</td>
<td>₱ {{ number_format($d->amount,2) }}</td>
</tr>
@endforeach

</table>

<h6 class="fw-bold mt-3">Statutory Contributions</h6>

<table class="table table-sm">
<tr>
<th>Name</th>
<th>Amount</th>
</tr>

@foreach($record->statutory as $s)
<tr>
<td>{{ $s->name }}</td>
<td>₱ {{ number_format($s->amount,2) }}</td>
</tr>
@endforeach

</table>

<hr>

<div class="row">

<div class="col-md-4">
<strong>Total Earnings</strong><br>
₱ {{ number_format($record->total_earnings,2) }}
</div>

<div class="col-md-4">
<strong>Total Deductions</strong><br>
₱ {{ number_format($record->total_deductions,2) }}
</div>

<div class="col-md-4">
<strong>Net Pay</strong><br>
₱ {{ number_format($record->net_pay,2) }}
</div>

</div>

<hr>

<div class="text-muted">
Created: {{ $record->created_at }} <br>
Last Updated: {{ $record->updated_at }}
</div>

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

</div>
</div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function addRow(tableId, type){
    let table = document.getElementById(tableId);
    let index = table.rows.length - 1;

    table.insertAdjacentHTML('beforeend', `
        <tr>
            <td><input class="form-control" name="${type}[${index}][name]" required></td>
            <td><input type="number" step="0.01" class="form-control" name="${type}[${index}][amount]" required></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✕</button></td>
        </tr>
    `);
}
</script>
<!-- Search -->
<script>
document.addEventListener("DOMContentLoaded", function () {

//ROWS PER PAGE
    const ROWS_PER_PAGE = 10;

// SET UP
    const table = document.getElementById("payslipTable");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const pagination = document.getElementById("payslipPagination");
    const searchInput = document.getElementById("search");

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

    searchInput.addEventListener("input", function () {
        const value = this.value.toLowerCase().trim();

        filteredRows = value === ""
            ? [...rows]
            : rows.filter(row =>
                row.textContent.toLowerCase().includes(value)
              );

        currentPage = 1;
        renderTable();
    });

    renderTable();
});

// SELECT ALL CHECKBOX
document.getElementById("selectAll").addEventListener("change", function(){

    let checkboxes = document.querySelectorAll(".recordCheckbox:not(:disabled)");

    checkboxes.forEach(cb => {
        cb.checked = this.checked;
    });

});


// BULK ACTION
function bulkAction(action){

    let ids = [];
    let invalid = false;

    document.querySelectorAll(".recordCheckbox:checked").forEach(cb=>{

        let status = cb.dataset.status;

        // RULES
        if(status === "paid"){
            invalid = true;
        }

        if(status === "approval" && (action === "review" || action === "draft")){
            invalid = true;
        }

        if(status === "review" && action === "draft"){
            invalid = true;
        }

        if(!invalid){
            ids.push(cb.value);
        }

    });

    if(invalid){
        alert("Invalid status change detected. Payroll workflow cannot go backwards.");
        return;
    }

    if(ids.length === 0){
        alert("Please select valid employees.");
        return;
    }

    if(!confirm("Are you sure you want to perform this action?")){
        return;
    }

    fetch('/payslip/bulk-action',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },

        body: JSON.stringify({
            ids: ids,
            action: action
        })

    })
    .then(res => res.json())
    .then(data => {

        if(data.success){
            location.reload();
        }

    });

}
</script>
</body>
</html>