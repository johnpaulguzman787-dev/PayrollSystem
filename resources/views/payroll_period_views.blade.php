<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Payroll View</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Manrope', 'Inter', 'Segoe UI', sans-serif; background: #f0f2f8; color: #1e293b; line-height: 1.5; }
.topbar { background: #2c7be5; color: white; padding: 0.9rem 2rem; font-weight: 600; font-size: 1rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); letter-spacing: 0.3px; }
.content { margin-left: 280px; padding: 1.8rem 2rem; min-height: 100vh; transition: all 0.2s; }
.header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.8rem; flex-wrap: wrap; gap: 1rem; }
.header-row h2 { font-size: 1.6rem; font-weight: 700; color: #0f2b3b; letter-spacing: -0.3px; border-left: 5px solid #2c7be5; padding-left: 1rem; margin: 0; }
.btn-primary, .btn-view, .btn-outline, .btn-submit { border-radius: 8px; border: none; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-family: inherit; padding: 0.6rem 1.2rem; }
.btn-primary { background: #2c7be5; color: white; box-shadow: 0 2px 6px rgba(44, 123, 229, 0.25); }
.btn-primary:hover:not(:disabled) { background: #1f62c9; transform: translateY(-1px); box-shadow: 0 8px 18px rgba(44, 123, 229, 0.3); }
.btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-view { background: #C8DCFE; color: #3B7DED; border: 2px solid #3B7DED; padding: 0.3rem 1rem; font-weight: 500; font-size: 0.75rem; }
.btn-view:hover { background: #3b82f6; color: white; }
.btn-outline { background: transparent; border: 2px solid #2c7be5; color: #2c7be5; padding: 0.5rem 1rem; }
.btn-outline:hover { background: #eef2ff; }
.btn-submit { background: #2c7be5; color: white; border: none; padding: 0.5rem 1rem; }
.cards { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.8rem; }
.card { background: #ffffff; border-radius: 16px; padding: 1.3rem 1.5rem; box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease; border: 1px solid #eef2f9; }
.card:hover { transform: translateY(-3px); box-shadow: 0 16px 24px -8px rgba(0, 0, 0, 0.1); }
.card h4 { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #2c3e50; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
.card p { font-size: 0.9rem; margin: 0.6rem 0; color: #334155; }
.card small { color: #6c757d; font-size: 0.75rem; }
.progress { height: 6px; background: #e9edf5; border-radius: 4px; margin: 0.4rem 0 0.8rem; }
.progress-bar { height: 100%; background: #2c7be5; border-radius: 4px; width: 0%; }
.main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
.table-card { background: white; border-radius: 16px; border: 1px solid #eef2f9; overflow: hidden; box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.05); }
table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
th { background: #f8fafc; color: #1e293b; font-weight: 600; padding: 1rem 1rem; border-bottom: 1px solid #e2e8f0; text-align: center; }
td { padding: 0.9rem 1rem; border-bottom: 1px solid #edf2f7; text-align: center; vertical-align: middle; color: #334155; }
th:first-child, td:first-child { text-align: left; }
tr:hover td { background: #fafcff; cursor: pointer; }
.badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 600; text-transform: capitalize; }
.badge.submitted { background: #CCF7E0; color: #2D6A4F; }
.badge.pending { background: #FFE6CC; color: #B45309; }
.pagination { display: flex; justify-content: flex-end; list-style: none; padding: 1rem 1.5rem; margin: 0; gap: 0.4rem; }
.pagination li { margin: 0; }
.pagination a { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; color: #2c7be5; font-weight: 500; transition: all 0.2s; }
.pagination .active a { background: #2c7be5; color: white; border-color: #2c7be5; }
.pagination a:hover { background: #eef2ff; transform: translateY(-1px); }
.side-panel { background: white; border-radius: 16px; border: 1px solid #eef2f9; overflow: hidden; box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.05); }
.payslip-card { background: white; color: #1e293b; }
.payslip-header { background: #2c7be5; color: white; padding: 1rem 1.2rem; }
.payslip-header h3 { margin: 0; font-size: 1.2rem; font-weight: 600; }
.payslip-header small { opacity: 0.9; font-size: 0.7rem; }
.payslip-body { padding: 1.2rem; }
.row-flex { display: flex; justify-content: space-between; align-items: center; margin: 0.5rem 0; }
.section-title { margin-top: 1rem; font-weight: 700; font-size: 0.8rem; color: #475569; letter-spacing: 0.3px; border-left: 3px solid #2c7be5; padding-left: 0.5rem; }
.divider { border-top: 1px solid #eef2f9; margin: 0.8rem 0; }
.peso-box { background: #f1f5f9; padding: 0.2rem 0.6rem; border-radius: 12px; font-weight: 500; font-size: 0.8rem; }
.peso-box.negative { color: #e53e3e; }
.panel-actions { display: flex; gap: 0.8rem; margin-top: 1.2rem; }
.panel-actions button { flex: 1; }
.modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
.modal-content { background: white; border-radius: 24px; max-width: 500px; width: 90%; padding: 1.5rem; animation: fadeSlideUp 0.2s ease; }
@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.modal-content h3 { font-size: 1.3rem; font-weight: 600; margin-bottom: 1rem; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; font-weight: 500; margin-bottom: 0.3rem; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; }
.form-actions { display: flex; justify-content: flex-end; gap: 0.8rem; margin-top: 1.2rem; }
@media (max-width: 1024px) { .content { margin-left: 0; padding: 1rem; } .main-grid { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .cards { grid-template-columns: 1fr; } .header-row { flex-direction: column; align-items: stretch; } .btn-primary { width: 100%; justify-content: center; } .pagination { justify-content: center; } }
.top-navigation { margin-bottom: 15px; align-items: center; display: flex; gap: 12px; }
.back-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.82rem; font-weight: 500; color: #374151; background: #fff; cursor: pointer; transition: background 0.15s; text-decoration: none; }
.back-btn:hover { background: #f3f4f6; }
.breadcrumb { font-size: 13px; color: #6c757d; }
</style>
</head>
<body>

@include('partials.sidebar')

<div class="topbar">Payroll</div>

<div class="content">

<div class="top-navigation">

    <!-- LEFT: BACK BUTTON -->
    <a href="{{ url('/payroll-period') }}" class="back-btn">
        ← Back to Payroll Period
    </a>

    <!-- RIGHT: BREADCRUMB -->
    <div class="breadcrumb">
        Payroll &gt; Payroll Period &gt; 
        <strong>{{ $period->name }}</strong>
    </div>

</div>

<div class="header-row">
    <h2>{{ $period->name }}</h2>
    <button 
        class="btn-primary" 
        id="submitBtn" 
        data-id="{{ $period->id }}"
        {{ $submittedCount !== $totalCount ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}
    >
        Submit for Approval
    </button>
</div>

<div class="cards">
    <div class="card">
        <h4><i class="fas fa-chart-line"></i> Payroll Processing Status</h4>
        <p>Payslips Reviewed & Submitted</p>
        <div class="progress"><div class="progress-bar" id="submittedBar"></div></div>
        <small id="submittedText">{{ $submittedCount }}/{{ $totalCount }} Completed</small>

        <p>Payroll Submitted for Approval</p>
        <div class="progress"><div class="progress-bar" id="approvalBar"></div></div>
        <small id="approvalText">{{ $approvalCount }}/1 Completed</small>

        <p>Finance Approval</p>
        <div class="progress"><div class="progress-bar" id="financeBar"></div></div>
        <small id="financeText">{{ $financeCount }}/1 Completed</small>

        <p>Disbursement</p>
        <div class="progress"><div class="progress-bar" id="disbursementBar"></div></div>
        <small id="disbursementText">{{ $disbursementCount }}/1 Completed</small>
    </div>

    <div class="card">
        <h4><i class="fas fa-calculator"></i> Payroll Summary</h4>
        <p>Gross Payroll: ₱{{ number_format($gross,2) }}</p>
        <p>Total Deductions: ₱{{ number_format($deductions,2) }}</p>
        <p>Net Payroll: ₱{{ number_format($net,2) }}</p>
    </div>
</div>

<div class="main-grid">

    <!-- LEFT TABLE -->
    <div class="table-card">
        <table id="employeeTable">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Gross Pay</th>
                    <th>Net Pay</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="employeeTableBody">
                @foreach($records as $rec)
                <tr data-id="{{ $rec->id }}" onclick="loadEmployee(this)">
                    <td>{{ $rec->fname }} {{ $rec->lname }}</td>
                    <td>₱{{ number_format($rec->gross_pay,2) }}</td>
                    <td>₱{{ number_format($rec->net_pay,2) }}</td>
                    <td>
                        <span class="badge {{ $rec->status == 'Submitted' ? 'submitted' : 'pending' }}">
                            {{ ucfirst($rec->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn-view" onclick="event.stopPropagation(); loadEmployee(this.closest('tr'))">
                            View
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <nav>
            <ul class="pagination" id="employeePagination"></ul>
        </nav>
    </div>

    <!-- RIGHT PANEL -->
    <div class="side-panel" id="employeePanel">
        <div style="padding: 2rem; text-align: center; color: #94a3b8;">
            <i class="fas fa-user-circle fa-3x"></i>
            <p>Select an employee to view details</p>
        </div>
    </div>

</div>

</div>

<!-- Adjustment Modal -->
<div class="modal" id="adjustmentModal">
    <div class="modal-content">
        <h3>Edit Adjustments</h3>

        <input type="hidden" id="adj_id">

        <div class="form-group">
            <label>Other Benefits</label>
            <input type="number" id="adj_benefits" step="0.01">
        </div>

        <div class="form-group">
            <label>Other Deductions</label>
            <input type="number" id="adj_deductions" step="0.01">
        </div>

        <div class="form-actions">
            <button class="btn-outline" onclick="closeAdjustmentModal()">Cancel</button>
            <button class="btn-primary" onclick="saveAdjustments()">Save</button>
        </div>
    </div>
</div>

<script>
    const PERIOD_STATUS = "{{ $period->status }}";
function peso(val){
    return '₱ ' + parseFloat(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function loadEmployee(row) {
    const id = row.getAttribute('data-id');
    fetch(`/payroll/employee/${id}`)
    .then(res => res.json())
    .then(data => {
        let earnings = '';
        let sss = parseFloat(data.record.sss || 0);
        let pagibig = parseFloat(data.record.pagibig || 0);
        let philhealth = parseFloat(data.record.philhealth || 0);
        let tax = parseFloat(data.record.withholding_tax || 0);
        let otherDeductions = '';
        let benefits = '';
        
        data.items.forEach(item => {
            if(item.type === 'Addition'){
                earnings += `<div class="row-flex"><span>${item.name}</span><span class="peso-box">${peso(item.amount)}</span></div>`;
            } else {
                // you can add custom logic here if needed
            }
        });
        if(data.benefits){
            data.benefits.forEach(b => {
                benefits += `<div class="row-flex"><span>${b.name}</span><span class="peso-box">${peso(b.amount)}</span></div>`;
            });
        }

        const panel = document.getElementById('employeePanel');
        const grid = document.querySelector('.main-grid');

        panel.style.display = 'block';
        grid.style.gridTemplateColumns = '2fr 1fr'; // restore layout
        document.getElementById('employeePanel').innerHTML = `
        <div class="payslip-card">
            <div class="payslip-header"><h3>Medisource</h3><small>{{ $period->name }}</small></div>
            <div class="payslip-body">
                <div class="row-flex"><div><strong>${data.record.fname} ${data.record.lname}</strong><br><small>${data.record.job_title ?? 'No Title'}</small></div><div><small>Department</small><br>${data.record.department_name ?? 'N/A'}</div></div>
                <div class="divider"></div>
                <div class="section-title">Earnings</div>
                <div class="row-flex"><span>Basic Pay</span><span>${peso(data.record.basic_pay)}</span></div>
                ${earnings}
                <div class="row-flex"><strong>Gross Pay</strong><strong>${peso(data.record.gross_pay)}</strong></div>
                <div class="section-title">Benefits</div>
                ${benefits}
                <div class="row-flex">
                    <span>Other Benefits</span>
                    <span class="peso-box">${peso(data.record.other_benefits ?? 0)}</span>
                </div>

                <div class="section-title">Deductions</div>
                <div class="row-flex">
                    <span>SSS</span>
                    <span class="peso-box negative">- ${peso(sss)}</span>
                </div>

                <div class="row-flex">
                    <span>Pag-IBIG</span>
                    <span class="peso-box negative">- ${peso(pagibig)}</span>
                </div>

                <div class="row-flex">
                    <span>PhilHealth</span>
                    <span class="peso-box negative">- ${peso(philhealth)}</span>
                </div>

                <div class="row-flex">
                    <span>Withholding Tax</span>
                    <span class="peso-box negative">- ${peso(tax)}</span>
                </div>

                ${otherDeductions}

                <div class="row-flex">
                    <span>Other Deductions</span>
                    <span class="peso-box negative">- ${peso(data.record.other_deductions ?? 0)}</span>
                </div>

                <div class="row-flex">
                    <strong>Total Deductions</strong>
                    <strong>${peso(data.record.total_deductions)}</strong>
                </div>
                <div class="section-title">Calculation</div>
                <div class="row-flex"><span>Earnings</span><span>${peso(data.record.gross_pay)}</span></div>
                <div class="row-flex"><span>Deductions</span><span>${peso(data.record.total_deductions)}</span></div>
                <div class="row-flex"><strong>Net Pay</strong><strong>${peso(data.record.net_pay)}</strong></div>
                <div class="panel-actions">
                    ${
                    data.record.status === 'Submitted'
                    ? (
                        PERIOD_STATUS === 'submitted'
                        ? `
                            <button class="btn-outline" onclick="closePanel()">Close</button>
                            <button class="btn-submit" onclick="exportPayslip(${data.record.id})">Export</button>
                        `
                        : `<button class="btn-submit" style="width:100%;" onclick="unsubmitStatus(${data.record.id})">Unsubmit</button>`
                    )
                        : `<button class="btn-outline" onclick="openAdjustmentModal(${data.record.id}, ${data.record.other_benefits || 0}, ${data.record.other_deductions || 0})">Edit</button>
                           <button class="btn-submit" onclick="updateStatus(${data.record.id})">Submit</button>`
                    }
                </div>
            </div>
        </div>`;
    });
}

function closePanel() {
    const panel = document.getElementById('employeePanel');
    const grid = document.querySelector('.main-grid');

    panel.style.display = 'none';
    grid.style.gridTemplateColumns = '1fr'; // FULL WIDTH TABLE
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
        if(res.error){ alert(res.error); return; }
        let row = document.querySelector(`tr[data-id='${id}']`);
        let badge = row.querySelector('.badge');
        badge.innerText = 'Submitted';
        badge.classList.remove('pending');
        badge.classList.add('submitted');
        loadEmployee(row);
        refreshProgressBars();
    });
}

function unsubmitStatus(id){
    if(!confirm("Unsubmit this employee?")) return;
    fetch(`/payroll/employee/${id}/unsubmit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(res => {
        if(res.error){ alert(res.error); return; }
        let row = document.querySelector(`tr[data-id='${id}']`);
        let badge = row.querySelector('.badge');
        badge.innerText = 'Pending';
        badge.classList.remove('submitted');
        badge.classList.add('pending');
        loadEmployee(row);
        refreshProgressBars();
    });
}

function submitForApproval(id){
    if(!confirm("Submit payroll for approval?")) return;
    fetch(`/payroll/period/${id}/submit`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(res => res.json())
    .then(res => { if(res.success){ alert("Payroll submitted for approval!"); location.reload(); } });
}

document.getElementById('submitBtn').addEventListener('click', function() { submitForApproval(this.getAttribute('data-id')); });

function openAdjustmentModal(id, benefits, deductions){
    document.getElementById('adj_id').value = id;
    document.getElementById('adj_benefits').value = benefits;
    document.getElementById('adj_deductions').value = deductions;
    document.getElementById('adjustmentModal').style.display = 'flex';
}

function closeAdjustmentModal(){
    document.getElementById('adjustmentModal').style.display = 'none';
}

function saveAdjustments(){
    let id = document.getElementById('adj_id').value;

    fetch(`/payroll/employee/${id}/adjustments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            other_benefits: document.getElementById('adj_benefits').value,
            other_deductions: document.getElementById('adj_deductions').value
        })
    })
    .then(res => res.json())
    .then(res => {
        if(res.success){
            alert("Updated!");
            closeAdjustmentModal();
            location.reload();
        }
    });
}

function toggleSubmitButton(data){
    let btn = document.getElementById('submitBtn');
    if(data.submitted === data.total && data.total > 0){
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    } else {
        btn.disabled = true;
        btn.style.opacity = '0.5';
        btn.style.cursor = 'not-allowed';
    }
}

function refreshProgressBars(){
    fetch(`/payroll/period/{{ $period->id }}/progress`)
    .then(res => res.json())
    .then(data => {
        let submittedPercent = data.total > 0 ? (data.submitted / data.total) * 100 : 0;
        document.getElementById('submittedBar').style.width = submittedPercent + '%';
        document.getElementById('submittedText').innerText = `${data.submitted}/${data.total} Completed`;
        let approvalPercent = (data.approval / 1) * 100;
        document.getElementById('approvalBar').style.width = approvalPercent + '%';
        document.getElementById('approvalText').innerText = `${data.approval}/1 Completed`;
        let financePercent = (data.finance / 1) * 100;
        document.getElementById('financeBar').style.width = financePercent + '%';
        document.getElementById('financeText').innerText = `${data.finance}/1 Completed`;
        let disbursementPercent = (data.disbursement / 1) * 100;
        document.getElementById('disbursementBar').style.width = disbursementPercent + '%';
        document.getElementById('disbursementText').innerText = `${data.disbursement}/1 Completed`;
        toggleSubmitButton(data);
    });
}

function exportPayslip(id){
    window.open(`/payroll/employee/${id}/export`, '_blank');
}

document.addEventListener("DOMContentLoaded", function(){
    refreshProgressBars();
    // Pagination logic remains exactly the same
    const ROWS_PER_PAGE = 10;
    const tbody = document.getElementById("employeeTableBody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const pagination = document.getElementById("employeePagination");
    let currentPage = 1;

    function renderTable() {
        tbody.innerHTML = "";
        const start = (currentPage - 1) * ROWS_PER_PAGE;
        const end = start + ROWS_PER_PAGE;
        rows.slice(start, end).forEach(row => tbody.appendChild(row));
        renderPagination();
    }
    function renderPagination() {
        pagination.innerHTML = "";
        const pageCount = Math.ceil(rows.length / ROWS_PER_PAGE);
        if (pageCount <= 1) return;
        for (let i = 1; i <= pageCount; i++) {
            const li = document.createElement("li");
            li.className = (i === currentPage) ? "active" : "";
            li.innerHTML = `<a href="#">${i}</a>`;
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