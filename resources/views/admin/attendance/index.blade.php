@extends('layouts.nav')

@section('title', 'Quản lý chấm công')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Danh sách chấm công</h3>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Thêm chấm công
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="GET" action="" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label for="date" class="form-label">Ngày</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ request('date', $date) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="employee_id" class="form-label">Nhân viên</label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">-- Tất cả nhân viên --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="month" class="form-label">Tháng</label>
                            <select name="month" id="month" class="form-select">
                                <option value="">-- Chọn tháng --</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="year" class="form-label">Năm</label>
                            <select name="year" id="year" class="form-select">
                                <option value="">-- Chọn năm --</option>
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
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
                                    <th>Ngày</th>
                                    <th>Giờ vào</th>
                                    <th>Giờ ra</th>
                                    <th>Vị trí vào</th>
                                    <th>Vị trí ra</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $attendances->firstItem() + $index }}</td>
                                        <td>{{ $attendance->employee->full_name ?? '-' }}</td>
                                        <td>{{ $attendance->employee->employee_code ?? '-' }}</td>
                                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                        <td>{{ $attendance->check_in ?? '-' }}</td>
                                        <td>{{ $attendance->check_out ?? '-' }}</td>
                                        <td>{{ $attendance->check_in_location ?? '-' }}</td>
                                        <td>{{ $attendance->check_out_location ?? '-' }}</td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'present' => 'Có mặt',
                                                    'late' => 'Đi muộn',
                                                    'early_leave' => 'Về sớm',
                                                    'absent' => 'Vắng mặt',
                                                ];
                                                $badgeMap = [
                                                    'present' => 'success',
                                                    'late' => 'warning',
                                                    'early_leave' => 'info',
                                                    'absent' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $badgeMap[$attendance->status] ?? 'secondary' }}">
                                                {{ $statusMap[$attendance->status] ?? $attendance->status }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->note }}</td>
                                        <td>
                                            <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                                            <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá bản ghi này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xoá</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">Không có dữ liệu chấm công.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $attendances->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
