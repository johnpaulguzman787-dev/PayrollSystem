<!DOCTYPE html>
<html>
<head>
    <title>Add Payslip | Payroll Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f7fa;
            display: flex;
        }

        /* ================= SIDEBAR RESPONSIVE ================= */
        .content {
            flex: 1;
            margin-left: 280px;
            padding: 24px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ================= MAIN CONTAINER ================= */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 24px;
        }

        /* ================= HEADER SECTION ================= */
        .page-header {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eef2f6;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #1a2639;
            letter-spacing: -0.5px;
        }

        @media (max-width: 768px) {
            .page-header h2 {
                font-size: 24px;
            }
        }

        /* ================= ALERT MESSAGES ================= */
        .alert-error {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            color: #991b1b;
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        /* ================= FORM GRIDS ================= */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #4b5565;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-group select,
        .form-group input {
            padding: 12px 16px;
            border: 1.5px solid #e5e9f0;
            border-radius: 12px;
            font-size: 15px;
            color: #1a2639;
            background: white;
            transition: all 0.2s;
            width: 100%;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-group input[readonly] {
            background: #f8fafc;
            border-color: #e5e9f0;
            color: #64748b;
        }

        /* ================= SALARY CARDS ================= */
        .salary-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .salary-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            border: 1.5px solid #eef2f6;
        }

        .salary-card-label {
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .salary-card-value {
            font-size: 28px;
            font-weight: 600;
            color: #1a2639;
            word-break: break-word;
        }

        /* ================= SECTION HEADERS ================= */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 24px 0 20px 0;
        }

        .section-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: #1a2639;
            position: relative;
            padding-left: 12px;
        }

        .section-header h3::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #3b82f6;
            border-radius: 4px;
        }

        /* ================= ATTENDANCE GRID ================= */
        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .attendance-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1.5px solid #eef2f6;
        }

        .attendance-label {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .attendance-value {
            font-size: 24px;
            font-weight: 600;
            color: #1a2639;
            line-height: 1.2;
        }

        .attendance-input input {
            width: 100%;
            padding: 12px;
            border: 1.5px solid #e5e9f0;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
        }

        /* ================= TWO COLUMN LAYOUT ================= */
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        /* ================= DYNAMIC TABLES ================= */
        .dynamic-table-container {
            background: #f8fafc;
            border-radius: 14px;
            padding: 24px;
            border: 1.5px solid #eef2f6;
        }

        .dynamic-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dynamic-table th {
            text-align: left;
            padding: 12px 8px;
            font-size: 13px;
            font-weight: 600;
            color: #4b5565;
            text-transform: uppercase;
            border-bottom: 1.5px solid #d1d9e6;
        }

        .dynamic-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e5e9f0;
        }

        .dynamic-table input[type="text"],
        .dynamic-table input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #e5e9f0;
            border-radius: 8px;
            font-size: 14px;
        }

        .dynamic-table input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* ================= BUTTONS ================= */
        .btn-add {
            background: white;
            border: 1.5px dashed #3b82f6;
            color: #3b82f6;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #eff6ff;
            border-color: #2563eb;
        }

        .btn-remove {
            background: #fee2e2;
            border: none;
            color: #dc2626;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-remove:hover {
            background: #fecaca;
        }

        .btn-submit {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 24px;
        }

        .btn-submit:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        }

        /* ================= STATUTORY GRID ================= */
        .statutory-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            background: #f8fafc;
            border-radius: 14px;
            padding: 24px;
            border: 1.5px solid #eef2f6;
            margin-bottom: 24px;
        }

        .statutory-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .statutory-item label {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
        }

        .statutory-item input {
            padding: 12px;
            border: 1.5px solid #e5e9f0;
            border-radius: 10px;
            background: white;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
        }

        .statutory-item input[readonly] {
            background: #f8fafc;
        }

        /* ================= NET PAY SECTION ================= */
        .net-pay-section {
            background: linear-gradient(135deg, #1a2639 0%, #2d3748 100%);
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .net-pay-label {
            font-size: 18px;
            font-weight: 500;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .net-pay-amount {
            font-size: 36px;
            font-weight: 700;
            color: white;
            word-break: break-word;
        }

        /* ================= RESPONSIVE BREAKPOINTS ================= */

        /* Large Desktop */
        @media (min-width: 1600px) {
            .container {
                max-width: 1600px;
            }
        }

        /* Desktop and below */
        @media (max-width: 1200px) {
            .attendance-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .statutory-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Tablet Landscape */
        @media (max-width: 1024px) {
            .content {
                margin-left: 0;
                padding: 16px;
            }
            
            .two-column {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }
            
            .salary-cards {
                grid-template-columns: 1fr;
                gap: 24px;
            }
        }

        /* Tablet Portrait */
        @media (max-width: 768px) {
            .content {
                padding: 12px;
            }
            
            .container {
                padding: 20px;
            }
            
            .attendance-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            
            .statutory-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                padding: 20px;
            }
            
            .dynamic-table-container {
                padding: 20px;
            }
            
            .dynamic-table th,
            .dynamic-table td {
                padding: 12px 6px;
            }
            
            .dynamic-table input[type="text"],
            .dynamic-table input[type="number"] {
                padding: 8px 10px;
                font-size: 13px;
            }
            
            .btn-remove {
                padding: 6px 10px;
                font-size: 11px;
            }
            
            .net-pay-amount {
                font-size: 28px;
            }
            
            .net-pay-label {
                font-size: 16px;
            }
            
            .salary-card-value {
                font-size: 24px;
            }
        }

        /* Mobile */
        @media (max-width: 480px) {
            .content {
                padding: 8px;
            }
            
            .container {
                padding: 16px;
                border-radius: 16px;
            }
            
            .page-header h2 {
                font-size: 22px;
            }
            
            .attendance-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .attendance-item {
                padding: 16px;
            }
            
            .attendance-value {
                font-size: 22px;
            }
            
            .form-group select,
            .form-group input {
                padding: 10px 14px;
            }
            
            .salary-card {
                padding: 20px;
            }
            
            .salary-card-value {
                font-size: 22px;
            }
            
            .section-header h3 {
                font-size: 18px;
            }
            
            .dynamic-table-container {
                padding: 16px;
            }
            
            .dynamic-table th {
                font-size: 12px;
                padding: 8px 4px;
            }
            
            .dynamic-table td {
                padding: 8px 4px;
            }
            
            .dynamic-table input[type="text"],
            .dynamic-table input[type="number"] {
                padding: 6px 8px;
                font-size: 12px;
            }
            
            .btn-add {
                padding: 10px 20px;
                font-size: 13px;
                width: 100%;
                justify-content: center;
            }
            
            .btn-remove {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            .statutory-item input {
                padding: 10px;
                font-size: 14px;
            }
            
            .net-pay-section {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }
            
            .net-pay-amount {
                font-size: 24px;
            }
            
            .btn-submit {
                padding: 14px 24px;
                font-size: 15px;
            }
        }

        /* Small Mobile */
        @media (max-width: 360px) {
            .container {
                padding: 12px;
            }
            
            .page-header h2 {
                font-size: 20px;
            }
            
            .dynamic-table th,
            .dynamic-table td {
                padding: 6px 2px;
            }
            
            .dynamic-table input[type="text"],
            .dynamic-table input[type="number"] {
                padding: 4px 6px;
                font-size: 11px;
            }
            
            .btn-remove {
                padding: 4px 6px;
                font-size: 9px;
            }
        }

        /* Landscape Mode */
        @media (max-height: 600px) and (orientation: landscape) {
            .content {
                padding: 12px;
            }
            
            .attendance-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        /* Touch Device Optimizations */
        @media (hover: none) and (pointer: coarse) {
            .form-group select,
            .form-group input,
            .btn-add,
            .btn-remove,
            .btn-submit {
                cursor: default;
                -webkit-tap-highlight-color: transparent;
            }
            
            .form-group select:focus,
            .form-group input:focus {
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
            }
        }

        /* Print Styles */
        @media print {
            .content {
                margin-left: 0;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 20px;
            }
            
            .btn-add,
            .btn-remove,
            .btn-submit {
                display: none;
            }
        }

        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .w-100 {
            width: 100%;
        }
        
        .mt-24 {
            margin-top: 24px;
        }
        
        .mb-24 {
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    @include('partials.sidebar')

    <div class="content">
        <div class="container">
            <!-- Header -->
            <div class="page-header">
                <h2>Create New Payslip</h2>
            </div>

            <!-- Error Alert -->
            @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="/payslip/store">
                @csrf

                <!-- Employee Selection Row -->
                <div class="form-grid">
                    <div class="form-group">
                        <label>Select Employee</label>
                        <select name="employee_id" id="employee_select" required>
                            <option value="">Choose employee...</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->employee_id }}" data-salary="{{ $emp->basic_salary }}">
                                {{ $emp->fname }} {{ $emp->lname }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Payroll Period</label>
                        <select name="payroll_period_id" id="period_select" required>
                            <option value="">Select period...</option>
                            @foreach($periods as $period)
                            <option value="{{ $period->id }}"
                                data-start="{{ $period->start_date }}"
                                data-end="{{ $period->end_date }}">
                                {{ $period->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Salary Information Cards -->
                <div class="salary-cards">
                    <div class="salary-card">
                        <div class="salary-card-label">Basic Monthly Salary</div>
                        <div class="salary-card-value" id="basic_salary_display">0.00</div>
                        <input type="hidden" id="basic_salary">
                    </div>
                    <div class="salary-card">
                        <div class="salary-card-label">Semi-Monthly Gross Pay</div>
                        <div class="salary-card-value" id="gross_pay_display">0.00</div>
                        <input type="hidden" id="gross_pay">
                    </div>
                </div>

                <!-- Attendance Summary Section -->
                <div class="section-header">
                    <h3>Attendance Summary</h3>
                </div>

                <div class="attendance-grid">
                    <div class="attendance-item">
                        <div class="attendance-label">Late (minutes)</div>
                        <div class="attendance-value" id="late_preview_display">0</div>
                        <input type="hidden" id="late_preview">
                    </div>
                    <div class="attendance-item">
                        <div class="attendance-label">Undertime (minutes)</div>
                        <div class="attendance-value" id="undertime_preview_display">0</div>
                        <input type="hidden" id="undertime_preview">
                    </div>
                    <div class="attendance-item">
                        <div class="attendance-label">Overtime (minutes)</div>
                        <div class="attendance-value" id="overtime_preview_display">0</div>
                        <input type="hidden" id="overtime_preview">
                    </div>
                    <div class="attendance-item">
                        <div class="attendance-label">Absents (days)</div>
                        <div class="attendance-value" id="absent_preview_display">0</div>
                        <input type="hidden" id="absent_preview">
                    </div>
                    <div class="attendance-item attendance-input">
                        <div class="attendance-label">Leaves (days)</div>
                        <input type="number" id="leave_preview" name="leave_days" value="0" min="0" step="1">
                    </div>
                </div>

                <!-- Earnings and Deductions Two Column -->
                <div class="two-column">
                    <!-- Earnings Column -->
                    <div class="dynamic-table-container">
                        <div class="section-header" style="margin-top: 0;">
                            <h3>Earnings</h3>
                        </div>
                        <table class="dynamic-table" id="earningsTable">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th style="width: 80px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic rows will be added here -->
                            </tbody>
                        </table>
                        <button type="button" class="btn-add" onclick="addEarning()">
                            + Add Earning
                        </button>
                    </div>

                    <!-- Deductions Column -->
                    <div class="dynamic-table-container">
                        <div class="section-header" style="margin-top: 0;">
                            <h3>Deductions</h3>
                        </div>
                        <table class="dynamic-table" id="deductionsTable">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th style="width: 80px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic rows will be added here -->
                            </tbody>
                        </table>
                        <button type="button" class="btn-add" onclick="addDeduction()">
                            + Add Deduction
                        </button>
                    </div>
                </div>

                <!-- Statutory Deductions -->
                <div class="section-header">
                    <h3>Statutory Deductions</h3>
                </div>

                <div class="statutory-grid">
                    <div class="statutory-item">
                        <label>SSS</label>
                        <input type="text" id="sss" readonly>
                    </div>
                    <div class="statutory-item">
                        <label>PhilHealth</label>
                        <input type="text" id="philhealth" readonly>
                    </div>
                    <div class="statutory-item">
                        <label>Pag-IBIG</label>
                        <input type="text" id="pagibig" readonly>
                    </div>
                    <div class="statutory-item">
                        <label>Withholding Tax</label>
                        <input type="text" id="tax" readonly>
                    </div>
                </div>

                <!-- Net Pay Preview -->
                <div class="net-pay-section">
                    <span class="net-pay-label">Net Pay</span>
                    <span class="net-pay-amount" id="net_pay_preview">0.00</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Create Payslip
                </button>
            </form>
        </div>
    </div>

    <script>
        // All your existing JavaScript remains exactly the same
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
            currentSalary = parseFloat(opt.getAttribute('data-salary')) || 0;
            
            document.getElementById('basic_salary').value = currentSalary.toFixed(2);
            document.getElementById('basic_salary_display').textContent = 
                currentSalary.toLocaleString(undefined,{minimumFractionDigits:2});
            
            computeGross();
            fetchStatutory();
            fetchAttendance();
        });

        /* ================= PERIOD CHANGE ================= */
        document.getElementById('period_select').addEventListener('change', function(){
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
            
            document.getElementById('gross_pay').value = currentGross.toFixed(2);
            document.getElementById('gross_pay_display').textContent = 
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
                document.getElementById('late_preview_display').textContent = lateMins;
                
                document.getElementById('undertime_preview').value = undertimeMins;
                document.getElementById('undertime_preview_display').textContent = undertimeMins;
                
                document.getElementById('overtime_preview').value = overtimeMins;
                document.getElementById('overtime_preview_display').textContent = overtimeMins;
                
                document.getElementById('absent_preview').value = absentDays;
                document.getElementById('absent_preview_display').textContent = absentDays;
                
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
            if(currentSalary === 0) return;

            let dailyRate = currentSalary / 30;
            let hourlyRate = dailyRate / 8;
            let minuteRate = hourlyRate / 60;

            let lateDeduction = lateMins * minuteRate;
            let undertimeDeduction = undertimeMins * minuteRate;
            let absentDeduction = absentDays * dailyRate;
            let leavePay = leaveDays * dailyRate;
            let overtimePay = (overtimeMins / 60) * hourlyRate;

            let attendanceDeductions = lateDeduction + undertimeDeduction + absentDeduction;
            let attendanceEarnings = overtimePay + leavePay;

            let manualEarnings = 0;
            document.querySelectorAll("input[name='earnings_amount[]']").forEach(e=>{
                manualEarnings += parseFloat(e.value) || 0;
            });

            let manualDeductions = 0;
            document.querySelectorAll("input[name='deductions_amount[]']").forEach(d=>{
                manualDeductions += parseFloat(d.value) || 0;
            });

            let net = currentGross + attendanceEarnings + manualEarnings - 
                     statTotal - attendanceDeductions - manualDeductions;

            document.getElementById('net_pay_preview').textContent = 
                net.toLocaleString(undefined,{minimumFractionDigits:2});
        }

        /* ================= ADD EARNING ================= */
        function addEarning(){
            let table = document.getElementById("earningsTable").getElementsByTagName('tbody')[0];
            let row = table.insertRow();
            row.innerHTML = `
                <td><input type="text" name="earnings_name[]" placeholder="e.g., Bonus, Commission"></td>
                <td><input type="number" name="earnings_amount[]" value="0" step="0.01" oninput="computeNet()"></td>
                <td><button type="button" class="btn-remove" onclick="this.closest('tr').remove(); computeNet();">Remove</button></td>
            `;
        }

        /* ================= ADD DEDUCTION ================= */
        function addDeduction(){
            let table = document.getElementById("deductionsTable").getElementsByTagName('tbody')[0];
            let row = table.insertRow();
            row.innerHTML = `
                <td><input type="text" name="deductions_name[]" placeholder="e.g., Loan, Adjustment"></td>
                <td><input type="number" name="deductions_amount[]" value="0" step="0.01" oninput="computeNet()"></td>
                <td><button type="button" class="btn-remove" onclick="this.closest('tr').remove(); computeNet();">Remove</button></td>
            `;
        }
    </script>
</body>
</html>