<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Payroll View</title>

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Manrope', sans-serif;
    background: #f6f8fc;
    margin: 0;
    color: #2d2d2d;
}

/* HEADER */
.topbar {
    background: #2c7be5;
    color: white;
    padding: 16px 24px;
    font-weight: 600;
    font-size: 18px;
}

/* CONTENT */
.container {
    padding: 25px;
}

/* HEADER ROW */
.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn-primary {
    background: #2c7be5;
    color: white;
    padding: 10px 18px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 500;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.card {
    background: white;
    padding: 18px;
    border-radius: 8px;
    box-shadow: 0 5px 12px rgba(0,0,0,0.05);
}

/* PROGRESS */
.progress {
    height: 6px;
    background: #e9edf5;
    border-radius: 5px;
    margin: 6px 0 12px;
}

.progress-bar {
    height: 100%;
    background: #2c7be5;
    border-radius: 5px;
}

/* MAIN GRID */
.main-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

/* TABLE */
.table-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f1f4f9;
    padding: 12px;
    font-size: 13px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

tr:hover {
    background: #fafcff;
    cursor: pointer;
}

/* STATUS */
.badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
}

.submitted {
    background: #d4edda;
    color: #155724;
}

.pending {
    background: #ffe6cc;
    color: #b26a00;
}

/* VIEW BUTTON */
.btn-view {
    background: #dbeafe;
    border: 1px solid #3b82f6;
    color: #3b82f6;
    padding: 5px 12px;
    border-radius: 5px;
    cursor: pointer;
}

/* RIGHT PANEL */
.side-panel {
    background: #2c7be5;
    border-radius: 10px;
    color: white;
    padding: 20px;
}

.side-panel h3 {
    margin: 0;
}

.side-box {
    background: white;
    color: black;
    border-radius: 8px;
    padding: 15px;
    margin-top: 12px;
}

/* BUTTONS */
.panel-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.btn-outline {
    flex: 1;
    border: 2px solid #2c7be5;
    background: white;
    color: #2c7be5;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-submit {
    flex: 1;
    background: #2c7be5;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
}

.payslip-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    color: #2d2d2d;
}

.payslip-header {
    background: #2c7be5;
    color: white;
    padding: 15px;
}

.payslip-header h3 {
    margin: 0;
}

.payslip-body {
    padding: 15px;
}

.row-flex {
    display: flex;
    justify-content: space-between;
    margin: 6px 0;
}

.section-title {
    margin-top: 15px;
    font-weight: 600;
    font-size: 14px;
}

.divider {
    border-top: 1px solid #eee;
    margin: 10px 0;
}

.peso-box {
    background: #f1f4f9;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 500;
}

.peso-box.negative {
    color: #d9534f;
}
</style>
</head>

<body>

<div class="topbar">Payroll</div>

<div class="container">

    <div class="header-row">
        <h2>{{ $period->name }}</h2>
        <button class="btn-primary" id="submitBtn" data-id="{{ $period->id }}">
    Submit for Approval
</button>
    </div>

    <div class="cards">

        <div class="card">
            <h4>Payroll Processing Status</h4>

            <p>Payslips Reviewed & Submitted</p>
            <div class="progress">
                <div class="progress-bar" data-width="{{ $submittedPercent }}%"></div>
            </div>
            <small>{{ round($submittedPercent) }}% Completed</small>

            <p>Payroll Submitted for Approval</p>
            <div class="progress">
                <div class="progress-bar" data-width="{{ $approvalPercent }}"></div>
            </div>

            <p>Finance Approval</p>
            <div class="progress"><div class="progress-bar" style="width: 0%"></div></div>

            <p>Disbursement</p>
            <div class="progress"><div class="progress-bar" style="width: 0%"></div></div>
        </div>

        <div class="card">
            <h4>Payroll Summary</h4>
            <p>Gross Payroll: ₱{{ number_format($gross,2) }}</p>
            <p>Total Deductions: ₱{{ number_format($deductions,2) }}</p>
            <p>Net Payroll: ₱{{ number_format($net,2) }}</p>
        </div>

    </div>

    <div class="main-grid">

        <!-- LEFT TABLE -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Gross Pay</th>
                        <th>Net Pay</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($records as $rec)
                <tr data-id="{{ $rec->id }}" onclick="loadEmployee(this)">
                    <td>{{ $rec->fname }} {{ $rec->lname }}</td>
                    <td>₱{{ number_format($rec->gross_pay,2) }}</td>
                    <td>₱{{ number_format($rec->net_pay,2) }}</td>
                    <td>
                        <span class="badge {{ $rec->status == 'submitted' ? 'submitted' : 'pending' }}">
                            {{ ucfirst($rec->status) }}
                        </span>
                    </td>
                    <td><button class="btn-view" onclick="event.stopPropagation(); loadEmployee(this.closest('tr'))">View</button></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- RIGHT PANEL -->
        <div class="side-panel" id="employeePanel">
            <h3>Select Employee</h3>
            <p>Click a row to view details</p>
        </div>

    </div>

</div>

<script>

    function peso(val){
    return '₱ ' + parseFloat(val).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function loadEmployee(row) {
    const id = row.getAttribute('data-id');

    fetch(`/payroll/employee/${id}`)
    .then(res => res.json())
    .then(data => {

        let earnings = '';
        let deductions = '';

        data.items.forEach(item => {
            if(item.type === 'earning'){
                earnings += `
                <div class="row-flex">
                    <span>${item.name}</span>
                    <span class="peso-box">${peso(item.amount)}</span>
                </div>`;
            } else {
                deductions += `
                <div class="row-flex">
                    <span>${item.name}</span>
                    <span class="peso-box negative">- ${peso(item.amount)}</span>
                </div>`;
            }
        });

        document.getElementById('employeePanel').innerHTML = `
        <div class="payslip-card">

            <!-- HEADER -->
            <div class="payslip-header">
                <h3>Medisource</h3>
                <small>${"{{ $period->name }}"}</small>
            </div>

            <!-- BODY -->
            <div class="payslip-body">

                <!-- EMPLOYEE INFO -->
                <div class="row-flex">
                    <div>
                        <strong>${data.record.fname} ${data.record.lname}</strong><br>
                        <small>${data.record.job_title ?? 'No Title'}</small>
                    </div>
                    <div>
                        <small>Department</small><br>
                        ${data.record.department_name ?? 'N/A'}
                    </div>
                </div>

                <div class="divider"></div>

                <!-- EARNINGS -->
                <div class="section-title">Earnings</div>
                <div class="row-flex">
                    <span>Basic Pay</span>
                    <span>${peso(data.record.basic_salary)}</span>
                </div>
                ${earnings}

                <div class="row-flex">
                    <strong>Gross Pay</strong>
                    <strong>${peso(data.record.gross_pay)}</strong>
                </div>

                <!-- DEDUCTIONS -->
                <div class="section-title">Deductions</div>
                ${deductions}

                <div class="row-flex">
                    <strong>Total Deductions</strong>
                    <strong>${peso(data.record.total_deductions)}</strong>
                </div>

                <!-- CALCULATION -->
                <div class="section-title">Calculation</div>
                <div class="row-flex">
                    <span>Earnings</span>
                    <span>${peso(data.record.gross_pay)}</span>
                </div>
                <div class="row-flex">
                    <span>Deductions</span>
                    <span>${peso(data.record.total_deductions)}</span>
                </div>
                <div class="row-flex">
                    <strong>Net Pay</strong>
                    <strong>${peso(data.record.net_pay)}</strong>
                </div>

                <!-- BUTTONS -->
                <div class="panel-actions">
                    <button class="btn-outline">Edit</button>
                    <button class="btn-submit" onclick="updateStatus(${data.record.id})">
                        ${data.record.status === 'submitted' ? 'Submitted' : 'Submit'}
                    </button>
                </div>

            </div>
        </div>
        `;
    });
}

function updateStatus(id){
    fetch(`/payroll/employee/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(res => {
        if(res.error){
            alert(res.error);
            return;
        }
        loadEmployee(document.querySelector(`tr[data-id='${id}']`));
        location.reload();
    });
}

function submitForApproval(id){
    if(!confirm("Submit payroll for approval?")) return;

    fetch(`/payroll/period/${id}/submit`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(res => {
        if(res.success){
            alert("Payroll submitted for approval!");
            location.reload();
        }
    });
}

document.querySelectorAll('.progress-bar').forEach(bar => {
    const width = bar.getAttribute('data-width');
    bar.style.width = width + '%';
});

document.getElementById('submitBtn').addEventListener('click', function() {
    const id = this.getAttribute('data-id');
    submitForApproval(id);
});
</script>

</body>
</html>