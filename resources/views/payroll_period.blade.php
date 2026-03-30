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
        .analytics-card h3 { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #5a6e85; margin-bottom: 0.6rem; display: flex; align-items: center; gap: 8px; }
        .analytics-card .value { font-size: 1.9rem; font-weight: 700; color: #2D2D2D; line-height: 1.2; margin-bottom: 0.5rem; }
        .period-name { font-size: 0.7rem; color: #8ca3bc; text-align: right; margin-top: auto; padding-top: 0.5rem; border-top: 1px dashed #eef2f9; }
        .table-section { border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; padding: 1.5rem 0 0 0; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03); }
        .row-controls { display: flex; flex-wrap: wrap; align-items: center; gap: 1.3rem; margin-bottom: 1.5rem; padding: 0 1.5rem; }
        .col-search { flex: 2; min-width: 250px; }
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
        th { background: #eef2f6; color: #2c3e50; font-weight: 600; padding: 1rem 1rem; border-bottom: 1px solid #e2e8f0; text-align: center; }
        td { padding: 1rem 1rem; border-bottom: 1px solid #edf2f7; text-align: center; vertical-align: middle; color: #2d3e50; }
        tr:hover td { background: #fafcff; }
        .status-badge { display: inline-block; padding: 0.3rem 0.9rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; letter-spacing: 0.3px; }
        .pending { background: #fff3cd; color: #856404; }
        .completed { background: #d4edda; color: #155724; }
        nav { margin-top: 1.5rem; display: flex; justify-content: flex-end; }
        .pagination { display: flex; gap: 6px; list-style: none; flex-wrap: wrap; }
        .page-item { display: inline-block; }
        .page-link { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 40px; background: #ffffff; border: 1px solid #e2e8f0; color: #2c3e50; text-decoration: none; font-weight: 500; transition: all 0.2s; cursor: pointer; }
        .page-item.active .page-link { background: #2c7be5; border-color: #2c7be5; color: white; }
        .page-link:hover { background: #eef2ff; border-color: #cbd5e1; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
        .modal-content { background: #ffffff; width: 90%; max-width: 500px; border-radius: 28px; padding: 1.8rem 2rem 2rem; position: relative; animation: fadeSlideUp 0.25s ease; box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.25); }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-content h3 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1.2rem; color: #0f2b3b; }
        .close { position: absolute; right: 1.5rem; top: 1.2rem; font-size: 1.6rem; cursor: pointer; color: #8f9eb2; transition: 0.2s; }
        .close:hover { color: #2c3e50; }
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { font-weight: 600; display: block; margin-bottom: 0.4rem; color: #2c3e50; font-size: 0.85rem; }
        .form-group input { width: 100%; padding: 0.7rem 1rem; border-radius: 14px; border: 1px solid #e2e8f0; font-family: inherit; transition: 0.2s; }
        .form-group input:focus { border-color: #2c7be5; outline: none; box-shadow: 0 0 0 3px rgba(44, 123, 229, 0.1); }
        #viewModal .modal-content { max-width: 700px; }
        #viewContent table { margin-top: 0.6rem; font-size: 0.85rem; }
        #viewContent table th, #viewContent table td { border: 1px solid #e9edf2; padding: 0.5rem; }
        hr { margin: 1rem 0; border: none; border-top: 1px solid #eef2f8; }
        .tabs-wrapper { position: relative; border-bottom: 1px solid #e2e8f0; margin-bottom: 1.5rem; padding: 0 1.5rem; }
        .tabs { display: flex; gap: 2rem; list-style: none; margin: 0; padding: 0; }
        .tab-btn { background: transparent; border: none; padding: 0.75rem 0; font-size: 1rem; font-weight: 600; color: #64748b; cursor: pointer; transition: color 0.2s ease; font-family: inherit; display: inline-block; }
        .tab-btn:hover { color: #2c7be5; }
        .tab-btn.active { color: #2c7be5; }
        .underline { position: absolute; bottom: -1px; left: 0; height: 2px; background-color: #2c7be5; border-radius: 2px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 0; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        @media (max-width: 1024px) { .content { margin-left: 0; padding: 1rem; } .analytics { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .analytics { grid-template-columns: 1fr; } .row-controls { flex-direction: column; align-items: stretch; } .col-button { margin-left: 0; display: flex; justify-content: center; } .btn-add { width: 100%; max-width: 100%; } .table-section { padding: 1rem; } .tabs { gap: 1rem; } .tab-btn { font-size: 0.9rem; } }
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
                            <th width="280">Actions</th>
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
        <div class="table-section" style="padding: 1.5rem; text-align: center;">
            <h3>Salary Structure</h3>
            <p>Content will appear here.</p>
        </div>
    </div>
    <div id="benefitsTab" class="tab-content">
        <div class="table-section" style="padding: 1.5rem; text-align: center;">
            <h3>Benefits</h3>
            <p>Content will appear here.</p>
        </div>
    </div>
    <div id="contributionsTab" class="tab-content">
        <div class="table-section" style="padding: 1.5rem; text-align: center;">
            <h3>Contributions</h3>
            <p>Content will appear here.</p>
        </div>
    </div>
</div>

<!-- Modals (unchanged) -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h3>Add Payroll Period</h3>
        <form method="POST" action="/payroll-period/store">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="modal_payroll_name" required>
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" id="modal_start_date" required>
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" id="modal_end_date" required>
            </div>
            <div class="form-group">
                <label>Payout Date</label>
                <input type="date" name="payout_date" id="modal_payout_date" required>
            </div>
            <button type="submit" class="btn btn-add" style="width:100%; justify-content:center;">Create Payroll</button>
        </form>
    </div>
</div>

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
                <thead>
                    <tr>
                        <th style="border:1px solid #ccc; padding:6px;">Department</th>
                        <th style="border:1px solid #ccc; padding:6px;">Net Pay</th>
                    </tr>
                </thead>
                <tbody id="view_department"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
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
                tr.innerHTML = `
                    <td style="border:1px solid #ccc; padding:6px;">${dept.name}</td>
                    <td style="border:1px solid #ccc; padding:6px;">
                        ₱${Number(dept.total).toLocaleString('en-US',{minimumFractionDigits:2})}
                    </td>`;
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
        if (start && end) {
            nameInput.value = `Payroll ${formatShortMonthDay(start)} - ${formatShortMonthDay(end)}`;
        }
    }
    startDateInput.addEventListener('change', autoFillName);
    endDateInput.addEventListener('change', function() {
    autoFillName();
    autoFillPayoutDate(); 
});
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

    const activeTab = document.querySelector('.tab-btn.active');
    updateUnderline(activeTab);

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.getAttribute('data-tab');
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

function autoFillPayoutDate() {
    const end = endDateInput.value;
    if (end) {
        const date = new Date(end);
        date.setDate(date.getDate() + 5);
        payoutDateInput.value = date.toISOString().split('T')[0];
    }
}
</script>

</body>
</html>