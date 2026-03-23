<!DOCTYPE html>
<html>
<head>
<title>Payslip</title>

<style>

body{
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#f4f6f9;
    padding:30px;
}

.container{
    width:750px;
    margin:auto;
    background:#fff;
    border-radius:10px;
    padding:30px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:2px solid #eee;
    padding-bottom:15px;
    margin-bottom:20px;
}

.company{
    font-size:20px;
    font-weight:bold;
}

.payslip-title{
    font-size:18px;
    font-weight:bold;
}

/* EMPLOYEE INFO */
.info{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px 40px;
    margin-bottom:20px;
}

.info p{
    margin:0;
    font-size:14px;
}

/* TABLES */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th,td{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:14px;
}

th{
    background:#f8fafc;
    text-align:left;
}

/* SUMMARY */
.summary{
    margin-top:20px;
    border-top:2px solid #eee;
    padding-top:15px;
}

.summary p{
    margin:5px 0;
}

.net-pay{
    margin-top:10px;
    padding:15px;
    background:#e6f4ea;
    border:1px solid #b7e1cd;
    border-radius:6px;
    font-size:18px;
    font-weight:bold;
    text-align:center;
}

/* PRINT BUTTON */
.print-btn{
    margin-top:20px;
    padding:10px 20px;
    border:none;
    background:#007bff;
    color:white;
    border-radius:5px;
    cursor:pointer;
}

.print-btn:hover{
    background:#0056b3;
}

/* PRINT MODE */
@media print{
    body{
        background:white;
        padding:0;
    }
    .container{
        box-shadow:none;
        border:none;
    }
    .print-btn{
        display:none;
    }
}

</style>

</head>

<body>

<div class="container">

<!-- HEADER -->
<div class="header">
    <div class="company">
        YOUR COMPANY NAME
        <br>
        <small style="font-weight:normal;">Payroll Department</small>
    </div>

    <div class="payslip-title">
        PAYSLIP
    </div>
</div>

<!-- EMPLOYEE INFO -->
<div class="info">
    <p><strong>Employee:</strong> {{ $record->fname }} {{ $record->lname }}</p>
    <p><strong>Department:</strong> {{ $record->department_name }}</p>

    <p><strong>Job Title:</strong> {{ $record->job_title_name }}</p>
    <p><strong>Date Hired:</strong> {{ \Carbon\Carbon::parse($record->date_hired)->format('F d, Y') }}</p>

    <p><strong>Payroll Period:</strong> {{ $record->period_name }}</p>
    <p><strong>Period Date:</strong> {{ $record->start_date }} - {{ $record->end_date }}</p>
</div>

<!-- EARNINGS -->
<h4>Earnings</h4>
<table>
<tr>
    <th>Description</th>
    <th style="text-align:right;">Amount</th>
</tr>

@foreach($earnings as $e)
<tr>
    <td>{{ $e->name }}</td>
    <td style="text-align:right;">{{ number_format($e->amount,2) }}</td>
</tr>
@endforeach

</table>

<!-- DEDUCTIONS -->
<h4>Deductions</h4>
<table>
<tr>
    <th>Description</th>
    <th style="text-align:right;">Amount</th>
</tr>

@foreach($deductions as $d)
<tr>
    <td>{{ $d->name }}</td>
    <td style="text-align:right;">{{ number_format($d->amount,2) }}</td>
</tr>
@endforeach

</table>

<!-- SUMMARY -->
<div class="summary">
    <p><strong>Gross Pay:</strong> {{ number_format($record->gross_pay,2) }}</p>
    <p><strong>Total Earnings:</strong> {{ number_format($record->total_earnings,2) }}</p>
    <p><strong>Total Deductions:</strong> {{ number_format($record->total_deductions,2) }}</p>

    <div class="net-pay">
        Net Pay: ₱ {{ number_format($record->net_pay,2) }}
    </div>
</div>

<button class="print-btn" onclick="window.print()">Print Payslip</button>

</div>

</body>
</html>