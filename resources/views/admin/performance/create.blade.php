@extends('layouts.nav')

@section('title', 'Thêm phiếu đánh giá KPI')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Thêm phiếu đánh giá KPI</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <form action="{{ route('performance.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="employee_id" class="form-label">Nhân viên <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            <option value="">Chọn nhân viên</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }} ({{ $employee->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="year" class="form-label">Năm <span class="text-danger">*</span></label>
                        <select name="year" id="year" class="form-select" required>
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ old('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="quarter" class="form-label">Quý <span class="text-danger">*</span></label>
                        <select name="quarter" id="quarter" class="form-select" required>
                            @foreach($quarters as $q)
                                <option value="{{ $q }}" {{ old('quarter') == $q ? 'selected' : '' }}>Quý {{ $q }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="overall_score" class="form-label">Điểm tổng <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="overall_score" id="overall_score" class="form-control" value="{{ old('overall_score') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="strengths" class="form-label">Điểm mạnh</label>
                        <textarea name="strengths" id="strengths" class="form-control">{{ old('strengths') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="weaknesses" class="form-label">Điểm yếu</label>
                        <textarea name="weaknesses" id="weaknesses" class="form-control">{{ old('weaknesses') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="improvements" class="form-label">Đề xuất cải thiện</label>
                        <textarea name="improvements" id="improvements" class="form-control">{{ old('improvements') }}</textarea>
                    </div>
                </div>
                <h5 class="mt-4">Kết quả từng KPI</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>KPI</th>
                                <th>Đơn vị</th>
                                <th>Trọng số</th>
                                <th>Giá trị mục tiêu</th>
                                <th>Giá trị thực tế</th>
                                <th>Điểm (%)</th>
                                <th>Nhận xét</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kpis as $kpi)
                                <tr>
                                    <td>
                                        {{ $kpi->name }}
                                        <input type="hidden" name="kpi_results[{{ $loop->index }}][kpi_id]" value="{{ $kpi->id }}">
                                    </td>
                                    <td>{{ $kpi->unit }}</td>
                                    <td>{{ $kpi->weight }}</td>
                                    <td>
                                        <input type="number" step="0.01" name="kpi_results[{{ $loop->index }}][target_value]" class="form-control" value="{{ $kpi->target_value }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="kpi_results[{{ $loop->index }}][actual_value]" class="form-control" value="{{ old('kpi_results.'.$loop->index.'.actual_value') }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" max="100" name="kpi_results[{{ $loop->index }}][score]" class="form-control" value="{{ old('kpi_results.'.$loop->index.'.score') }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="kpi_results[{{ $loop->index }}][comment]" class="form-control" value="{{ old('kpi_results.'.$loop->index.'.comment') }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Lưu phiếu đánh giá</button>
                    <a href="{{ route('performance.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
