@extends('layouts.nav')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    $employee = Auth::user()->employee;
@endphp
<div class="container mt-4">
    <h2 class="mb-4">CHỈNH SỬA THÔNG TIN CÁ NHÂN</h2>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('employee.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="{{ $employee->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="img-thumbnail mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                        <div class="mt-2">
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <strong>Mã nhân viên:</strong> {{ $employee->employee_code }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Tên nhân viên:</strong> {{ $employee->full_name }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Tình trạng công tác:</strong> {{ $employee->status }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 mb-2">
                                <strong>Mã đơn vị:</strong> {{ $employee->department->code ?? 'N/A' }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="email"><strong>Email làm việc:</strong></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email" value="{{ old('email', $employee->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Ngày vào làm:</strong> {{ $employee->join_date ? $employee->join_date->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>Thông tin làm việc</strong>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 mb-2">
                        <strong>Phòng ban:</strong> {{ $employee->department->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Chức danh:</strong> {{ $employee->position->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Loại nhân viên:</strong> {{ $employee->type ?? 'N/A' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 mb-2">
                        <strong>Nơi làm việc:</strong> {{ $employee->workplace ?? 'N/A' }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Ngày vào đơn vị:</strong> {{ $employee->join_date ? $employee->join_date->format('d/m/Y') : 'N/A' }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Ngày chính thức:</strong> {{ $employee->official_date ? $employee->official_date->format('d/m/Y') : 'N/A' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="phone"><strong>Số điện thoại:</strong></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Thâm niên:</strong> {{ $employee->seniority ?? 'N/A' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 mb-2">
                        <strong>Mã chấm công:</strong> {{ $employee->attendance_code ?? 'N/A' }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Loại hợp đồng:</strong> {{ $employee->contract_type ?? 'N/A' }}
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Trạng thái:</strong> {{ $employee->status }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address"><strong>Địa chỉ:</strong></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" value="{{ old('address', $employee->address) }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('employee.dashboard') }}" class="btn btn-secondary me-2">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection 