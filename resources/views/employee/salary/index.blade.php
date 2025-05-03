@extends('layouts.nav')

@section('title', 'Thông tin lương')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Thông tin lương</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="GET" action="" class="row g-2 align-items-end mb-3">
                        <div class="col-md-3">
                            <label for="month" class="form-label">Tháng</label>
                            <select name="month" id="month" class="form-select">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="year" class="form-label">Năm</label>
                            <select name="year" id="year" class="form-select">
                                @for($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Lọc</button>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Thông tin cơ bản</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Họ tên:</th>
                                            <td>{{ $salaryInfo['employee']->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mã nhân viên:</th>
                                            <td>{{ $salaryInfo['employee']->employee_code }}</td>
                                        </tr>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Thông tin lương tháng {{ $month }}/{{ $year }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Lương cơ bản:</th>
                                            <td>{{ number_format($salaryInfo['employee']->salary) }} đ</td>
                                        </tr>
                                        <tr>
                                            <th>Số ngày làm việc chuẩn:</th>
                                            <td>{{ $salaryInfo['work_days'] }} ngày</td>
                                        </tr>
                                        <tr>
                                            <th>Số ngày đi làm thực tế:</th>
                                            <td>{{ $salaryInfo['attendance_days'] }} ngày</td>
                                        </tr>
                                        <tr>
                                            <th>Lương một ngày:</th>
                                            <td>{{ number_format($salaryInfo['salary_per_day']) }} đ</td>
                                        </tr>
                                        <tr class="table-success">
                                            <th><strong>Tổng lương thực nhận:</strong></th>
                                            <td><strong>{{ number_format($salaryInfo['total_salary']) }} đ</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
