<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .sidebar {
        width: 260px;
        height: 100vh;
        background: linear-gradient(135deg, #1a2a3a 0%, #0f1a24 100%);
        color: #e2e8f0;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 1.5rem;
        transition: width 0.3s ease;
        box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08);
        overflow-y: auto;
        z-index: 1000;
    }

    /* Custom scrollbar */
    .sidebar::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar::-webkit-scrollbar-track {
        background: #2d3a47;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: #5c6e7e;
        border-radius: 4px;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1.5rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: white;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 0.8rem;
        margin-left: 1rem;
        margin-right: 1rem;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin: 0.25rem 0;
    }

    .sidebar ul li a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.8rem 1.2rem;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.25s ease;
        font-size: 0.95rem;
        font-weight: 500;
        border-radius: 8px;
        margin: 0 0.75rem;
    }

    .sidebar ul li a i {
        width: 1.5rem;
        font-size: 1.1rem;
        text-align: center;
    }

    .sidebar ul li a:hover {
        background: rgba(44, 123, 229, 0.15);
        color: #ffffff;
        transform: translateX(4px);
    }

    .sidebar ul li a.active {
        background: #2c7be5;
        color: white;
        box-shadow: 0 4px 12px rgba(44, 123, 229, 0.3);
    }

    /* Responsive: on smaller screens, collapse to icon‑only */
    @media (max-width: 768px) {
        .sidebar {
            width: 80px;
        }

        .sidebar h2 {
            font-size: 0;
            visibility: hidden;
            margin: 0 0 1rem 0;
            padding: 0;
            border: none;
        }

        .sidebar ul li a span {
            display: none;
        }

        .sidebar ul li a {
            justify-content: center;
            padding: 0.8rem 0;
            margin: 0 0.5rem;
            gap: 0;
        }

        .sidebar ul li a i {
            margin: 0;
            font-size: 1.3rem;
        }

        .sidebar ul li a:hover {
            transform: translateX(0);
        }
    }

    @media (max-width: 480px) {
        .sidebar {
            width: 70px;
        }
        .sidebar ul li a i {
            font-size: 1.2rem;
        }
    }

    /* Adjust main content margin (you may need to add this to your main container) */
    .main-content {
        margin-left: 260px;
        transition: margin-left 0.3s ease;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 80px;
        }
    }
    @media (max-width: 480px) {
        .main-content {
            margin-left: 70px;
        }
    }
</style>

<div class="sidebar">
    <h2>HRIS</h2>
    <ul>
        <li>
            <a href="{{ url('/payroll-period') }}" class="{{ request()->is('payroll_period*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Payroll Period</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/payslip') }}" class="{{ request()->is('payslip*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <span>Payslips</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/payroll-officer/gov-contributions') }}" class="{{ request()->is('payroll-officer/gov-contributions*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Government Contribution</span>
            </a>
        </li>
    </ul>
</div>