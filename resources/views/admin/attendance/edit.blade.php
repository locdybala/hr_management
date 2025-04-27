@extends('layouts.nav')

@section('title', 'Chỉnh sửa chấm công')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Chỉnh sửa chấm công</h3>
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
                    <form action="{{ route('attendance.update', $attendance) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Nhân viên <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Ngày <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in" class="form-label">Giờ vào</label>
                                <input type="text" name="check_in" id="check_in" class="form-control @error('check_in') is-invalid @enderror" value="{{ old('check_in', $attendance->check_in) }}" placeholder="VD: 08:00, 13:00, 14:30">
                                @error('check_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="check_out" class="form-label">Giờ ra</label>
                                <input type="text" name="check_out" id="check_out" class="form-control @error('check_out') is-invalid @enderror" value="{{ old('check_out', $attendance->check_out) }}" placeholder="VD: 17:00, 18:00, 20:30">
                                @error('check_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_location" class="form-label">Vị trí vào</label>
                                <input type="text" name="check_in_location" id="check_in_location" class="form-control @error('check_in_location') is-invalid @enderror" value="{{ old('check_in_location', $attendance->check_in_location) }}">
                                @error('check_in_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="check_out_location" class="form-label">Vị trí ra</label>
                                <input type="text" name="check_out_location" id="check_out_location" class="form-control @error('check_out_location') is-invalid @enderror" value="{{ old('check_out_location', $attendance->check_out_location) }}">
                                @error('check_out_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Có mặt</option>
                                <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Đi muộn</option>
                                <option value="early_leave" {{ old('status', $attendance->status) == 'early_leave' ? 'selected' : '' }}>Về sớm</option>
                                <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Vắng mặt</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="2">{{ old('note', $attendance->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
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
