<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Employee Contributions — MEDISOURCE Payroll Officer</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Alpine.js (lightweight reactive framework) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', 'Inter', 'Segoe UI', sans-serif; background: #f0f2f8; color: #2D2D2D; line-height: 1.5; background-color: #ffffff; }
        .content { margin-left: 280px; padding: 2rem; min-height: 100vh; transition: all 0.2s; }
        /* ----- Animation----- */
        @keyframes popUp { 0% { opacity: 0; transform: scale(0.96) translateY(8px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-item { animation: popUp 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards; opacity: 0; }

        /* ----- Top Bar ----- */
        .top-bar { display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
        .back-link { display: inline-flex; align-items: center; gap: 0.5rem; background: #EDF0F5; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.6rem 1rem; color: #2D2D2D; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s ease; }
        .back-link:hover { background: #e2e8f0; color: #1f62c9; }
        .back-link i { font-size: 0.8rem; }
        .breadcrumb { color: #5a6e85; font-size: 0.9rem; background: transparent; padding: 0.2rem 0; }
        .breadcrumb a { color: #8ca3bc; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { color: #2D2D2D; font-weight: 600; margin: 0 0.25rem; }

        /* ----- Table Card ----- */
        .table-section { border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03); }
        .card-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        .card-header h2 { font-size: 1.25rem; font-weight: 600; color: #2c3e50; margin: 0; }

        /* ----- Table UI ----- */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; background: #ffffff; }
        th { background: #eef2f6; color: #2c3e50; font-weight: 600; padding: 1rem 1rem; border-bottom: 1px solid #e2e8f0; }
        td { padding: 1rem 1rem; border-bottom: 1px solid #edf2f7; vertical-align: middle; color: #2d3e50; }
        tr:hover td { background: #fafcff; }
        /* Column alignment: first column left, all others center */
        th:first-child, td:first-child { text-align: left; }
        th:not(:first-child), td:not(:first-child) { text-align: center; }

        /* ----- Status Badges ----- */
        .status-badge { display: inline-block; padding: 0.3rem 0.9rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; letter-spacing: 0.3px; }
        .status-submitted { background: #CCF7E0; color: #2D2D2D; }
        .status-pending { background: #fff3cd; color: #856404; }

        /* ----- Year Filter ----- */
        .col-filter { min-width: 150px; }
        .select-wrapper { position: relative; width: 100%; }
        .select-wrapper select { width: 100%; padding: 0.6rem 2rem 0.6rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0; background: #EDF0F5; font-size: 0.9rem; font-family: inherit; cursor: pointer; transition: 0.2s; color: #2D2D2D; text-align: center; text-align-last: center; appearance: none; -webkit-appearance: none; -moz-appearance: none; }
        .select-wrapper select:focus { border-color: #2c7be5; outline: none; box-shadow: 0 0 0 3px rgba(44, 123, 229, 0.1); }
        .dropdown-icon { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.8rem; pointer-events: none; }

        /* ----- Responsive ----- */
        @media (max-width: 1024px) { .content { margin-left: 0; padding: 1rem; } }
        @media (max-width: 640px) { .content { margin-left: 0; padding: 1rem; } .table-section { padding: 1rem; } th, td { padding: 0.75rem; } .card-header { flex-direction: column; align-items: stretch; } .col-filter { width: 100%; } .top-bar { flex-direction: column; align-items: stretch; } .back-link { justify-content: center; } }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="content">
    <!-- Top bar -->
    <div class="top-bar animate-item" style="animation-delay: 0s;">
        <a href="{{ route('payroll.officer.govpay') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to All Contributions
        </a>
        <div class="breadcrumb">
            <a href="{{ route('payroll.officer.govpay') }}">All Contributions</a>
            <span>&gt;</span>
            <span>{{ $periodName }}</span>
        </div>
    </div>

    <!-- Table card -->
    <div class="table-section animate-item" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h2>Employee Contributions</h2>
            <div class="col-filter">
                <form method="GET" action="{{ request()->url() }}" class="select-wrapper">
                    <select name="year" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>SSS</th>
                        <th>PhilHealth</th>
                        <th>Pag-IBIG</th>
                        <th>W/ Tax</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $row)
                        <tr>
                            <td>{{ $row->fname }} {{ $row->lname }}</td>
                            <td>₱{{ number_format($row->sss, 2) }}</td>
                            <td>₱{{ number_format($row->philhealth, 2) }}</td>
                            <td>₱{{ number_format($row->pagibig, 2) }}</td>
                            <td>₱{{ number_format($row->tax, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $row->status === 'submitted' ? 'submitted' : 'pending' }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #8ca3bc;">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>