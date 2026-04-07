<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Payroll Period</title>
    <!-- Google Fonts: Manrope & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', 'Inter', 'Segoe UI', sans-serif; background: #f0f2f8; color: #2D2D2D; line-height: 1.5; background-color: #ffffff; }
        .content { margin-left: 280px; padding: 2rem; min-height: 100vh; transition: all 0.2s; }
        h1 { font-size: 1.9rem; font-weight: 600; margin-bottom: 1.8rem; color: #0b2b3b; letter-spacing: -0.3px; border-left: 5px solid #2c7be5; padding-left: 1rem; }
        .analytics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem; padding: 0 1.5rem; }
        .analytics-card { background: #ffffff; border-radius: 8px; padding: 1.3rem 1.5rem; box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease; border: 1px solid #eef2f9; display: flex; flex-direction: column; position: relative; }
        .analytics-card:hover { transform: translateY(-3px); box-shadow: 0 16px 24px -8px rgba(0, 0, 0, 0.1); }
        .analytics-card h3 { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #2D2D2D; margin-bottom: 0.6rem; display: flex; align-items: center; gap: 8px; }
        .analytics-card .value { font-size: 1.9rem; font-weight: 700; color: #2D2D2D; line-height: 1.2; margin-bottom: 0.5rem; }
        .tax-scroll { max-height: 120px; overflow-y: auto; padding-right: 5px; }
        .tax-scroll::-webkit-scrollbar { width: 6px; }
        .tax-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .sss-row { padding: 6px 0; border-bottom: 1px solid #eef2f9; }
        .sss-line { display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; }
        .sss-sub { font-size: 13px;  margin-left: 15px; }
        .tax-row {
    padding: 6px 0;
    border-bottom: 1px solid #eef2f9;
}
.tax-line {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    font-weight: 600;
}
.tax-sub {
    font-size: 11px;
    margin-left: 15px;
}
        .period-name { font-size: 0.7rem; color: #8ca3bc; text-align: right; margin-top: auto; padding-top: 0.5rem; border-top: 1px dashed #eef2f9; }
        .table-section { border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; padding: 1.5rem 0 0 0; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03); }
        .row-controls { display: flex; flex-wrap: wrap; align-items: center; gap: 1.3rem; margin-bottom: 1.5rem; padding: 0 1.5rem; }
        .col-search { flex: 2; min-width: 250px; }
        /* Grid layout for form rows inside SSS and Tax modals */
        .form-row-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eef2f9;
        }
        .form-row-grid:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .form-row-grid .form-group {
            margin-bottom: 0;
        }
        .form-row-grid .form-group label {
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            display: block;
        }
        @media (max-width: 640px) {
            .form-row-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }
        .col-filter { min-width: 150px; }
        .col-button { margin-left: auto; }
        .search-box { position: relative; }
        .search-box input { width: 60%; padding: 0.75rem 0.75rem 0.75rem 2.6rem; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; font-size: 1rem; transition: all 0.2s; font-family: inherit; }
        .search-box input:focus { border-color: #2c7be5; outline: none; box-shadow: 0 0 0 3px rgba(44, 123, 229, 0.15); }
        .search-box i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #8ca3bc; font-size: 1rem; }
        .select-wrapper { position: relative; width: 100%; }
        .select-wrapper select { width: 100%; padding: 0.7rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0; background: #EDF0F5; font-size: 1rem; font-family: inherit; cursor: pointer; transition: 0.2s; color: #2D2D2D; text-align: center; text-align-last: center; appearance: none; -webkit-appearance: none; -moz-appearance: none; background: #EDF0F5; padding-right: 2rem; }
        .select-wrapper select:focus { border-color: #2c7be5; outline: none; box-shadow: 0 0 0 3px rgba(44, 123, 229, 0.1); }
        .dropdown-icon { position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.9rem; pointer-events: none; transition: color 0.2s; }
        .btn { border-radius: 8px; border: none; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-family: inherit; background: transparent; }
        .btn-add { background: #2c7be5; color: white; box-shadow: 0 2px 6px rgba(44, 123, 229, 0.25); height: 3rem; width: 15rem; padding: 0 1rem; }
        .btn-add i { color: white; }
        .btn-add:hover { background: #1f62c9; transform: translateY(-1px); box-shadow: 0 8px 18px rgba(44, 123, 229, 0.3); }
        .btn-edit { background: #ffb74d; color: #2c2c2c; padding: 0.6rem 1.2rem; }
        .btn-edit:hover { background: #ffa726; transform: translateY(-1px); }
        .btn-status { background: #26a69a; color: white; padding: 0.6rem 1.2rem; }
        .btn-status:hover { background: #1e8f84; transform: translateY(-1px); }
        .btn-info { background: #C8DCFE; color: #3B7DED; border: 2px solid #3B7DED; border-radius: 4px; padding: 6px 16px; font-weight: 500; font-size: 14px; box-shadow: 0 2px 6px rgba(59, 130, 246, 0.25); transition: all 0.2s ease; }
        .btn-info:hover { background: #3b82f6; color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; background: #ffffff; overflow: hidden; }
        th { background: #eef2f6; color: #2c3e50; font-weight: 600; padding: 1rem 1rem; border-bottom: 1px solid #e2e8f0; text-align: center; font-size: 1rem; }
        td { padding: 1rem 1rem; border-bottom: 1px solid #edf2f7; text-align: center; vertical-align: middle; color: #2d3e50; font-size: 0.9rem; }
        th:first-child, td:first-child { text-align: left; }
        tr:hover td { background: #fafcff; }
        .status-badge { display: inline-block; padding: 0.3rem 0.9rem; border-radius: 4px; font-size: 0.9rem; font-weight: 600; text-transform: capitalize; letter-spacing: 0.3px; }
        .pending { background: #FFE0C9; color: #856404; }
        .completed { background: #d4edda; color: #155724; }
        nav { margin-top: 1.5rem; display: flex; justify-content: flex-end; }
        .pagination { display: flex; gap: 6px; list-style: none; flex-wrap: wrap; }
        .page-item { display: inline-block; }
        .page-link { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 40px; background: #ffffff; border: 1px solid #e2e8f0; color: #2c3e50; text-decoration: none; font-weight: 500; transition: all 0.2s; cursor: pointer; }
        .page-item.active .page-link { background: #2c7be5; border-color: #2c7be5; color: white; }
        .page-link:hover { background: #eef2ff; border-color: #cbd5e1; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
        #viewModal .modal-content { background: #ffffff; width: 90%; max-width: 700px; border-radius: 28px; padding: 1.8rem 2rem 2rem; position: relative; animation: fadeSlideUp 0.25s ease; box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.25); }
        #viewModal .close { position: absolute; right: 1.5rem; top: 1.2rem; font-size: 1.6rem; cursor: pointer; color: #8f9eb2; transition: 0.2s; }
        #viewModal .close:hover { color: #2c3e50; }
        #viewContent table th, #viewContent table td { border: 1px solid #e9edf2; padding: 0.5rem; }
        .modal-content { background: #ffffff; border-radius: 16px; width: 90%; max-width: 520px; padding: 1.8rem 2rem 2rem; position: relative; animation: fadeSlideUp 0.25s ease; box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.3); }
        /* Make modals scrollable when content overflows */
        .modal-content {
            max-height: 85vh;
            overflow-y: auto;
        }
        /* Optional: better scrollbar styling inside modals */
        .modal-content::-webkit-scrollbar {
            width: 6px;
        }
        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .modal-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .modal-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .modal-header h3 { font-size: 1.8rem; font-weight: 700; color: #0f2b3b; }
        .close-circle { background: #f1f5f9; border: none; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
        .form-group input, .form-group select { width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0; font-family: inherit; }
        .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.8rem; }
        .btn-primary-modal { background: #2c7be5; border: none; padding: 0.7rem 1.6rem; border-radius: 8px; font-weight: 600; color: white; cursor: pointer; }
        .btn-outline-modal { border: 2px solid #3B7DED; background: transparent; padding: 0.7rem 1.4rem; border-radius: 8px; font-weight: 600; color: #3B7DED; cursor: pointer; }
        #addModal .form-group { margin-bottom: 1.25rem; }
        #addModal .form-group label { display: block; font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #1e293b; letter-spacing: 0.2px; }
        #addModal .form-group input { width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0; font-family: 'Manrope', sans-serif; font-size: 0.95rem; transition: all 0.2s; background: #ffffff; }
        #addModal .form-group input:focus { border-color: #2c7be5; outline: none; box-shadow: 0 0 0 3px rgba(44, 123, 229, 0.12); }
        #addModal .date-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
        #addModal .date-row .form-group { margin-bottom: 0; }
        #addModal .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.8rem; }
        #addModal .btn-primary-modal { background: #2c7be5; border: none; padding: 0.7rem 1.6rem; border-radius: 8px; font-weight: 600; font-size: 1rem; color: white; cursor: pointer; transition: 0.2s; font-family: inherit; display: inline-flex; align-items: center; gap: 8px; }
        #addModal .btn-primary-modal:hover { background: #1f62c9; transform: translateY(-1px); box-shadow: 0 6px 14px rgba(44, 123, 229, 0.25); }
        #addModal .btn-outline-modal { background: transparent; border: 2px solid #3B7DED; padding: 0.7rem 1.4rem; border-radius: 8px; font-weight: 600; font-size: 1rem; color: #3B7DED; cursor: pointer; transition: 0.2s; font-family: inherit; }
        #addModal .btn-outline-modal:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(44, 123, 229, 0.25); }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .tabs-wrapper { position: relative; border-bottom: 1px solid #e2e8f0; margin-bottom: 1.5rem; padding: 0 1.5rem; }
        .tabs { display: flex; gap: 2rem; list-style: none; margin: 0; padding: 0; }
        .tab-btn { background: transparent; border: none; padding: 0.75rem 0; font-size: 1rem; font-weight: 600; color: #64748b; cursor: pointer; transition: color 0.2s ease; font-family: inherit; display: inline-block; }
        .tab-btn:hover { color: #2c7be5; }
        .tab-btn.active { color: #2c7be5; }
        .underline { position: absolute; bottom: -1px; left: 0; height: 2px; background-color: #2c7be5; border-radius: 2px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 0; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        @media (max-width: 1024px) { .content { margin-left: 0; padding: 1rem; } .analytics { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .analytics { grid-template-columns: 1fr; } .row-controls { flex-direction: column; align-items: stretch; } .col-button { margin-left: 0; display: flex; justify-content: center; } .btn-add { width: 100%; max-width: 100%; } .table-section { padding: 1rem; } .tabs { gap: 1rem; } .tab-btn { font-size: 0.9rem; } #addModal .date-row { grid-template-columns: 1fr; gap: 0.75rem; } #addModal .modal-content { padding: 1.5rem; } #addModal .form-actions { flex-direction: column-reverse; } #addModal .btn-primary-modal, #addModal .btn-outline-modal { width: 100%; justify-content: center; } }
        .tag-container { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
        .tag { background: #2c7be5; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; display: flex; align-items: center; gap: 6px; }
        .tag span { cursor: pointer; font-weight: bold; }
        .search-dropdown { position: relative; }
        .search-dropdown input { width: 100%; padding: 0.7rem; border-radius: 8px; border: 1px solid #e2e8f0; }
        .dropdown-list { position: absolute; width: 100%; max-height: 200px; overflow-y: auto; background: white; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 5px; display: none; z-index: 999; }
        .dropdown-item { padding: 8px; cursor: pointer; }
        .dropdown-item:hover { background: #f1f5f9; }
        .dropdown-item.assigned { color: #999; cursor: not-allowed; }
        .assigned-badge { background: #fee2e2; color: #b91c1c; font-size: 0.65rem; padding: 2px 6px; border-radius: 4px; margin-left: 6px; font-weight: 600; }
        .edit-icon { position: absolute; top: 12px; right: 12px; cursor: pointer; color: #8ca3bc; font-size: 0.9rem; }
        .edit-icon:hover { color: #2c7be5; }
        .card-data { margin-top: 10px; font-size: 0.85rem; }
        .card-data div { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .card-data span { color: #6b7280; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="content">
    <h1>Payroll Period Management</h1>

    <!-- Tabs with sliding underline -->
    <div class="tabs-wrapper">
        <div class="tabs" id="tabsContainer">
            <button class="tab-btn active" data-tab="payrollTab">Payroll Period</button>
            <button class="tab-btn" data-tab="salaryTab">Salary Structure</button>
            <button class="tab-btn" data-tab="benefitsTab">Benefits</button>
            <button class="tab-btn" data-tab="contributionsTab">Contributions</button>
        </div>
        <div class="underline" id="underline"></div>
    </div>

    <!-- Payroll Period Tab Content -->
    <div id="payrollTab" class="tab-content active">
        <div class="analytics" style="margin-bottom:25px;">
            <div class="analytics-card">
                <h3>Gross Payroll</h3>
                <div class="value">₱{{ number_format($gross,2) }}</div>
                <div class="period-name">{{ $currentPeriodName ?? 'No Active Period' }}</div>
            </div>
            <div class="analytics-card">
                <h3>Net Pay</h3>
                <div class="value">₱{{ number_format($net,2) }}</div>
                <div class="period-name">{{ $currentPeriodName ?? 'No Active Period' }}</div>
            </div>
            <div class="analytics-card">
                <h3>Total Deductions</h3>
                <div class="value">₱{{ number_format($deductions,2) }}</div>
                <div class="period-name">{{ $currentPeriodName ?? 'No Active Period' }}</div>
            </div>
            <div class="analytics-card">
                <h3>Days to Cutoff</h3>
                <div class="value">{{ $daysToCutoff }} days</div>
                <div class="period-name">{{ $currentPeriodName ?? 'No Active Period' }}</div>
            </div>
        </div>

        <div class="table-section">
            <div class="row-controls">
                <div class="col-search">
                    <div class="search-box">
                        <input type="text" id="search" placeholder="Search payroll periods...">
                        <i class="fa fa-search"></i>
                    </div>
                </div>
                <div class="col-filter">
                    <div class="select-wrapper">
                        <select id="statusFilter">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                </div>
                <div class="col-filter">
                    <div class="select-wrapper">
                        <select id="yearFilter">
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                </div>
                <div class="col-button">
                    <button class="btn btn-add" onclick="openAddModal()">
                        <i class="fa fa-plus-circle"></i> Add Payroll Period
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="pasyrollperiodTable">
                    <thead>
                        <tr>
                            <th>Period Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th width="280"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periods as $period)
                        <tr>
                            <td>{{ $period->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($period->start_date)->format('F j, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($period->end_date)->format('F j, Y') }}</td>
                            <td>
                                <span class="status-badge {{ $period->status }}">
                                    {{ str_replace('_',' ', $period->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('payroll.period.view', $period->id) }}" class="btn btn-info">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination justify-content-end mt-3" id="payrollperiodPagination"></ul>
            </nav>
        </div>
    </div>

    <!-- Placeholder Tabs -->
    <div id="salaryTab" class="tab-content">
        <div class="table-section" style="padding: 1.5rem 0 1.5rem 0; text-align: center;">
            <!-- HEADER -->
            <div class="row-controls">
                <h3 style="font-size:1.4rem; font-weight:600;">Salary Grade</h3>
                <div class="col-button">
                    <button class="btn btn-add" onclick="openSalaryModal()">
                        <i class="fa fa-plus-circle"></i> Add Salary Grade
                    </button>
                </div>
            </div>
            <!-- TABLE -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Grade Code</th>
                            <th>Level Name</th>
                            <th>Monthly Salary</th>
                            <th>15 Day Pay</th>
                            <th>Assigned Employees</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salaryGrades as $grade)
                        <tr>
                            <td>{{ $grade->grade_code }}</td>
                            <td>{{ $grade->level_name }}</td>
                            <td>₱{{ number_format($grade->monthly_basic_salary,2) }}</td>
                            <td>₱{{ number_format($grade->monthly_basic_salary / 2,2) }}</td>
                            <td><span class="btn-info">{{ $grade->employees_count ?? 0 }} {{ ($grade->employees_count ?? 0) == 1 ? 'employee' : 'employees' }}</span></td>
                            <td>
                                <button class="btn btn-edit" data-id="{{ $grade->id }}" data-code="{{ $grade->grade_code }}" data-level="{{ $grade->level_name }}" data-salary="{{ $grade->monthly_basic_salary }}" data-employees='@json(DB::table("employees")->where("salary_grade_id", $grade->id)->pluck("employee_id"))' onclick="handleEditSalary(this)"> Edit </button>
                                <form method="POST" action="/salary-grade/delete/{{ $grade->id }}" style="display:inline;">@csrf @method('DELETE')<button class="btn btn-status">Delete</button></form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="benefitsTab" class="tab-content">
        <div class="table-section" style="padding: 1.5rem; text-align: center;">
            <h3>Benefits</h3>
            <p>Content will appear here.</p>
        </div>
    </div>
    <div id="contributionsTab" class="tab-content">
        <div class="analytics" style="margin-bottom:25px;">
        @php
            $sssRates = DB::table('sss_contributions')->orderBy('salary_from')->get();
        @endphp

        <div class="analytics-card">
            <!-- EDIT ICON -->
            <i class="fas fa-edit edit-icon" onclick="openSSSModal()" style="color:black"></i>

            <h3>SSS Rates</h3>

            <!-- SCROLLABLE DATA -->
            <div class="card-data tax-scroll">
                @foreach($sssRates as $sss)
                    <div class="sss-row">
                        <div class="sss-line">
                            <span>
                                ₱{{ number_format($sss->salary_from,0) }} 
                                - 
                                ₱{{ number_format($sss->salary_to,0) }}
                            </span>

                        </div>

                        <div class="sss-sub">
                            ₱{{ number_format($sss->total, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
            @php
                $philhealth = DB::table('philhealth_contributions')->first();
            @endphp

            <div class="analytics-card">
                <!-- EDIT ICON -->
                <i class="fas fa-edit edit-icon "
                    onclick="openPhilhealthModal(this)"
                    data-min-salary="{{ $philhealth->min_salary ?? 0 }}"
                    data-max-salary="{{ $philhealth->max_salary ?? 0 }}"
                    data-rate="{{ $philhealth->contribution_rate ?? 0 }}"
                    data-employee="{{ $philhealth->employee_share ?? 0 }}"
                    data-employer="{{ $philhealth->employer_share ?? 0 }}"
                    style="color:black">
                </i>

                <h3>PhilHealth</h3>

                <!-- DATA (RIGHT SIDE) -->
                <div class="card-data">
                    <div><span>Employee Share:</span> {{ $philhealth->employee_share ?? 0 }}%</div>
                    <div><span>Employer Share:</span> {{ $philhealth->employer_share ?? 0 }}%</div>
                    <div><span>Salary Floor:</span> ₱{{ number_format($philhealth->min_salary ?? 0,2) }}</div>
                    <div><span>Salary Ceiling:</span> ₱{{ number_format($philhealth->max_salary ?? 0,2) }}</div>
                </div>
            </div>
            @php
                $pagibig = DB::table('pagibig_contributions')->first();
            @endphp

            <div class="analytics-card">
                <!-- EDIT ICON -->
                <i class="fas fa-edit edit-icon"
                onclick="openPagibigModal(this)"
                data-salary-cap="{{ $pagibig->salary_cap ?? 0 }}"
                data-employee-rate-low="{{ $pagibig->employee_rate_low ?? 0 }}"
                data-employee-rate-high="{{ $pagibig->employee_rate_high ?? 0 }}"
                data-threshold="{{ $pagibig->salary_threshold ?? 0 }}"
                style="color:black">
                </i>

                <h3>Pag-IBIG</h3>

                <!-- DATA (RIGHT SIDE) -->
                <div class="card-data">
                    <div><span>Salary Cap:</span> ₱{{ number_format($pagibig->salary_cap ?? 0,2) }}</div>
                    <div><span>≤ ₱{{ number_format($pagibig->salary_threshold ?? 0,2) }}:</span> {{ $pagibig->employee_rate_low ?? 0 }}%</div>
                    <div><span>> ₱{{ number_format($pagibig->salary_threshold ?? 0,2) }}:</span> {{ $pagibig->employee_rate_high ?? 0 }}%</div>
                    <div><span>Threshold:</span> ₱{{ number_format($pagibig->salary_threshold ?? 0,2) }}</div>
                </div>
            </div>
            @php
                $taxes = DB::table('tax_contributions')->orderBy('income_from')->get();
            @endphp

            <div class="analytics-card">
                <h3>Withholding Tax</h3>
                <i class="fas fa-edit edit-icon" onclick="openTaxModal()" style="color:black"></i>
                <div class="card-data tax-scroll">
                    @foreach($taxes as $tax)
                        <div class="tax-row">
                            <div class="tax-line">
                                <span>
                                    ₱{{ number_format($tax->income_from,0) }} 
                                    - 
                                    ₱{{ $tax->income_to ? number_format($tax->income_to,0) : 'Above' }}
                                </span>
                            </div>
                            <div class="tax-sub">
                                ₱{{ number_format($tax->base_tax,2) }} + {{ $tax->tax_rate * 100 }}%
                                (Excess over ₱{{ number_format($tax->excess_over,0) }})
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="table-section" style="padding: 1.5rem 0 1.5rem 0; text-align: center;">
    <div class="row-controls">
        <h3 style="font-size:1.4rem; font-weight:600;">
            Contribution Rates by Salary Grade
        </h3>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Grade Code</th>
                    <th>Level Name</th>
                    <th>Monthly Basic Salary</th>
                    <th>SSS (Employee)</th>
                    <th>SSS (Employer)</th>
                    <th>PhilHealth</th>
                    <th>Pag-IBIG</th>
                    <th>Monthly W/Tax</th>
                    <th>Total Deduction / Month</th>
                    <th>Total Deduction / Cut-off</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salaryGrades as $grade)
                <tr>
                    <td>{{ $grade->grade_code }}</td>
                    <td>{{ $grade->level_name }}</td>
                    <td>₱{{ number_format($grade->monthly_basic_salary,2) }}</td>

                    @php
    $salary = $grade->monthly_basic_salary;

    // SSS (TOTAL first like controller)
    $sssRow = DB::table('sss_contributions')
        ->where('salary_from', '<=', $salary)
        ->where('salary_to', '>=', $salary)
        ->first();

    $sssEmployee = $sssRow->employee_share ?? 0;
    $sssEmployer = $sssRow->employer_share ?? 0;
    $monthlySSS = $sssEmployee + $sssEmployer;

    // PhilHealth (MONTHLY)
    $ph = DB::table('philhealth_contributions')->first();
    $monthlyPhilhealth = 0;
    if ($ph) {
        $salaryPH = max($ph->min_salary, min($salary, $ph->max_salary));
        $monthlyPhilhealth = $salaryPH * ($ph->contribution_rate / 100) / 2;
    }

    // Pag-IBIG (MONTHLY)
    $pagibigRow = DB::table('pagibig_contributions')->first();
    $monthlyPagibig = 0;
    if ($pagibigRow) {
        $salaryCap = min($salary, $pagibigRow->salary_cap);
        $rate = ($salary <= $pagibigRow->salary_threshold)
            ? $pagibigRow->employee_rate_low
            : $pagibigRow->employee_rate_high;

        $monthlyPagibig = $salaryCap * ($rate / 100);
    }

    // TAX (MONTHLY)
    $annual = $salary * 12;
    $taxRow = DB::table('tax_contributions')
        ->where('income_from', '<=', $annual)
        ->where(function ($q) use ($annual) {
            $q->where('income_to', '>=', $annual)
              ->orWhereNull('income_to');
        })
        ->first();

    $annualTax = 0;
    if ($taxRow && $annual > 250000) {
        $annualTax = $taxRow->base_tax +
            (($annual - $taxRow->excess_over) * $taxRow->tax_rate);
    }

    $monthlyTax = $annualTax / 12;

    // ✅ CUT-OFF (FOLLOW CONTROLLER EXACTLY)
    $sss = $monthlySSS / 2;
    $philhealth = $monthlyPhilhealth / 2;
    $pagibig = $monthlyPagibig / 2;
    $tax = $monthlyTax / 2;

    // ✅ TOTALS (CORRECT)
    $totalMonthly = $monthlySSS + $monthlyPhilhealth + $monthlyPagibig + $monthlyTax;
    $cutoff = $sss + $philhealth + $pagibig + $tax;
@endphp

                    <td>₱{{ number_format($sssEmployee,2) }}</td>
                    <td>₱{{ number_format($sssEmployer,2) }}</td>
                    <td>₱{{ number_format($monthlyPhilhealth,2) }}</td>
                    <td>₱{{ number_format($monthlyPagibig,2) }}</td>
                    <td>₱{{ number_format($monthlyTax,2) }}</td>
                    <td>₱{{ number_format($totalMonthly,2) }}</td>
                    <td>₱{{ number_format($cutoff,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    </div>
</div>

<!-- PAYROLL PERIOD MODALS -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Payroll Period</h3>
            <button class="close-circle" onclick="closeAddModal()" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="/payroll-period/store">
            @csrf
            <div class="form-group">
                <label>Payroll Period Name</label>
                <input type="text" name="name" id="modal_payroll_name" placeholder="Enter payroll period name" required>
            </div>
            <div class="date-row">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" id="modal_start_date" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" id="modal_end_date" required>
                </div>
            </div>
            <div class="form-group">
                <label>Payout Date</label>
                <input type="date" name="payout_date" id="modal_payout_date" required>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-outline-modal" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn-primary-modal">Create Payroll</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content" style="width: 600px; max-height: 80vh; overflow-y: auto;">
        <span class="close" onclick="closeViewModal()">&times;</span>
        <h3>Payroll Period Details</h3>
        <div id="viewContent">
            <p><strong>Name:</strong> <span id="view_name"></span></p>
            <p><strong>Start Date:</strong> <span id="view_start"></span></p>
            <p><strong>End Date:</strong> <span id="view_end"></span></p>
            <p><strong>Status:</strong> <span id="view_status"></span></p>
            <hr>
            <h4>Summary</h4>
            <p><strong>Total Employees:</strong> <span id="view_employees"></span></p>
            <p><strong>Total Gross Pay:</strong> ₱<span id="view_gross"></span></p>
            <p><strong>Total Net Pay:</strong> ₱<span id="view_net"></span></p>
            <p><strong>Total Deductions:</strong> ₱<span id="view_deductions"></span></p>
            <hr>
            <h4>Department Breakdown</h4>
            <table style="width:100%; border-collapse: collapse;">
                <thead><tr><th style="border:1px solid #ccc; padding:6px;">Department</th><th style="border:1px solid #ccc; padding:6px;">Net Pay</th></tr></thead>
                <tbody id="view_department"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- SALARY STRUCTURE MODAL -->
<div id="salaryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Salary Grade</h3>
            <button class="close-circle" onclick="closeSalaryModal()" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" id="salaryForm">
            @csrf
            <input type="hidden" id="salary_id">
            <div class="form-group">
                <label>Grade Code</label>
                <input type="text" id="grade_code" name="grade_code" required>
            </div>
            <div class="form-group">
                <label>Level Name</label>
                <input type="text" id="level_name" name="level_name" required>
            </div>
            <div class="form-group">
                <label>Monthly Basic Salary</label>
                <input type="number" id="monthly_salary" name="monthly_basic_salary" required>
            </div>

            <div class="form-group">
                <label>Assign Employees</label>

                <div id="selectedEmployees" class="tag-container"></div>

                <div class="search-dropdown">
                    <input type="text" id="employeeSearch" placeholder="Search Employee..." autocomplete="off">
                    <div id="employeeDropdown" class="dropdown-list"></div>
                </div>

                <input type="hidden" name="employees" id="assignedEmployeesInput">
            </div>

            <div class="form-actions">
                <button type="button" class="btn-outline-modal" onclick="closeSalaryModal()">Cancel</button>
                <button type="submit" class="btn-primary-modal">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- CONTRIBUTIONS MODAL -->
 <!-- PAGIBIG MODAL -->
<div id="pagibigModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Pag-IBIG</h3>
            <button class="close-circle" onclick="closePagibigModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="/pagibig/update">
            @csrf

            <div class="form-group">
                <label>Salary Cap</label>
                <input type="number" name="salary_cap" value="{{ $pagibig->salary_cap ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>
                    Employee Rate (≤ ₱{{ number_format($pagibig->salary_threshold ?? 0, 2) }})
                </label>
                <input type="number" step="0.01" name="employee_rate_low" 
                    value="{{ $pagibig->employee_rate_low ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>
                    Employee Rate (> ₱{{ number_format($pagibig->salary_threshold ?? 0, 2) }})
                </label>
                <input type="number" step="0.01" name="employee_rate_high" 
                    value="{{ $pagibig->employee_rate_high ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Threshold</label>
                <input type="number" name="salary_threshold" value="{{ $pagibig->salary_threshold ?? '' }}" required>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-outline-modal" onclick="closePagibigModal()">Cancel</button>
                <button type="submit" class="btn-primary-modal">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- PHILHEALTH MODAL -->
<div id="philhealthModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit PhilHealth</h3>
            <button class="close-circle" onclick="closePhilhealthModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="/philhealth/update">
            @csrf

            <div class="form-group">
                <label>Minimum Salary</label>
                <input type="number" name="min_salary" value="{{ $philhealth->min_salary ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Maximum Salary</label>
                <input type="number" name="max_salary" value="{{ $philhealth->max_salary ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Contribution Rate (%)</label>
                <input type="number" step="0.01" name="contribution_rate" value="{{ $philhealth->contribution_rate ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Employee Share (%)</label>
                <input type="number" step="0.01" name="employee_share" value="{{ $philhealth->employee_share ?? '' }}" required>
            </div>

            <div class="form-group">
                <label>Employer Share (%)</label>
                <input type="number" step="0.01" name="employer_share" value="{{ $philhealth->employer_share ?? '' }}" required>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-outline-modal" onclick="closePhilhealthModal()">Cancel</button>
                <button type="submit" class="btn-primary-modal">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- TAX MODAL -->
<div id="taxModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Withholding Tax</h3>
            <button class="close-circle" onclick="closeTaxModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="/tax/update">
            @csrf
            <div id="taxRows">
                @foreach($taxes as $tax)
                <div class="tax-row form-row-grid">
                    <input type="hidden" name="id[]" value="{{ $tax->id }}">
                    
                    <div class="form-group">
                        <label>Income From (₱)</label>
                        <input type="number" name="income_from[]" value="{{ $tax->income_from }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Income To (₱)</label>
                        <input type="number" name="income_to[]" value="{{ $tax->income_to }}" placeholder="Leave empty for unlimited">
                    </div>
                    
                    <div class="form-group">
                        <label>Base Tax (₱)</label>
                        <input type="number" step="0.01" name="base_tax[]" value="{{ $tax->base_tax }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Tax Rate (%)</label>
                        <input type="number" step="0.01" name="tax_rate[]" value="{{ $tax->tax_rate }}" placeholder="e.g., 0.20" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Excess Over (₱)</label>
                        <input type="number" name="excess_over[]" value="{{ $tax->excess_over }}" required>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="form-actions" style="justify-content: space-between; margin-top: 1.5rem;">
                <button type="button" class="btn-outline-modal" onclick="addTaxRow()">
                    <i class="fas fa-plus"></i> Add Row
                </button>
                <div>
                    <button type="button" class="btn-outline-modal" onclick="closeTaxModal()">Cancel</button>
                    <button type="submit" class="btn-primary-modal">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- SSS MODAL -->
<div id="sssModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit SSS Rates</h3>
            <button class="close-circle" onclick="closeSSSModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form method="POST" action="/sss/update">
            @csrf
            <div id="sssRows">
                @foreach($sssRates as $sss)
                <div class="sss-edit-row form-row-grid">
                    <input type="hidden" name="id[]" value="{{ $sss->id }}">
                    
                    <div class="form-group">
                        <label>Salary From (₱)</label>
                        <input type="number" name="salary_from[]" value="{{ $sss->salary_from }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Salary To (₱)</label>
                        <input type="number" name="salary_to[]" value="{{ $sss->salary_to }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Monthly Salary Credit (₱)</label>
                        <input type="number" step="0.01" name="monthly_salary_credit[]" value="{{ $sss->monthly_salary_credit }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Employee Share (₱)</label>
                        <input type="number" step="0.01" name="employee_share[]" value="{{ $sss->employee_share }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Employer Share (₱)</label>
                        <input type="number" step="0.01" name="employer_share[]" value="{{ $sss->employer_share }}" required>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="form-actions" style="justify-content: space-between; margin-top: 1.5rem;">
                <button type="button" class="btn-outline-modal" onclick="addSSSRow()">
                    <i class="fas fa-plus"></i> Add Row
                </button>
                <div>
                    <button type="button" class="btn-outline-modal" onclick="closeSSSModal()">Cancel</button>
                    <button type="submit" class="btn-primary-modal">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Scripts -->
<script>
let selectedEmployees = new Set();
</script>
<script>
    function openViewModal(button) {
        const periodId = button.dataset.id;
        fetch(`/payroll-period/view/${periodId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('view_name').innerText = data.name;
                document.getElementById('view_start').innerText = data.start_date;
                document.getElementById('view_end').innerText = data.end_date;
                document.getElementById('view_status').innerText = data.status;
                document.getElementById('view_employees').innerText = data.employees;
                document.getElementById('view_gross').innerText = data.gross.toLocaleString('en-US',{minimumFractionDigits:2});
                document.getElementById('view_net').innerText = data.net.toLocaleString('en-US',{minimumFractionDigits:2});
                document.getElementById('view_deductions').innerText = data.deductions.toLocaleString('en-US',{minimumFractionDigits:2});

                const deptBody = document.getElementById('view_department');
                deptBody.innerHTML = '';
                data.departments.forEach(dept => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td style="border:1px solid #ccc; padding:6px;">${dept.name}</td><td style="border:1px solid #ccc; padding:6px;">₱${Number(dept.total).toLocaleString('en-US',{minimumFractionDigits:2})}</td>`;
                    deptBody.appendChild(tr);
                });
                document.getElementById('viewModal').style.display = 'flex';
            })
            .catch(() => alert('Failed to fetch payroll details'));
    }
    function closeViewModal(){ document.getElementById('viewModal').style.display = 'none'; }
    function openAddModal(){ document.getElementById('addModal').style.display = 'flex'; }
    function closeAddModal(){ document.getElementById('addModal').style.display = 'none'; }
    window.addEventListener('click', function (event) {
        const viewModal = document.getElementById('viewModal');
        const addModal = document.getElementById('addModal');
        if (event.target === viewModal) viewModal.style.display = 'none';
        if (event.target === addModal) addModal.style.display = 'none';
    });
    function editSalary(id, code, level, salary, employees = []) {
    openSalaryModal();

    document.getElementById('salary_id').value = id;
    document.getElementById('grade_code').value = code;
    document.getElementById('level_name').value = level;
    document.getElementById('monthly_salary').value = salary;


    selectedEmployees.clear();


    employees.forEach(empId => {
        selectedEmployees.add(empId.toString());
    });


    if (typeof renderTags === "function") {
        renderTags();
    }

    document.getElementById('salaryForm').action = `/salary-grade/update/${id}`;
}
    function openSalaryModal() {
    document.getElementById('salaryModal').style.display = 'flex';

    document.getElementById('salaryForm').reset();
    document.getElementById('salary_id').value = '';


    selectedEmployees.clear();

    document.getElementById("selectedEmployees").innerHTML = "";

    document.getElementById('salaryForm').action = `/salary-grade/store`;
}
    function closeSalaryModal() {document.getElementById('salaryModal').style.display = 'none';}
    window.addEventListener('click', function (event) {
        const salaryModal = document.getElementById('salaryModal');
        if (event.target === salaryModal) salaryModal.style.display = 'none';
    });
    function handleEditSalary(button) {
        const id = button.dataset.id;
        const code = button.dataset.code;
        const level = button.dataset.level;
        const salary = button.dataset.salary;
        let employees = [];
        try { employees = JSON.parse(button.dataset.employees); } catch (e) { employees = []; }
        editSalary(id, code, level, salary, employees);
    }
</script>
<script>
    const startDateInput = document.getElementById('modal_start_date');
    const endDateInput = document.getElementById('modal_end_date');
    const payoutDateInput = document.getElementById('modal_payout_date');
    const nameInput = document.getElementById('modal_payroll_name');
    function formatShortMonthDay(dateStr) {
        const options = { month: 'short', day: 'numeric' };
        return new Date(dateStr).toLocaleDateString('en-US', options);
    }
    function autoFillName() {
        const start = startDateInput.value;
        const end = endDateInput.value;
        if (start && end) nameInput.value = `Payroll ${formatShortMonthDay(start)} - ${formatShortMonthDay(end)}`;
    }
    function autoFillPayoutDate() {
        const end = endDateInput.value;
        if (end) {
            const date = new Date(end);
            date.setDate(date.getDate() + 5);
            payoutDateInput.value = date.toISOString().split('T')[0];
        }
    }
    startDateInput.addEventListener('change', autoFillName);
    endDateInput.addEventListener('change', function() { autoFillName(); autoFillPayoutDate(); });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ROWS_PER_PAGE = 5;
        const table = document.getElementById("pasyrollperiodTable");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const pagination = document.getElementById("payrollperiodPagination");
        const searchInput = document.getElementById("search");
        const statusFilter = document.getElementById("statusFilter");
        const yearFilter = document.getElementById("yearFilter");

        let currentPage = 1;
        let filteredRows = [...rows];
        
        function applyFilters() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const statusValue = statusFilter.value.toLowerCase();
            const yearValue = yearFilter.value;
            filteredRows = rows.filter(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(searchValue);
                const matchesStatus = statusValue === "" || text.includes(statusValue);
                const matchesYear = yearValue === "" || text.includes(yearValue);
                return matchesSearch && matchesStatus && matchesYear;
            });
            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            tbody.innerHTML = "";
            const start = (currentPage - 1) * ROWS_PER_PAGE;
            const end = start + ROWS_PER_PAGE;
            filteredRows.slice(start, end).forEach(row => tbody.appendChild(row));
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

        searchInput.addEventListener("input", applyFilters);
        statusFilter.addEventListener("change", applyFilters);
        yearFilter.addEventListener("change", applyFilters);
        renderTable();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const underline = document.getElementById('underline');
        const tabsContainer = document.getElementById('tabsContainer');

        function updateUnderline(activeTab) {
            if (!activeTab) return;
            const rect = activeTab.getBoundingClientRect();
            const containerRect = tabsContainer.parentElement.getBoundingClientRect();
            const left = rect.left - containerRect.left;
            const width = rect.width;
            underline.style.width = `${width}px`;
            underline.style.left = `${left}px`;
        }

        const savedTab = localStorage.getItem('activeTab');
        if (savedTab) {
            const tabToActivate = document.querySelector(`.tab-btn[data-tab="${savedTab}"]`);
            if (tabToActivate) {
                tabs.forEach(btn => btn.classList.remove('active'));
                tabToActivate.classList.add('active');
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                document.getElementById(savedTab).classList.add('active');
                updateUnderline(tabToActivate);
            }
        } else {
            const activeTab = document.querySelector('.tab-btn.active');
            updateUnderline(activeTab);
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetId = this.getAttribute('data-tab');
                localStorage.setItem('activeTab', targetId);
                tabs.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateUnderline(this);
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                document.getElementById(targetId).classList.add('active');
            });
        });

        window.addEventListener('resize', function() {
            const currentActive = document.querySelector('.tab-btn.active');
            if (currentActive) updateUnderline(currentActive);
        });
    });
</script>
<script id="employees-data" type="application/json">
{!! json_encode(
    $allEmployees->map(function($emp){
        $grade = DB::table('salary_grades')
            ->where('id', $emp->salary_grade_id)
            ->first();

        return [
            'id' => $emp->employee_id,
            'name' => $emp->fname . ' ' . $emp->lname,
            'assigned' => $emp->salary_grade_id ? true : false,
            'grade' => $grade ? $grade->grade_code : null
        ];
    })
) !!}
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("employeeSearch");
    const dropdown = document.getElementById("employeeDropdown");
    const selectedContainer = document.getElementById("selectedEmployees");
    const hiddenInput = document.getElementById("assignedEmployeesInput");

    window.selectedEmployees = new Set();

    const allEmployees = JSON.parse(
        document.getElementById("employees-data").textContent
    );

    function renderDropdown(filter = "") {
        dropdown.innerHTML = "";

        const filtered = allEmployees.filter(emp =>
            emp.name.toLowerCase().includes(filter.toLowerCase())
        );

        filtered.forEach(emp => {
            const div = document.createElement("div");
            div.className = "dropdown-item";

            if (emp.assigned && !selectedEmployees.has(emp.id)) {
                div.classList.add("assigned");

                div.innerHTML = `
                    ${emp.name}
                    <span class="assigned-badge">
                        Already Assigned to ${emp.grade ? emp.grade : 'Unknown Grade'}
                    </span>
                `;

                dropdown.appendChild(div);
                return;
            }

            div.textContent = emp.name;

            div.onclick = () => {
                if (selectedEmployees.has(emp.id)) return;

                selectedEmployees.add(emp.id.toString());
                renderTags();

                dropdown.style.display = "none";
                searchInput.value = "";
            };

            dropdown.appendChild(div);
        });

        dropdown.style.display = filtered.length ? "block" : "none";
    }

    window.renderTags = function () {
        selectedContainer.innerHTML = "";

        selectedEmployees.forEach(id => {
            const emp = allEmployees.find(e => e.id == id);
            if (!emp) return;

            const tag = document.createElement("div");
            tag.className = "tag";

            tag.innerHTML = emp.name + ' <span data-id="' + id + '">×</span>';

            tag.querySelector("span").onclick = () => {
                selectedEmployees.delete(id);
                renderTags();
            };

            selectedContainer.appendChild(tag);
        });

        hiddenInput.value = JSON.stringify(Array.from(selectedEmployees));
    };

    searchInput.addEventListener("input", function () {
        renderDropdown(this.value);
    });

    searchInput.addEventListener("focus", function () {
        renderDropdown(this.value);
    });

    document.addEventListener("click", function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });

});
</script>
<!-- CONTRIBUTIONS SCRIPT -->
 <script>
function openPagibigModal(button) {
    const cap = button.dataset.salaryCap;
    const rateLow = button.dataset.employeeRateLow;
    const rateHigh = button.dataset.employeeRateHigh;
    const threshold = button.dataset.threshold;

    const modal = document.getElementById('pagibigModal');
    modal.style.display = 'flex';

    // Fill input values
    modal.querySelector('input[name="salary_cap"]').value = cap;
    modal.querySelector('input[name="employee_rate_low"]').value = rateLow;
    modal.querySelector('input[name="employee_rate_high"]').value = rateHigh;
    modal.querySelector('input[name="salary_threshold"]').value = threshold;

    // Update labels dynamically
    modal.querySelector('label[for="employee_rate_low"]')?.remove(); // optional
    modal.querySelector('label[for="employee_rate_high"]')?.remove(); // optional

    modal.querySelector('input[name="employee_rate_low"]').previousElementSibling.innerText =
        `Employee Rate (≤ ₱${Number(threshold).toLocaleString('en-US', {minimumFractionDigits:2})})`;

    modal.querySelector('input[name="employee_rate_high"]').previousElementSibling.innerText =
        `Employee Rate (> ₱${Number(threshold).toLocaleString('en-US', {minimumFractionDigits:2})})`;
}

function closePagibigModal() {
    document.getElementById('pagibigModal').style.display = 'none';
}

window.addEventListener('click', function (event) {
    const modal = document.getElementById('pagibigModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

function openPhilhealthModal(el) {
    const modal = document.getElementById('philhealthModal');
    modal.style.display = 'flex';
    const form = modal.querySelector('form');
    form.min_salary.value = el.dataset.minSalary;
    form.max_salary.value = el.dataset.maxSalary;
    form.contribution_rate.value = el.dataset.rate;
    form.employee_share.value = el.dataset.employee;
    form.employer_share.value = el.dataset.employer;
}

function closePhilhealthModal() {
    document.getElementById('philhealthModal').style.display = 'none';
}

window.addEventListener('click', function (event) {
    const modal = document.getElementById('philhealthModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

function openTaxModal() {
    document.getElementById('taxModal').style.display = 'flex';
}

function closeTaxModal() {
    document.getElementById('taxModal').style.display = 'none';
}

function addTaxRow() {
    const container = document.getElementById('taxRows');
    const row = document.createElement('div');
    row.className = 'tax-row form-row-grid';
    row.innerHTML = `
        <input type="hidden" name="id[]" value="">
        <div class="form-group">
            <label>Income From (₱)</label>
            <input type="number" name="income_from[]" required>
        </div>
        <div class="form-group">
            <label>Income To (₱)</label>
            <input type="number" name="income_to[]" placeholder="Leave empty for unlimited">
        </div>
        <div class="form-group">
            <label>Base Tax (₱)</label>
            <input type="number" step="0.01" name="base_tax[]" required>
        </div>
        <div class="form-group">
            <label>Tax Rate (%)</label>
            <input type="number" step="0.01" name="tax_rate[]" placeholder="e.g., 0.20" required>
        </div>
        <div class="form-group">
            <label>Excess Over (₱)</label>
            <input type="number" name="excess_over[]" required>
        </div>
    `;
    container.appendChild(row);
}

window.addEventListener('click', function (event) {
    const modal = document.getElementById('taxModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

function openSSSModal() {
    document.getElementById('sssModal').style.display = 'flex';
}

function closeSSSModal() {
    document.getElementById('sssModal').style.display = 'none';
}

function addSSSRow() {
    const container = document.getElementById('sssRows');
    const row = document.createElement('div');
    row.className = 'sss-edit-row form-row-grid';
    row.innerHTML = `
        <input type="hidden" name="id[]" value="">
        <div class="form-group">
            <label>Salary From (₱)</label>
            <input type="number" name="salary_from[]" required>
        </div>
        <div class="form-group">
            <label>Salary To (₱)</label>
            <input type="number" name="salary_to[]" required>
        </div>
        <div class="form-group">
            <label>Monthly Salary Credit (₱)</label>
            <input type="number" step="0.01" name="monthly_salary_credit[]" required>
        </div>
        <div class="form-group">
            <label>Employee Share (₱)</label>
            <input type="number" step="0.01" name="employee_share[]" required>
        </div>
        <div class="form-group">
            <label>Employer Share (₱)</label>
            <input type="number" step="0.01" name="employer_share[]" required>
        </div>
    `;
    container.appendChild(row);
}

window.addEventListener('click', function (event) {
    const modal = document.getElementById('sssModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
</script>
</body>
</html>