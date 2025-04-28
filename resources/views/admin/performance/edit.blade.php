@extends('layouts.nav')

@section('title', 'Chỉnh sửa đánh giá KPI')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Chỉnh sửa đánh giá KPI</h3>
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

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('performance.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="employee_id" class="form-label">Nhân viên <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            <option value="">Chọn nhân viên</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $review->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }} ({{ $employee->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="year" class="form-label">Năm <span class="text-danger">*</span></label>
                        <select name="year" id="year" class="form-select" required>
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ old('year', $review->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="quarter" class="form-label">Quý <span class="text-danger">*</span></label>
                        <select name="quarter" id="quarter" class="form-select" required>
                            @foreach($quarters as $q)
                                <option value="{{ $q }}" {{ old('quarter', $review->quarter) == $q ? 'selected' : '' }}>Quý {{ $q }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="overall_score" class="form-label">Điểm tổng <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="overall_score" id="overall_score" class="form-control" value="{{ old('overall_score', $review->overall_score) }}" required readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="draft" {{ old('status', $review->status) == 'draft' ? 'selected' : '' }}>Nháp</option>
                            <option value="submitted" {{ old('status', $review->status) == 'submitted' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="approved" {{ old('status', $review->status) == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="rejected" {{ old('status', $review->status) == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="strengths" class="form-label">Điểm mạnh</label>
                        <textarea name="strengths" id="strengths" class="form-control">{{ old('strengths', $review->strengths) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="weaknesses" class="form-label">Điểm yếu</label>
                        <textarea name="weaknesses" id="weaknesses" class="form-control">{{ old('weaknesses', $review->weaknesses) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="improvements" class="form-label">Đề xuất cải thiện</label>
                        <textarea name="improvements" id="improvements" class="form-control">{{ old('improvements', $review->improvements) }}</textarea>
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
                                @php
                                    $result = $kpiResults[$kpi->id] ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $kpi->name }}
                                        <input type="hidden" name="kpi_results[{{ $loop->index }}][kpi_id]" value="{{ $kpi->id }}">
                                    </td>
                                    <td>{{ $kpi->unit }}</td>
                                    <td>
                                        {{ $kpi->weight }}
                                        <input type="hidden" class="kpi-weight" value="{{ $kpi->weight }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="kpi_results[{{ $loop->index }}][target_value]" class="form-control target-value" value="{{ old('kpi_results.'.$loop->index.'.target_value', $result ? $result->target_value : $kpi->target_value) }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="kpi_results[{{ $loop->index }}][actual_value]" class="form-control actual-value" value="{{ old('kpi_results.'.$loop->index.'.actual_value', $result ? $result->actual_value : '') }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" max="100" name="kpi_results[{{ $loop->index }}][score]" class="form-control kpi-score" value="{{ old('kpi_results.'.$loop->index.'.score', $result ? $result->score : '') }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="kpi_results[{{ $loop->index }}][comment]" class="form-control" value="{{ old('kpi_results.'.$loop->index.'.comment', $result ? $result->comment : '') }}">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tính điểm KPI khi thay đổi giá trị thực tế
    document.querySelectorAll('.actual-value').forEach(function(input) {
        input.addEventListener('input', calculateScore);
    });

    // Tính điểm tổng khi thay đổi điểm KPI
    document.querySelectorAll('.kpi-score').forEach(function(input) {
        input.addEventListener('input', calculateOverallScore);
    });

    function calculateScore(e) {
        const row = e.target.closest('tr');
        const targetValue = parseFloat(row.querySelector('.target-value').value) || 0;
        const actualValue = parseFloat(e.target.value) || 0;
        
        let score = 0;
        if (targetValue > 0) {
            score = (actualValue / targetValue) * 100;
        }
        
        row.querySelector('.kpi-score').value = Math.min(100, Math.max(0, score.toFixed(2)));
        calculateOverallScore();
    }

    function calculateOverallScore() {
        let totalScore = 0;
        let totalWeight = 0;
        
        document.querySelectorAll('tbody tr').forEach(function(row) {
            const weight = parseFloat(row.querySelector('.kpi-weight').value) || 0;
            const score = parseFloat(row.querySelector('.kpi-score').value) || 0;
            
            totalScore += (weight * score);
            totalWeight += weight;
        });
        
        const overallScore = totalWeight > 0 ? (totalScore / totalWeight).toFixed(2) : 0;
        document.getElementById('overall_score').value = overallScore;
    }

    // Tính điểm tổng ban đầu
    calculateOverallScore();
});
</script>
@endpush
@endsection
