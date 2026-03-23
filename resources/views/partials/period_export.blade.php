<!DOCTYPE html>
<html>
<head>
<title>Payroll Period Export</title>

<style>
body{font-family:Arial;padding:30px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:8px;text-align:center;}
th{background:#eee;}
</style>

</head>
<body>

<h2>Payroll Period Report</h2>

<table>

<tr>
<th>Employee</th>
<th>Basic Salary</th>
<th>Gross Pay</th>
<th>Total Earnings</th>
<th>Total Deductions</th>
<th>Net Pay</th>
</tr>

@foreach($records as $r)

<tr>
<td>{{ $r->fname }} {{ $r->lname }}</td>
<td>{{ number_format($r->basic_salary,2) }}</td>
<td>{{ number_format($r->gross_pay,2) }}</td>
<td>{{ number_format($r->total_earnings,2) }}</td>
<td>{{ number_format($r->total_deductions,2) }}</td>
<td><b>{{ number_format($r->net_pay,2) }}</b></td>
</tr>

@endforeach

</table>

<script>
window.print();
</script>

</body>
</html>