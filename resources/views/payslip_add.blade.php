<!DOCTYPE html>
<html>
<head>
    <title>Add Payslip</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; padding:30px; }
        .content {margin-left: 280px;padding: 25px;min-height: 100vh;transition: all 0.3s ease;}
        .container { background:white; padding:25px; border-radius:8px; width:900px; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th, td { border:1px solid #ccc; padding:6px; text-align:center; }
        th { background:#eee; }
        button { padding:5px 10px; cursor:pointer; }
        .add-btn { margin-top:8px; }
        input[readonly] { background:#f1f1f1; }
        h3 { margin-top:25px; }

        .card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.card-title {
    font-weight: bold;
    margin-bottom: 15px;
    font-size: 16px;
    color: #333;
}

.snapshot {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.snapshot div {
    width: 48%;
    margin-bottom: 10px;
}

.snapshot strong {
    display: block;
    font-size: 18px;
    color: #222;
}
    </style>
</head>
<body class="content">

@include('partials.sidebar')

<div class="container">
<h2>Add Payslip</h2>

@if(session('error'))
<div style="color:red; margin-bottom:10px;">
    {{ session('error') }}
</div>
@endif

<form method="POST" action="/payslip/store">
@csrf

<label>Employee</label>
<select name="employee_id" id="employee_select" required>
    <option value="">Select</option>
    @foreach($employees as $emp)
        <option value="{{ $emp->employee_id }}"data-name="{{ $emp->fname }} {{ $emp->lname }}"data-salary="{{ $emp->basic_salary }}"data-department="{{ $emp->department_name }}"data-position="{{ $emp->job_title }}"data-hired="{{ $emp->date_hired }}">
        {{ $emp->fname }} {{ $emp->lname }}
        </option>
    @endforeach
</select>

<br><br>

<div class="card">
    <div class="card-title">👤 Employee Info</div>

    <div class="snapshot">
        <div>
            <strong id="emp_name">Full Name</strong>
            <small>Employee Name</small>
        </div>

        <div>
            <label>Department</label>
            <input type="text" id="emp_department" readonly>
        </div>

        <div>
            <label>Position</label>
            <input type="text" id="emp_position" readonly>
        </div>

        <div>
            <label>Date Hired</label>
            <input type="text" id="emp_hired" readonly>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">💰 Salary Info</div>

    <label>Basic Salary</label>
    <input type="text" id="basic_salary" readonly>

    <br><br>

    <label>Gross Pay</label>
    <input type="text" id="gross_pay" readonly>
</div>

<label>Payroll Period</label>
<select name="payroll_period_id" id="period_select" required>
    <option value="">Select Period</option>
    @foreach($periods as $period)
        <option value="{{ $period->id }}"
            data-start="{{ $period->start_date }}"
            data-end="{{ $period->end_date }}">
            {{ $period->name }}
        </option>
    @endforeach
</select>

<!-- ================= ATTENDANCE PREVIEW ================= -->

<div class="card">
<div class="card-title">⏱ Attendance</div>

<table>
<tr>
    <th>Total Late (mins)</th>
    <th>Total Undertime (mins)</th>
    <th>Total Overtime (mins)</th>
    <th>Total Absents (days)</th>
    <th>Leaves (days)</th>
</tr>

<tr>
    <td><input type="text" id="late_preview" readonly></td>
    <td><input type="text" id="undertime_preview" readonly></td>
    <td><input type="text" id="overtime_preview" readonly></td>
    <td><input type="text" id="absent_preview" readonly></td>

    <!-- EDITABLE -->
    <td>
        <input type="number" id="leave_preview" name="leave_days" value="0" min="0" step="1">
    </td>
</tr>
</table>
</div>

<!-- ================= EARNINGS ================= -->

<div class="card">
<div class="card-title">➕ Earnings</div>
<table id="earningsTable">
<tr><th>Name</th><th>Amount</th><th></th></tr>
</table>
<button type="button" onclick="addEarning()">+ Add Earning</button>
</div>

<!-- ================= DEDUCTIONS ================= -->

<div class="card">
<div class="card-title">➖ Deductions</div>
<table id="deductionsTable">
<tr><th>Name</th><th>Amount</th><th></th></tr>
</table>
<button type="button" onclick="addDeduction()">+ Add Deduction</button>
</div>

<!-- ================= STATUTORY ================= -->

<div class="card">
<div class="card-title">📊 Summary</div>

<h4>Statutory Deductions</h4>
<table>
<tr>
    <th>SSS</th>
    <th>PhilHealth</th>
    <th>Pag-IBIG</th>
    <th>Tax</th>
</tr>
<tr>
    <td><input type="text" id="sss" readonly></td>
    <td><input type="text" id="philhealth" readonly></td>
    <td><input type="text" id="pagibig" readonly></td>
    <td><input type="text" id="tax" readonly></td>
</tr>
</table>

<br>

<h4>Net Pay</h4>
<input type="text" id="net_pay_preview" readonly style="font-size:20px;font-weight:bold;">

</div>

<button type="submit">Save Payslip</button>
</form>
</div>

<script>

let currentSalary = 0;
let currentGross = 0;
let statTotal = 0;

/* attendance totals */
let leaveDays = 0;
let lateMins = 0;
let undertimeMins = 0;
let overtimeMins = 0;
let absentDays = 0;

/* ================= EMPLOYEE CHANGE ================= */

document.getElementById('employee_select').addEventListener('change', function(){

    let opt = this.options[this.selectedIndex];
    document.getElementById('emp_name').innerText =
    opt.getAttribute('data-name') || 'Full Name';

    currentSalary = parseFloat(opt.getAttribute('data-salary')) || 0;

    document.getElementById('emp_department').value =
        opt.getAttribute('data-department') || '';

    document.getElementById('emp_position').value =
        opt.getAttribute('data-position') || '';

    document.getElementById('emp_hired').value =
        opt.getAttribute('data-hired') || '';

    document.getElementById('basic_salary').value =
        currentSalary.toLocaleString(undefined,{minimumFractionDigits:2});

    computeGross();
    fetchStatutory();
    fetchAttendance();

});


/* ================= PERIOD CHANGE ================= */

document.getElementById('period_select')
.addEventListener('change', function(){

    computeGross();
    fetchAttendance();

});

document.addEventListener("input", function(e){

    if(e.target.id === "leave_preview"){
        leaveDays = parseFloat(e.target.value) || 0;
        computeNet();
    }

});


/* ================= GROSS ================= */

function computeGross(){

    let period = document.getElementById('period_select');
    let opt = period.options[period.selectedIndex];

    if(!opt) return;

    let start = new Date(opt.dataset.start);
    let end = new Date(opt.dataset.end);

    let days = Math.floor((end - start) / (1000*60*60*24)) + 1;

    let daily = currentSalary / 30;

    currentGross = daily * days;

    document.getElementById('gross_pay').value =
        currentGross.toLocaleString(undefined,{minimumFractionDigits:2});

    computeNet();

}


/* ================= FETCH ATTENDANCE ================= */

function fetchAttendance(){

    let emp = document.getElementById('employee_select').value;
    let period = document.getElementById('period_select').value;

    if(!emp || !period) return;

    fetch('/payslip/attendance-preview',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({
            employee_id: emp,
            period_id: period
        })
    })
    .then(res=>res.json())
    .then(d=>{

    lateMins = parseFloat(d.late) || 0;
    undertimeMins = parseFloat(d.undertime) || 0;
    overtimeMins = parseFloat(d.overtime) || 0;
    absentDays = parseFloat(d.absent) || 0;
    leaveDays = parseFloat(d.leave) || 0;

    document.getElementById('late_preview').value = lateMins;
    document.getElementById('undertime_preview').value = undertimeMins;
    document.getElementById('overtime_preview').value = overtimeMins;
    document.getElementById('absent_preview').value = absentDays;
    document.getElementById('leave_preview').value = leaveDays;

    computeNet();

});

}


/* ================= STATUTORY ================= */

function fetchStatutory(){

    fetch('/payslip/compute-statutory',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },

        body:JSON.stringify({
            employee_id:document.getElementById('employee_select').value
        })

    })

    .then(res=>res.json())
    .then(d=>{

        document.getElementById('sss').value = d.bi_sss;
        document.getElementById('philhealth').value = d.bi_philhealth;
        document.getElementById('pagibig').value = d.bi_pagibig;
        document.getElementById('tax').value = d.bi_tax;

        statTotal =
            d.bi_sss_raw +
            d.bi_philhealth_raw +
            d.bi_pagibig_raw +
            d.bi_tax_raw;

        computeNet();

    });

}


/* ================= NET PAY ================= */

function computeNet(){

    let emp = document.getElementById('employee_select').value;
    let period = document.getElementById('period_select').value;

    if(!emp || !period) return;

    fetch('/payslip/net-preview',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({
            employee_id: emp,
            period_id: period
        })
    })
    .then(res=>res.json())
    .then(d=>{
        document.getElementById('net_pay_preview').value = d.net;
    });

}

/* ================= ADD EARNING ================= */

function addEarning(){

    let table = document.getElementById("earningsTable");

    let row = table.insertRow();

    row.innerHTML = `
        <td>
            <input type="text" name="earnings_name[]" placeholder="Earning name">
        </td>

        <td>
            <input type="number" name="earnings_amount[]" value="0" step="0.01"
            oninput="computeNet()">
        </td>

        <td>
            <button type="button" onclick="this.closest('tr').remove(); computeNet();">
            Remove
            </button>
        </td>
    `;

}


/* ================= ADD DEDUCTION ================= */

function addDeduction(){

    let table = document.getElementById("deductionsTable");

    let row = table.insertRow();

    row.innerHTML = `
        <td>
            <input type="text" name="deductions_name[]" placeholder="Deduction name">
        </td>

        <td>
            <input type="number" name="deductions_amount[]" value="0" step="0.01"
            oninput="computeNet()">
        </td>

        <td>
            <button type="button" onclick="this.closest('tr').remove(); computeNet();">
            Remove
            </button>
        </td>
    `;

}
</script>

</body>
</html>