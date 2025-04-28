@extends('layouts.nav')

@section('title', 'Kết quả đánh giá KPI')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Kết quả đánh giá KPI</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="GET" action="" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label for="year" class="form-label">Năm</label>
                            <select name="year" id="year" class="form-select">
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="quarter" class="form-label">Quý</label>
                            <select name="quarter" id="quarter" class="form-select">
                                @for($q = 1; $q <= 4; $q++)
                                    <option value="{{ $q }}" {{ $quarter == $q ? 'selected' : '' }}>Quý {{ $q }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Lọc</button>
                        </div>
                    </form>

                    @if($reviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Năm</th>
                                        <th>Quý</th>
                                        <th>Điểm tổng</th>
                                        <th>Trạng thái</th>
                                        <th>Người đánh giá</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $index => $review)
                                        <tr>
                                            <td>{{ $reviews->firstItem() + $index }}</td>
                                            <td>{{ $review->year }}</td>
                                            <td>Quý {{ $review->quarter }}</td>
                                            <td>
                                                <span class="badge bg-{{ $review->overall_score >= 8 ? 'success' : ($review->overall_score >= 6 ? 'warning' : 'danger') }}">
                                                    {{ number_format($review->overall_score, 1) }}/10
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $review->status == 'approved' ? 'success' : 
                                                    ($review->status == 'submitted' ? 'primary' : 
                                                    ($review->status == 'rejected' ? 'danger' : 'secondary')) 
                                                }}">
                                                    {{ 
                                                        $review->status == 'approved' ? 'Đã duyệt' : 
                                                        ($review->status == 'submitted' ? 'Chờ duyệt' : 
                                                        ($review->status == 'rejected' ? 'Từ chối' : 'Nháp')) 
                                                    }}
                                                </span>
                                            </td>
                                            <td>{{ $review->reviewer->name }}</td>
                                            <td>
                                                <a href="{{ route('employee.performance.show', $review->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $reviews->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            Không có kết quả đánh giá KPI cho quý {{ $quarter }} năm {{ $year }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
