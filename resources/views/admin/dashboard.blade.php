@extends('layouts.nav')

@section('title', 'Dashboard Quản trị')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Tổng số nhân viên</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ App\Models\Employee::count() }}">0</span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-success-subtle text-success">+{{ App\Models\Employee::where('status', 'active')->count() }} đang làm việc</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-primary-subtle rounded-circle">
                                    <i class="bi bi-people-fill text-primary font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Phòng ban</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ App\Models\Department::count() }}">0</span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-info-subtle text-info">Quản lý phòng ban</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-success-subtle rounded-circle">
                                    <i class="bi bi-building text-success font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Chức vụ</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ App\Models\Position::count() }}">0</span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-warning-subtle text-warning">Quản lý chức vụ</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-warning-subtle rounded-circle">
                                    <i class="bi bi-person-badge text-warning font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Đang làm việc</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="{{ App\Models\Employee::where('status', 'active')->count() }}">0</span>
                            </h4>
                            <div class="text-nowrap">
                                <span class="badge bg-danger-subtle text-danger">-{{ App\Models\Employee::where('status', 'inactive')->count() }} không hoạt động</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-info-subtle rounded-circle">
                                    <i class="bi bi-person-check text-info font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Thống kê chấm công tháng này</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Trạng thái</th>
                                    <th>Số lượng</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = App\Models\Attendance::whereMonth('date', now()->month)
                                        ->whereYear('date', now()->year)
                                        ->count();
                                    $statuses = [
                                        'present' => 'Có mặt',
                                        'late' => 'Đi muộn',
                                        'early_leave' => 'Về sớm',
                                        'absent' => 'Vắng mặt'
                                    ];
                                @endphp
                                @foreach($statuses as $key => $label)
                                    @php
                                        $count = App\Models\Attendance::whereMonth('date', now()->month)
                                            ->whereYear('date', now()->year)
                                            ->where('status', $key)
                                            ->count();
                                        $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $count }}</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-{{ 
                                                    $key == 'present' ? 'success' : 
                                                    ($key == 'late' ? 'warning' : 
                                                    ($key == 'early_leave' ? 'info' : 'danger')) 
                                                }}" role="progressbar" style="width: {{ $percentage }}%;" 
                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">{{ $percentage }}%</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Thống kê KPI quý này</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Trạng thái</th>
                                    <th>Số lượng</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = App\Models\PerformanceReview::where('quarter', ceil(now()->month / 3))
                                        ->where('year', now()->year)
                                        ->count();
                                    $statuses = [
                                        'draft' => 'Nháp',
                                        'submitted' => 'Chờ duyệt',
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Từ chối'
                                    ];
                                @endphp
                                @foreach($statuses as $key => $label)
                                    @php
                                        $count = App\Models\PerformanceReview::where('quarter', ceil(now()->month / 3))
                                            ->where('year', now()->year)
                                            ->where('status', $key)
                                            ->count();
                                        $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $count }}</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-{{ 
                                                    $key == 'approved' ? 'success' : 
                                                    ($key == 'submitted' ? 'primary' : 
                                                    ($key == 'rejected' ? 'danger' : 'secondary')) 
                                                }}" role="progressbar" style="width: {{ $percentage }}%;" 
                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">{{ $percentage }}%</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter-value');
        const speed = 200;

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCounter, 1);
            } else {
                counter.innerText = target;
            }

            function updateCounter() {
                const count = +counter.innerText;
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(updateCounter, 1);
                } else {
                    counter.innerText = target;
                }
            }
        });
    });
</script>
@endpush
@endsection
