@extends('layouts.nav')

@section('title', 'Dashboard Quản trị')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Dashboard</h2>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-people"></i> Tổng số nhân viên</h5>
                            <p class="card-text display-4">{{ App\Models\Employee::count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-building"></i> Phòng ban</h5>
                            <p class="card-text display-4">{{ App\Models\Department::count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-person-badge"></i> Chức vụ</h5>
                            <p class="card-text display-4">{{ App\Models\Position::count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-person-check"></i> Đang làm việc</h5>
                            <p class="card-text display-4">{{ App\Models\Employee::where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
