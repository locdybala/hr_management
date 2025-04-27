@extends('layouts.nav')

@section('title', 'Bảng lương tháng')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Bảng lương tháng {{ $month }}/{{ $year }}</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label for="month" class="form-label">Tháng</label>
                            <select name="month" id="month" class="form-select">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="year" class="form-label">Năm</label>
                            <select name="year" id="year" class="form-select">
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="employee_id" class="form-label">Nhân viên</label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">Tất cả nhân viên</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id', $employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Lọc</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nhân viên</th>
                                    <th>Mã NV</th>
                                    <th>Lương cơ bản</th>
                                    <th>Số ngày công chuẩn</th>
                                    <th>Số ngày công thực tế</th>
                                    <th>Lương/ngày</th>
                                    <th>Tổng lương tháng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaryList as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row['employee']->full_name }}</td>
                                        <td>{{ $row['employee']->employee_code }}</td>
                                        <td>{{ number_format($row['employee']->salary) }}</td>
                                        <td>{{ $row['work_days'] }}</td>
                                        <td>{{ $row['attendance_days'] }}</td>
                                        <td>{{ number_format($row['salary_per_day']) }}</td>
                                        <td class="fw-bold text-primary">{{ number_format($row['total_salary']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu lương.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
