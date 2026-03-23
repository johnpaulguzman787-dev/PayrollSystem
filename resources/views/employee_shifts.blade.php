<!DOCTYPE html>
<html>
<head>
    <title>Employee Shifts Management</title>
    <style>
        .content {margin-left: 280px;padding: 25px;min-height: 100vh;transition: all 0.3s ease;}
        body { font-family: Arial; background:#f4f4f4; margin:0; padding:0;}
        .container { width: 90%; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
        h1 { text-align:center; color:#333; }
        table { width:100%; border-collapse: collapse; margin-top:20px;}
        th, td { padding:10px; border:1px solid #ccc; text-align:center;}
        th { background:#007BFF; color:#fff;}
        .btn { padding:5px 10px; border:none; border-radius:4px; cursor:pointer; margin:2px;}
        .btn-add { background:#28a745; color:#fff; }
        .btn-delete { background:#dc3545; color:#fff; }

        .form-group { margin-bottom:15px;}
        label { display:block; margin-bottom:5px;}
        input, select { width:100%; padding:8px; border-radius:4px; border:1px solid #ccc; }

        .alert { padding:10px; background:#28a745; color:#fff; margin-bottom:15px; border-radius:4px; }
        .alert-error { background:#dc3545; }

        /* Modal */
        .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color: rgba(0,0,0,0.5);}
        .modal-content { background:#fff; margin:10% auto; padding:20px; border-radius:8px; width:400px; position:relative;}
        .close { color:#aaa; position:absolute; right:10px; top:5px; font-size:28px; font-weight:bold; cursor:pointer;}
        .close:hover { color:#000; }
    </style>
</head>
<body class="content">
    
        @include('partials.sidebar')

<div class="container">
    <h1>Employee Shifts Management</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <button class="btn btn-add" onclick="openModal()">Assign Shift</button>

    <!-- Shifts Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Payroll Period</th>
                <th>Shift</th>
                <th>Shift Time</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employee_shifts as $es)
            <tr>
                <td>{{ $es->id }}</td>
                <td>{{ $es->fname }} {{ $es->lname }}</td>
                <td>{{ $es->period_start }} to {{ $es->period_end }}</td>
                <td>{{ $es->shift_name }}</td>
                <td>{{ $es->start_time }} - {{ $es->end_time }}</td>
                <td>{{ $es->shift_date }}</td>
                <td>
                    <a href="/employee-shifts/delete/{{ $es->id }}" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="shiftModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Assign Shift</h3>
        <form method="POST" action="/employee-shifts/store">
            @csrf
            <div class="form-group">
                <label>Employee</label>
                <select name="employee_id" required>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->fname }} {{ $emp->lname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Payroll Period</label>
                <select name="payroll_period_id" required>
                    @foreach($payroll_periods as $pp)
                        <option value="{{ $pp->id }}">{{ $pp->start_date }} to {{ $pp->end_date }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Shift</label>
                <select name="shift_id" required>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Shift Date</label>
                <input type="date" name="shift_date" required>
            </div>
            <button type="submit" class="btn btn-add">Assign</button>
        </form>
    </div>
</div>

<script>
    function openModal(){
        document.getElementById('shiftModal').style.display='block';
    }
    function closeModal(){
        document.getElementById('shiftModal').style.display='none';
    }
    window.onclick=function(event){
        if(event.target==document.getElementById('shiftModal')){
            closeModal();
        }
    }
</script>
</body>
</html>
