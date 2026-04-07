<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Govt. Contributions — MEDISOURCE Payroll Officer</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Alpine.js for reactive tabs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ----- RESET & GLOBAL ----- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', 'Inter', 'Segoe UI', sans-serif; background: #f0f2f8; color: #2D2D2D; line-height: 1.5; background-color: #ffffff; }


        /* ----- ANIMATIONS (staggered pop‑up) ----- */
        @keyframes popUp { 0% { opacity: 0; transform: scale(0.96) translateY(8px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        .animate-item { animation: popUp 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards; opacity: 0; }

        /* ----- LAYOUT ----- */
        .content { margin-left: 280px; padding: 2rem; min-height: 100vh; transition: all 0.2s; }
        h1 { font-size: 1.9rem; font-weight: 600; margin-bottom: 0.5rem; color: #0b2b3b; letter-spacing: -0.3px; border-left: 5px solid #2c7be5; padding-left: 1rem; }
        .subhead { color: #5a6e85; margin-bottom: 1.5rem; font-size: 0.95rem; }

        /* ----- TABS (simple bottom border) ----- */
        .tabs-wrapper { border-bottom: 1px solid #e2e8f0; margin-bottom: 1.5rem; }
        .tabs { display: flex; gap: 2rem; }
        .tab-btn { background: transparent; border: none; padding: 0.75rem 0; font-size: 1rem; font-weight: 600; color: #64748b; cursor: pointer; transition: color 0.2s ease; font-family: inherit; }
        .tab-btn:hover { color: #2c7be5; }
        .tab-btn.active { color: #2c7be5; border-bottom: 2px solid #2c7be5; margin-bottom: -1px; }

        /* ----- TABLE CARD ----- */
        .table-section { border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03); }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; background: #ffffff; }
        th { background: #eef2f6; color: #2c3e50; font-weight: 600; padding: 1rem 1rem; border-bottom: 1px solid #e2e8f0; }
        td { padding: 1rem 1rem; border-bottom: 1px solid #edf2f7; vertical-align: middle; color: #2d3e50; }
        tr:hover td { background: #fafcff; }
        /* First column left, others center */
        th:first-child, td:first-child { text-align: left; }
        th:not(:first-child), td:not(:first-child) { text-align: center; }

        /* ----- STATUS BADGES ----- */
        .status-badge { display: inline-block; padding: 0.3rem 0.9rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; letter-spacing: 0.3px; }
        .status-submitted { background: #CCF7E0; color: #2D2D2D; }
        .status-pending { background: #fff3cd; color: #856404; }

        /* ----- BUTTONS ----- */
        .btn { border-radius: 4px; border: none; font-weight: 500; font-size: 0.8rem; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-family: inherit; padding: 6px 16px; background: transparent; }
        .btn-info { background: #C8DCFE; color: #3B7DED; border: 2px solid #3B7DED; border-radius: 4px; font-weight: 500; font-size: 0.8rem; text-decoration: none; }
        .btn-info:hover { background: #3b82f6; color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }

        /* ----- YEAR FILTER (matches original style) ----- */
        .row-controls { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.3rem; margin-bottom: 1.5rem; }
        .summary-title { font-size: 1.5rem; font-weight: 600; color: #2c3e50; }
        .col-filter { min-width: 150px; }
        .select-wrapper { position: relative; width: 100%; }
        .select-wrapper select { width: 100%; padding: 0.7rem 2rem 0.7rem 1rem; border-radius: 8px; border: 1px solid #e2e8f0; background: #EDF0F5; font-size: 1rem; font-family: inherit; cursor: pointer; transition: 0.2s; color: #2D2D2D; text-align: center; text-align-last: center; appearance: none; -webkit-appearance: none; -moz-appearance: none; background: #EDF0F5; }
        .select-wrapper select:focus { border-color: #2c7be5; outline: none; box-shadow: 0 0 0 3px rgba(44, 123, 229, 0.1); }
        .dropdown-icon { position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.9rem; pointer-events: none; }

        /* ----- RESPONSIVE ----- */
        @media (max-width: 1024px) { .content { margin-left: 0; padding: 1rem; } }
        @media (max-width: 640px) { .content { margin-left: 0; padding: 1rem; } .tabs { gap: 1rem; } .tab-btn { font-size: 0.9rem; } .table-section { padding: 1rem; } th, td { padding: 0.75rem; } .row-controls { flex-direction: column; align-items: stretch; } .col-filter { width: 100%; } }
    </style>
</head>

<body x-data="{ tab: 'all' }">

    @include('partials.sidebar')

    <div class="content">
            <!-- Header -->
            <h1 class="animate-item" style="animation-delay: 0s;">Govt. Contributions</h1>
            <p class="subhead animate-item" style="animation-delay: 0.1s;">Manage government contributions (SSS, PhilHealth, Pag-IBIG).</p>

            <!-- Tabs -->
            <div class="tabs-wrapper animate-item" style="animation-delay: 0.2s;">
                <div class="tabs">
                    <button @click="tab='all'" :class="tab === 'all' ? 'tab-btn active' : 'tab-btn'">All Contributions</button>
                <button @click="tab='mine'" :class="tab === 'mine' ? 'tab-btn active' : 'tab-btn'">My Contributions</button>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="table-section animate-item" style="animation-delay: 0.3s;">
            <!-- Year Filter -->
            <div class="row-controls">
                <h2 class="summary-title">Contribution Summary</h2>
                <div class="col-filter">
                    <form method="GET" action="{{ request()->url() }}" class="select-wrapper">
                        <select name="year" onchange="this.form.submit()">
                            <option value="">All Years</option>
                            @foreach($years ?? [] as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <!-- ALL CONTRIBUTION -->
                <table x-show="tab === 'all'" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Period Name</th>
                            <th>SSS Total</th>
                            <th>PhilHealth Total</th>
                            <th>Pag-IBIG Total</th>
                            <th>W/ Tax Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contributions ?? [] as $row)
                            <tr>
                                <td>{{ $row->period_name }}</td>
                                <td>₱{{ number_format($row->sss_total, 2) }}</td>
                                <td>₱{{ number_format($row->philhealth_total, 2) }}</td>
                                <td>₱{{ number_format($row->pagibig_total, 2) }}</td>
                                <td>₱{{ number_format($row->tax_total, 2) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $row->status === 'submitted' ? 'submitted' : 'pending' }}">
                                        {{ $row->status === 'submitted' ? 'Submitted' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('payroll.officer.govpay.view', $row->payroll_period_id) }}" class="btn btn-info">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #8ca3bc;">No contribution data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!--MY CONTRIBUTION) -->
                <table x-show="tab === 'mine'" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Period Name</th>
                            <th>SSS Total</th>
                            <th>PhilHealth Total</th>
                            <th>Pag-IBIG Total</th>
                            <th>W/ Tax Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myContributions ?? [] as $row)
                            <tr>
                                <td>{{ $row->period_name }}</td>
                                <td>₱{{ number_format($row->sss_total, 2) }}</td>
                                <td>₱{{ number_format($row->philhealth_total, 2) }}</td>
                                <td>₱{{ number_format($row->pagibig_total, 2) }}</td>
                                <td>₱{{ number_format($row->tax_total, 2) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $row->status === 'submitted' ? 'submitted' : 'pending' }}">
                                        {{ $row->status === 'submitted' ? 'Submitted' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #8ca3bc;">No personal contribution data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>