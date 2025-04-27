@extends('layouts.nav')

@section('title', 'Lịch sử chấm công')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Lịch sử chấm công</h3>
                    <div>
                        @php
                            $todayAttendance = $attendances->first(function($attendance) {
                                return $attendance->date->isToday();
                            });
                        @endphp

                        @if($todayAttendance)
                            @if($todayAttendance->check_in && !$todayAttendance->check_out)
                                <form method="POST" action="{{ route('employee.attendance.checkOut') }}" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn chấm công ra không?');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Chấm công ra</button>
                                </form>
                            @elseif($todayAttendance->check_in && $todayAttendance->check_out)
                                <span class="badge bg-success">Bạn đã chấm công đầy đủ hôm nay</span>
                            @else
                                <form method="POST" action="{{ route('employee.attendance.checkIn') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Chấm công vào</button>
                                </form>
                            @endif
                        @else
                            <form method="POST" action="{{ route('employee.attendance.checkIn') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Chấm công vào</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
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
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Lọc</button>
                        </div>
                    </form>
                    <div class="alert alert-info">
                        Tổng số ngày công trong tháng: {{ $attendances->whereNotNull('check_in')->count() }}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Ngày</th>
                                    <th>Giờ vào</th>
                                    <th>Giờ ra</th>
                                    <th>Trạng thái</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $attendances->firstItem() + $index }}</td>
                                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                        <td>{{ $attendance->check_in ?? '-' }}</td>
                                        <td>{{ $attendance->check_out ?? '-' }}</td>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Không có dữ liệu chấm công.</td>
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
