@extends('layouts.nav')

@section('title', 'Chỉnh sửa đánh giá KPI')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Chỉnh sửa đánh giá KPI</h3>
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
                    <form action="{{ route('performance.update', $review) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="employee_id" class="form-label">Nhân viên <span class="text-danger">*</span></label>
                                <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $review->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} ({{ $employee->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="year" class="form-label">Năm <span class="text-danger">*</span></label>
                                <select name="year" id="year" class="form-select @error('year') is-invalid @enderror" required>
                                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" {{ old('year', $review->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="quarter" class="form-label">Quý <span class="text-danger">*</span></label>
                                <select name="quarter" id="quarter" class="form-select @error('quarter') is-invalid @enderror" required>
                                    @for($q = 1; $q <= 4; $q++)
                                        <option value="{{ $q }}" {{ old('quarter', $review->quarter) == $q ? 'selected' : '' }}>Quý {{ $q }}</option>
                                    @endfor
                                </select>
                                @error('quarter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="overall_score" class="form-label">Tổng điểm <span class="text-danger">*</span></label>
                                <input type="number" name="overall_score" id="overall_score" class="form-control @error('overall_score') is-invalid @enderror" value="{{ old('overall_score', $review->overall_score) }}" min="0" max="100" required>
                                @error('overall_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status', $review->status) == 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="submitted" {{ old('status', $review->status) == 'submitted' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="approved" {{ old('status', $review->status) == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="rejected" {{ old('status', $review->status) == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="strengths" class="form-label">Điểm mạnh</label>
                            <textarea name="strengths" id="strengths" class="form-control @error('strengths') is-invalid @enderror" rows="2">{{ old('strengths', $review->strengths) }}</textarea>
                            @error('strengths')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="weaknesses" class="form-label">Điểm yếu</label>
                            <textarea name="weaknesses" id="weaknesses" class="form-control @error('weaknesses') is-invalid @enderror" rows="2">{{ old('weaknesses', $review->weaknesses) }}</textarea>
                            @error('weaknesses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="improvements" class="form-label">Đề xuất cải thiện</label>
                            <textarea name="improvements" id="improvements" class="form-control @error('improvements') is-invalid @enderror" rows="2">{{ old('improvements', $review->improvements) }}</textarea>
                            @error('improvements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h5 class="mt-4">Kết quả từng KPI</h5>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>KPI</th>
                                        <th>Mục tiêu</th>
                                        <th>Thực tế</th>
                                        <th>Điểm</th>
                                        <th>Nhận xét</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kpis as $kpi)
                                        @php
                                            $result = $review->kpiResults->where('kpi_id', $kpi->id)->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $kpi->name }}</td>
                                            <td><input type="number" step="0.01" name="kpi[{{ $kpi->id }}][target_value]" class="form-control" value="{{ old('kpi.'.$kpi->id.'.target_value', $result->target_value ?? '') }}"></td>
                                            <td><input type="number" step="0.01" name="kpi[{{ $kpi->id }}][actual_value]" class="form-control" value="{{ old('kpi.'.$kpi->id.'.actual_value', $result->actual_value ?? '') }}"></td>
                                            <td><input type="number" step="0.01" name="kpi[{{ $kpi->id }}][score]" class="form-control" value="{{ old('kpi.'.$kpi->id.'.score', $result->score ?? '') }}"></td>
                                            <td><input type="text" name="kpi[{{ $kpi->id }}][comment]" class="form-control" value="{{ old('kpi.'.$kpi->id.'.comment', $result->comment ?? '') }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('performance.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
