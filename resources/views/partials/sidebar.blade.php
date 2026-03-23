<style>
    .sidebar {
        width: 220px;
        height: 100vh;
        background-color: #1f2937; /* dark gray */
        color: #fff;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
        font-family: Arial, sans-serif;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 18px;
        letter-spacing: 1px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom: 10px;
    }

    .sidebar ul li a {
        display: block;
        padding: 12px 20px;
        color: #e5e7eb;
        text-decoration: none;
        transition: background 0.3s;
    }

    .sidebar ul li a:hover,
    .sidebar ul li a.active {
        background-color: #374151;
        color: #ffffff;
    }
</style>

<div class="sidebar">
    <h2>HRIS</h2>

    <ul>
        <li>
            <a href="{{ url('/attendance-logs') }}"
               class="{{ request()->is('attendance-logs*') ? 'active' : '' }}">
                Attendance
            </a>
        </li>

        <li>
            <a href="{{ url('/employees') }}"
               class="{{ request()->is('employees*') ? 'active' : '' }}">
                Employees
            </a>
        </li>

        <li>
            <a href="{{ url('/employee-shifts') }}"
               class="{{ request()->is('employee-shifts*') ? 'active' : '' }}">
                Employee Shifts
            </a>
        </li>

        <li>
            <a href="{{ url('/shifts') }}"
               class="{{ request()->is('shifts*') ? 'active' : '' }}">
                Shifts
            </a>
        </li>

        <li>
            <a href="{{ url('/payroll-period') }}"
               class="{{ request()->is('payroll_period*') ? 'active' : '' }}">
                Payroll Period
            </a>
        </li>

        <li>
            <a href="{{ url('/payslip') }}"
               class="{{ request()->is('payslip*') ? 'active' : '' }}">
                Payslips
            </a>
        </li>

        <li>
            <a href="{{ url('/payroll-items-settings') }}"
               class="{{ request()->is('payroll-items-settings*') ? 'active' : '' }}">
                Payroll Items
            </a>
        </li>

        <li>
            <a href="{{ url('/government-contributions') }}"
               class="{{ request()->is('government-contributions*') ? 'active' : '' }}">
                Government Items
            </a>
        </li>
    </ul>
</div>