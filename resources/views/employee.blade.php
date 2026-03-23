<!DOCTYPE html>
<html>
<head>
    <title>Employee & Department Management</title>
    <style>
        .content {margin-left: 280px;padding: 25px;min-height: 100vh;transition: all 0.3s ease;}
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin:0; padding:0; }
        .container { width: 90%; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { text-align:center; color:#333; margin-bottom:15px; }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { padding:10px; border:1px solid #ccc; text-align:center; }
        th { background:#007BFF; color:#fff; }
        .btn { padding:5px 10px; border:none; border-radius:4px; cursor:pointer; margin:2px; }
        .btn-add { background:#28a745; color:#fff; }
        .btn-delete { background:#dc3545; color:#fff; }
        .btn-edit { background:#ffc107; color:#fff; }
        .form-group { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; }
        input, select { width:100%; padding:8px; border-radius:4px; border:1px solid #ccc; }
        .alert { padding:10px; background:#28a745; color:#fff; margin-bottom:15px; border-radius:4px; }
        .alert-error { background:#dc3545; }

        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);}
        .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 400px; position: relative;}
        .close { color: #aaa; position: absolute; right: 10px; top: 5px; font-size: 28px; font-weight: bold; cursor: pointer;}
        .close:hover { color: #000; }
    </style>
</head>
<body class="content">

@include('partials.sidebar')

<div class="container">
    <h1>Employee Management</h1>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}

            @if(session('employee_id'))
                <br><strong>Employee ID:</strong> {{ session('employee_id') }}
            @endif

            @if(session('generated_password'))
                <br><strong style="font-size:18px;">
                    🔑 Generated Password: 
                    <span style="background:#000;color:#0f0;padding:5px 10px;border-radius:5px;">
                        {{ session('generated_password') }}
                    </span>
                </strong>
            @endif
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <button class="btn btn-add" onclick="openEmployeeModal()">Add Employee</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Contact</th>
                <th>Date Hired</th>
                <th>Department</th>
                <th>Job Title</th>
                <th>Basic Salary</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
            <tr>
                <td>{{ $emp->employee_id }}</td>
                <td>{{ $emp->fname }} {{ $emp->mname }} {{ $emp->lname }}</td>
                <td>{{ $emp->email }}</td>
                <td>{{ $emp->gender }}</td>
                <td>{{ $emp->date_of_birth }}</td>
                <td>{{ $emp->contact_no }}</td>
                <td>{{ $emp->date_hired }}</td>
                <td>{{ $emp->department_name }}</td>
                <td>{{ $emp->job_title }}</td>
                <td>₱ {{ number_format($emp->basic_salary,2) }}</td>
                <td>{{ $emp->address }}</td>
                <td>
                    <button class="btn btn-edit" 
                    onclick="openEmployeeModal(
                        '{{ $emp->employee_id }}',
                        '{{ $emp->fname }}',
                        '{{ $emp->mname }}',
                        '{{ $emp->lname }}',
                        '{{ $emp->email }}',
                        '{{ $emp->gender }}',
                        '{{ $emp->date_of_birth }}',
                        '{{ $emp->contact_no }}',
                        '{{ $emp->date_hired }}',
                        '{{ $emp->department_id }}',
                        '{{ $emp->job_title_id }}',
                        '{{ $emp->basic_salary }}',
                        '{{ $emp->address }}'
                    )">Edit</button>

                    <a href="/employees/delete/{{ $emp->employee_id }}" 
                       class="btn btn-delete" 
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Departments</h2>

    <form method="POST" action="/departments/store">
        @csrf
        <div class="form-group">
            <label>Department Name</label>
            <input type="text" name="name" required>
        </div>
        <button type="submit" class="btn btn-add">Add Department</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Department Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $dept)
            <tr>
                <td>{{ $dept->id }}</td>
                <td>{{ $dept->name }}</td>
                <td>
                    <a href="/departments/delete/{{ $dept->id }}" 
                       class="btn btn-delete" 
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Job Titles</h2>

<form method="POST" action="/jobtitles/store">
    @csrf

    <div class="form-group">
        <label>Job Title</label>
        <input type="text" name="title" required>
    </div>

    <div class="form-group">
        <label>Department</label>
        <select name="department_id" required>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-add">Add Job Title</button>
</form>
<table>
<thead>
<tr>
<th>ID</th>
<th>Title</th>
<th>Department</th>
<th>Action</th>
</tr>
</thead>

<tbody>
@foreach($job_titles as $job)
<tr>
<td>{{ $job->id }}</td>
<td>{{ $job->title }}</td>
<td>
    @foreach($departments as $dept)
        @if($dept->id == $job->department_id)
            {{ $dept->name }}
        @endif
    @endforeach
</td>

<td>
<a href="/jobtitles/delete/{{ $job->id }}"
class="btn btn-delete"
onclick="return confirm('Delete job title?')">
Delete
</a>
</td>

</tr>
@endforeach
</tbody>
</table>
</div>

<!-- Employee Modal -->
<div id="employeeModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEmployeeModal()">&times;</span>
        <h3 id="employeeModalTitle">Add Employee</h3>
        <form method="POST" id="employeeForm">
            @csrf
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="fname" id="emp_fname" required>
            </div>
            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="mname" id="emp_mname">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lname" id="emp_lname" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="emp_email" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" id="emp_gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" id="emp_dob" required>
            </div>
            <div class="form-group">
                <label>Contact No</label>
                <input type="text" name="contact_no" id="emp_contact">
            </div>
            <div class="form-group">
                <label>Date Hired</label>
                <input type="date" name="date_hired" id="emp_hired" required>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" id="emp_department" required>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>

                <div class="form-group">
                <label>Job Title</label>
                <select name="job_title_id" id="emp_job_title" required>
                    @foreach($job_titles as $job)
                        <option value="{{ $job->id }}" data-dept="{{ $job->department_id }}">
                            {{ $job->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            </div>
            <div class="form-group">
                <label>Basic Salary</label>
                <input type="number" step="0.01" name="basic_salary" id="emp_salary" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" id="emp_address">
            </div>
            <button type="submit" class="btn btn-add">Save</button>
        </form>
    </div>
</div>

<script>
    function openEmployeeModal(
    id=null, fname='', mname='', lname='', email='', gender='', dob='', 
    contact='', hired='', dept_id='', job_id='', salary='', address=''
){
    document.getElementById('employeeModal').style.display = 'block';

    if(id){
        document.getElementById('employeeModalTitle').innerText = 'Edit Employee';
        document.getElementById('employeeForm').action = '/employees/update/' + id;

        emp_fname.value = fname;
        emp_mname.value = mname;
        emp_lname.value = lname;
        emp_email.value = email;
        emp_gender.value = gender;
        emp_dob.value = dob;
        emp_contact.value = contact;
        emp_hired.value = hired;
        emp_department.value = dept_id;
        emp_salary.value = salary;
        emp_address.value = address;
        

        // 🔥 FILTER FIRST THEN SET JOB
        setTimeout(() => {
            filterJobTitles();
            emp_job_title.value = job_id;
        }, 100);

    } else {
        document.getElementById('employeeModalTitle').innerText = 'Add Employee';
        document.getElementById('employeeForm').action = '/employees/store';
        document.getElementById('employeeForm').reset();

        filterJobTitles();
    }
}

function closeEmployeeModal(){
    document.getElementById('employeeModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('employeeModal')) {
        closeEmployeeModal();
    }
}
</script>
<script>
function filterJobTitles() {
    let deptId = document.getElementById('emp_department').value;
    let jobSelect = document.getElementById('emp_job_title');

    Array.from(jobSelect.options).forEach(option => {
        if (!option.value) return;

        if (option.getAttribute('data-dept') == deptId) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });

    jobSelect.value = "";
}

// 🔥 AUTO FILTER WHEN DEPARTMENT CHANGES
document.getElementById('emp_department').addEventListener('change', filterJobTitles);
</script>
</body>
</html>
