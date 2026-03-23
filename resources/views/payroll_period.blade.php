<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Payroll Period Management</title>
    <style>
        .analytics {
            display: grid;
            grid-template-columns: repeat(2,1fr);
            gap: 15px;
        }

        .analytics-card{
            background:#f8f9fb;
            padding:18px;
            border-radius:10px;
            box-shadow:0 3px 10px rgba(0,0,0,0.05);
        }

        .analytics-card h3{
            margin:0;
            font-size:14px;
            color:#666;
        }

        .analytics-card .value{
            font-size:22px;
            font-weight:700;
            margin-top:5px;
        }

        .icon{
            font-size:18px;
            margin-right:6px;
        }

        .trend-up{color:#28a745;}
        .trend-down{color:#dc3545;}
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }
        .content { margin-left: 280px; padding: 30px; min-height: 100vh; }
        .container { background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        h1 { margin-bottom: 25px; color: #2c3e50; }

        .form-group { margin-bottom: 15px; }
        label { font-weight: 600; display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px 10px; border-radius: 6px; border: 1px solid #ccc; transition: 0.2s; }
        input:focus, select:focus { border-color: #007BFF; outline: none; box-shadow: 0 0 5px rgba(0,123,255,0.3); }

        .btn {padding: 7px 14px;border-radius: 6px;border: none;cursor: pointer;font-weight: 500;transition: all 0.25s ease;margin: 2px;}
        .btn:hover {transform: translateY(-2px) scale(1.03);box-shadow: 0 6px 15px rgba(0,0,0,0.15);}
        .btn-add {background: #3b82f6;color: white;border-radius: 8px;font-weight: 600;}
        .btn-delete { background: #dc3545; color: white; }
        .btn-edit { background: #ffc107; color: black; }
        .btn-status { background: #17a2b8; color: white; }

        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; }
        .alert { background: #28a745; color: white; }
        .alert-error { background: #dc3545; color: white; }

        table { width: 100%; border-collapse: collapse; margin-top: 25px; background: white; }
        th { background: #007BFF; color: white; padding: 12px; font-weight: 600; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        tr:hover { background: #f9fbff; }
        /* Center align all table columns */
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        .status-badge { padding: 5px 10px; border-radius: 6px; font-size: 13px; font-weight: 600; text-transform: capitalize; display: inline-block; }
        .draft { background:#6c757d; color:white; }
        .open { background:#17a2b8; color:white; }
        .approved { background:#28a745; color:white; }
        .closed { background:#343a40; color:white; }

        .card { background: #ffffff; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }

        .modal { display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background: white; width: 420px; margin: 8% auto; padding: 25px; border-radius: 12px; position: relative; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from {opacity:0; transform: translateY(-10px);} to {opacity:1; transform: translateY(0);} }
        .close { position: absolute; right: 15px; top: 10px; font-size: 22px; cursor: pointer; }
    </style>
</head>
<body>
<!-- SIDEBAR -->
    @include('partials.sidebar')

    <div class="content">
        <div class="container">
            <h1>Payroll Period Management</h1>

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:30px;">

<div class="card">
<h2>Payroll Trend</h2>
<canvas id="payrollTrendChart" height="120"></canvas>
</div>

<div style="display:flex;flex-direction:column;gap:20px;">

<!-- TOTAL GROSS PAY CARD -->
<div class="card">
<h3>Total Gross Pay</h3>
<div style="font-size:26px;font-weight:bold;margin-top:10px;">
₱{{ number_format($gross,2) }}
</div>
<button class="btn btn-status" onclick="showLineChart()" style="margin-top:10px;">
    <i class="fa fa-chart-line"></i> View Trend
</button>
</div>

<!-- TOTAL EMPLOYEES CARD -->
<div class="card">
<h3>Total Employees</h3>
<div style="font-size:26px;font-weight:bold;margin-top:10px;">
{{ $employees }}
</div>
<button class="btn btn-status" onclick="showBarChart()" style="margin-top:10px;">
    <i class="fa fa-chart-bar"></i> View Departments
</button>
</div>

</div>
</div>
<!-- SEARCH AND ADD BUTTON -->
<div class="card" style="display:flex; justify-content:space-between; align-items:center; gap:15px;">

    <div style="flex:1; position:relative;">
        <input type="text" id="search" placeholder="Search payroll periods..."
               style="width:30%; padding:10px 15px 10px 40px; border-radius:8px; border:1px solid #ccc;">
        <i class="fa fa-search"
           style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#888;"></i>
    </div>

    <button class="btn btn-add" onclick="openAddModal()" 
            style="padding:10px 18px; font-size:14px; white-space:nowrap;">
        <i class="fa fa-plus"></i> Add Payroll Period
    </button>

</div>
<!-- TABLE CARD -->
            <div class="card">
                <table class="table" id="pasyrollperiodTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start</th>
                            <th>End</th>
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
                                @if(in_array($period->status, ['for_approval', 'approved', 'closed']))
                                    <button 
                                        class="btn btn-info"
                                        data-id="{{ $period->id }}"
                                        onclick="openViewModal(this)">
                                        View
                                    </button>
                                @endif
                                @if($period->status == 'draft')
                                   <button 
                                    class="btn btn-edit"
                                    data-id="{{ $period->id }}"
                                    data-name="{{ $period->name }}"
                                    data-start="{{ $period->start_date }}"
                                    data-end="{{ $period->end_date }}"
                                    data-status="{{ $period->status }}"
                                    onclick="openModal(this)">
                                    Edit
                                    </button>
                                @endif
                                @if($period->status != 'closed')
                                    <form method="POST" action="/payroll-period/status/{{ $period->id }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="open">
                                        <button class="btn btn-status">Next</button>
                                    </form>
                                @endif
                                <a href="/payroll-period/delete/{{ $period->id }}" class="btn btn-delete" onclick="return confirm('Are you sure?')"> Delete </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-end mt-3" id="payrollperiodPagination"></ul>
                </nav>
            </div>
        </div>
    </div>

<!--ADD MODAL -->
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

            <button type="submit" class="btn btn-add">Create Payroll</button>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Edit Payroll Period</h3>
            <form method="POST" id="editForm">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="modal_name" required>
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" id="edit_start_date" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" id="edit_end_date" required>
                </div>
                <button type="submit" class="btn btn-edit">Save Changes</button>
            </form>
        </div>
    </div>

<!-- VIEW MODAL -->
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
                <tbody id="view_department">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- VIEW AND EDIT SCRIPT -->
<script>
// ================= VIEW MODAL =================
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

            document.getElementById('viewModal').style.display = 'block';
        })
        .catch(() => alert('Failed to fetch payroll details'));
}

function closeViewModal(){
    document.getElementById('viewModal').style.display = 'none';
}

//ADD MODAL
function openAddModal(){
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal(){
    document.getElementById('addModal').style.display = 'none';
}

//EDIT MODAL
function openModal(button){
    document.getElementById('modal_name').value = button.dataset.name;
    document.getElementById('edit_start_date').value = button.dataset.start;
    document.getElementById('edit_end_date').value = button.dataset.end;

    document.getElementById('editForm').action =
        '/payroll-period/update/' + button.dataset.id;

    document.getElementById('editModal').style.display = 'block';
}

function closeModal(){
    document.getElementById('editModal').style.display = 'none';
}

window.addEventListener('click', function (event) {

    const viewModal = document.getElementById('viewModal');
    const editModal = document.getElementById('editModal');
    const addModal = document.getElementById('addModal');

    if (event.target === viewModal) {
        viewModal.style.display = 'none';
    }

    if (event.target === editModal) {
        editModal.style.display = 'none';
    }

    if (event.target === addModal) {
        addModal.style.display = 'none';
    }

});
</script>

<!-- AUTO NAME SCRIPT -->
<script>
    const startDateInput = document.getElementById('modal_start_date');
    const endDateInput = document.getElementById('modal_end_date');
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
    endDateInput.addEventListener('change', autoFillName);
</script>

<!-- SEARCH AND NAV SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const ROWS_PER_PAGE = 5;

    const table = document.getElementById("pasyrollperiodTable");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const pagination = document.getElementById("payrollperiodPagination");
    const searchInput = document.getElementById("search");

    let currentPage = 1;
    let filteredRows = [...rows];

    function renderTable() {
        tbody.innerHTML = "";

        const start = (currentPage - 1) * ROWS_PER_PAGE;
        const end = start + ROWS_PER_PAGE;

        filteredRows.slice(start, end).forEach(row => {
            tbody.appendChild(row);
        });

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

    searchInput.addEventListener("input", function () {
        const value = this.value.toLowerCase().trim();

        filteredRows = value === ""
            ? [...rows]
            : rows.filter(row =>
                row.textContent.toLowerCase().includes(value)
              );

        currentPage = 1;
        renderTable();
    });

    renderTable();
});
</script>

<!-- GRAPH SCRIPT -->
<script>

const trendLabels = <?php echo json_encode($trendLabels); ?>;
const trendData = <?php echo json_encode($trendData); ?>;
const deductionTrend = <?php echo json_encode($deductionTrend); ?>;

const deptLabels = <?php echo json_encode($deptLabels); ?>;
const deptTotals = <?php echo json_encode($deptTotals); ?>;

const ctx = document.getElementById('payrollTrendChart').getContext('2d');

let chart;

//AUTO COLORS PER DEPARTMENT
function generateColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        const hue = (i * 360 / count);
        colors.push(`hsl(${hue}, 70%, 60%)`);
    }
    return colors;
}

//LINE CHART
function showLineChart(){

    if(chart) chart.destroy();

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Total Net Payroll',
                    data: trendData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'Total Deductions',
                    data: deductionTrend,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.2)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }
            ]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    labels: {
                        font: { size: 13 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₱' + value.toLocaleString()
                    }
                }
            }
        }
    });
}

//BAR CHART
function showBarChart(){

    if(chart) chart.destroy();

    const colors = generateColors(deptLabels.length);

    //CREATE MULTIPLE DATASETS
    const datasets = deptLabels.map((dept, index) => ({
        label: dept,
        data: deptLabels.map((_, i) => i === index ? deptTotals[index] : 0),
        backgroundColor: colors[index],
        borderRadius: 8,
        barPercentage: 1.2, 
        categoryPercentage: 0.5 
    }));

    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: deptLabels,
            datasets: datasets
        },
        options: {
            responsive: true,
            animation: {
                duration: 1000,
                easing: 'easeOutBounce'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// DEFAULT LOAD
showLineChart();

</script>

</body>
</html>