<!DOCTYPE html>
<html>
<head>
    <title>Shifts Management</title>
    <style>
        .content {margin-left: 280px;padding: 25px;min-height: 100vh;transition: all 0.3s ease;}
        body { font-family: Arial; background:#f4f4f4; margin:0; padding:0; }
        .conphp tainer { width:90%; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
        h1 { text-align:center; }
        table { width:100%; border-collapse:collapse; margin-top:20px;}
        th, td { padding:10px; border:1px solid #ccc; text-align:center;}
        th { background:#007BFF; color:#fff;}
        .btn { padding:5px 10px; border:none; border-radius:4px; cursor:pointer; margin:2px;}
        .btn-add { background:#28a745; color:#fff;}
        .btn-delete { background:#dc3545; color:#fff;}
        .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.5);}
        .modal-content { background:#fefefe; margin:10% auto; padding:20px; border-radius:8px; width:400px; position:relative;}
        .close { position:absolute; right:10px; top:5px; font-size:28px; cursor:pointer; color:#aaa; }
        .close:hover { color:#000; }
        .alert { padding:10px; background:#28a745; color:#fff; margin-bottom:15px; border-radius:4px; }
        .alert-error { background:#dc3545; }
        input, select { width:100%; padding:8px; margin-bottom:10px; border-radius:4px; border:1px solid #ccc;}
    </style>
</head>
<body class="content">
<div class="container">

    @include('partials.sidebar')

    <h1>Shifts Management</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <button class="btn btn-add" onclick="openModal()">Add Shift</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Shift Name</th>
                <th>Department</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shifts as $shift)
            <tr>
                <td>{{ $shift->id }}</td>
                <td>{{ $shift->name }}</td>
                <td>{{ $shift->department_name }}</td>
                <td>{{ $shift->start_time }}</td>
                <td>{{ $shift->end_time }}</td>
                <td>
                    <a href="/shifts/edit/{{ $shift->id }}" class="btn btn-edit">Edit</a>
                    <a href="/shifts/delete/{{ $shift->id }}" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Shift Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Add Shift</h3>
        <form method="POST" action="/shifts/store">
            @csrf
            <label>Shift Name</label>
            <input type="text" name="name" required>

            <label>Department</label>
            <select name="department_id" required>
                <option value="">Select Department</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>

            <label>Start Time</label>
            <input type="time" name="start_time" required>

            <label>End Time</label>
            <input type="time" name="end_time" required>

            <button type="submit" class="btn btn-add">Save</button>
        </form>
    </div>
</div>

<script>
function openModal(){ document.getElementById('addModal').style.display='block'; }
function closeModal(){ document.getElementById('addModal').style.display='none'; }
window.onclick = function(event){
    if(event.target == document.getElementById('addModal')) closeModal();
}
</script>
</body>
</html>
