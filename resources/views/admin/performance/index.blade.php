@extends('layouts.nav')

@section('title', 'Danh sách phiếu đánh giá KPI')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Danh sách phiếu đánh giá KPI</h3>
            <a href="{{ route('performance.create') }}" class="btn btn-primary">Thêm mới</a>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-2 align-items-end mb-3">
                <div class="col-md-3">
                    <label for="employee_id" class="form-label">Nhân viên</label>
                    <select name="employee_id" id="employee_id" class="form-select">
                        <option value="">Tất cả nhân viên</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->full_name }} ({{ $employee->employee_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="year" class="form-label">Năm</label>
                    <select name="year" id="year" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="quarter" class="form-label">Quý</label>
                    <select name="quarter" id="quarter" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($quarters as $q)
                            <option value="{{ $q }}" {{ request('quarter') == $q ? 'selected' : '' }}>Quý {{ $q }}</option>
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
                            <th>Năm</th>
                            <th>Quý</th>
                            <th>Điểm tổng</th>
                            <th>Người đánh giá</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $i => $review)
                            <tr>
                                <td>{{ $i + 1 + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
                                <td>{{ $review->employee->full_name ?? '' }}</td>
                                <td>{{ $review->employee->employee_code ?? '' }}</td>
                                <td>{{ $review->year }}</td>
                                <td>{{ $review->quarter }}</td>
                                <td>{{ $review->overall_score }}</td>
                                <td>{{ $review->reviewer->name ?? '' }}</td>
                                <td>
                                    @if($review->status === 'approved')
                                        <span class="badge bg-success">Đã duyệt</span>
                                    @elseif($review->status === 'rejected')
                                        <span class="badge bg-danger">Từ chối</span>
                                    @elseif($review->status === 'submitted')
                                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                    @else
                                        <span class="badge bg-secondary">Nháp</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('performance.show', $review->id) }}" class="btn btn-sm btn-info">Xem</a>
                                    <a href="{{ route('performance.edit', $review->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có dữ liệu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $reviews->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
