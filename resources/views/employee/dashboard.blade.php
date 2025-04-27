@extends('layouts.nav')

@section('title', 'Hồ sơ cá nhân')

@section('content')
@php
    $employee = Auth::user()->employee;
@endphp
<div class="container mt-4">
    <h2 class="mb-4">HỒ SƠ CÁ NHÂN</h2>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="{{ $employee->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="img-thumbnail mb-2" style="width: 120px; height: 120px; object-fit: cover;">
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
                            <strong>Email làm việc:</strong> {{ $employee->email }}
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
                    <strong>Email làm việc:</strong> {{ $employee->email }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Số điện thoại:</strong> {{ $employee->phone }}
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
        </div>
    </div>
</div>
@endsection
