<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang quản lý')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6fa;
        }

        .main-navbar {
            background: #fff;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            padding: 0.5rem 1.5rem;
        }

        .main-navbar .nav-link {
            color: #222;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 2rem 2rem 0 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }

        .main-navbar .nav-link.active,
        .main-navbar .nav-link:focus,
        .main-navbar .nav-link:hover {
            background: #f6e9da;
            color: #d49b2a;
        }

        .main-navbar .nav-link .bi {
            font-size: 1.1rem;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #eee;
        }

        .profile-name {
            font-weight: 600;
        }

        .profile-role {
            font-size: 0.9em;
            color: #888;
        }

        .help-btn {
            background: #ff9800;
            color: #fff;
            border-radius: 1rem;
            font-size: 0.9em;
            padding: 0.2rem 0.7rem;
            margin-right: 0.5rem;
            border: none;
        }

        .notif-badge {
            background: #ff3d3d;
            color: #fff;
            border-radius: 50%;
            font-size: 0.8em;
            padding: 0.2em 0.6em;
            position: relative;
            top: -8px;
            left: -8px;
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="main-navbar d-flex justify-content-between align-items-center">
        <ul class="nav">
            @auth
                @if (auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-house-door"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/employees*') ? 'active' : '' }}"
                            href="{{ route('employees.index') }}">
                            <i class="bi bi-person-lines-fill"></i> Quản lý nhân viên
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/departments*') ? 'active' : '' }}"
                            href="{{ route('departments.index') }}">
                            <i class="bi bi-building"></i> Quản lý phòng ban
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/positions*') ? 'active' : '' }}"
                            href="{{ route('positions.index') }}">
                            <i class="bi bi-person-badge"></i> Quản lý chức vụ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/attendance*') ? 'active' : '' }}"
                            href="{{ route('attendance.index') }}">
                            <i class="bi bi-calendar-check"></i> Chấm công
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/salary*') ? 'active' : '' }}"
                            href="{{ route('salary.index') }}">
                            <i class="bi bi-cash-stack"></i> Quản lý lương
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/performance*') ? 'active' : '' }}"
                            href="{{ route('performance.index') }}">
                            <i class="bi bi-bar-chart-line"></i> Quản lý KPI
                        </a>
                    </li>
                @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('employee/dashboard*') ? 'active' : '' }}"
                        href="{{ route('employee.dashboard') }}">
                        <i class="bi bi-house-door"></i> Trang chủ
                    </a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('employee/attendance*') ? 'active' : '' }}"
                            href="{{ route('attendanceIndex') }}">
                            <i class="bi bi-calendar-check"></i> Chấm công
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('employee/salary*') ? 'active' : '' }}"
                            href="{{ route('salaryIndex') }}">
                            <i class="bi bi-cash-stack"></i> Quản lý lương
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('employee/performance*') ? 'active' : '' }}"
                            href="{{ route('performanceIndex') }}">
                            <i class="bi bi-bar-chart-line"></i> Quản lý KPI
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
        <div class="profile-info">
            <div class="text-end">
                @auth
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown">

                        <div class="profile-name">{{ auth()->user()->name }}</div>
                        @if (auth()->user()->role !== 'admin')
                            <div class="profile-role">{{ auth()->user()->employee->position->name }}</div>
                        @endif

                        <img src="https://randomuser.me/api/portraits/men/1.jpg" class="profile-avatar" alt="avatar">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('user.edit') }}">Thông tin cá nhân</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                    </li>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container-fluid py-4">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
