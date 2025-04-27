@extends('layouts.nav')

@section('title', 'Thêm KPI')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Thêm KPI mới</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('kpis.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Tên KPI <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="unit" class="form-label">Đơn vị <span class="text-danger">*</span></label>
                        <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit') }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="target_value" class="form-label">Giá trị mục tiêu <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="target_value" id="target_value" class="form-control" value="{{ old('target_value') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="weight" class="form-label">Trọng số <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="weight" id="weight" class="form-control" value="{{ old('weight') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="is_active" class="form-label">Trạng thái</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success">Lưu KPI</button>
                    <a href="{{ route('kpis.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
