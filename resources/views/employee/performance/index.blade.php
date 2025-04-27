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
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Lọc</button>
                        </div>
                    </form>

                    @if($performances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Ngày đánh giá</th>
                                        <th>Điểm tổng</th>
                                        <th>Điểm mạnh</th>
                                        <th>Điểm cần cải thiện</th>
                                        <th>Kế hoạch phát triển</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($performances as $index => $performance)
                                        <tr>
                                            <td>{{ $performances->firstItem() + $index }}</td>
                                            <td>{{ $performance->review_date->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $performance->overall_score >= 8 ? 'success' : ($performance->overall_score >= 6 ? 'warning' : 'danger') }}">
                                                    {{ $performance->overall_score }}/10
                                                </span>
                                            </td>
                                            <td>{{ $performance->strengths }}</td>
                                            <td>{{ $performance->weaknesses }}</td>
                                            <td>{{ $performance->improvements }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $performances->withQueryString()->links('pagination::bootstrap-5') }}
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
