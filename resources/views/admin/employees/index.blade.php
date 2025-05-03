@extends('layouts.nav')

@section('title', 'Danh sách nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Danh sách nhân viên</h3>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Thêm nhân viên
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="GET" action="" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, mã NV, email..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Tìm kiếm</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Mã NV</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                    <th>Ảnh</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $employee)
                                    <tr>
                                        <td>{{ $employees->firstItem() + $index }}</td>
                                        <td>{{ $employee->employee_code }}</td>
                                        <td>{{ $employee->full_name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>
                                            @if($employee->status == 'active')
                                                <span class="badge bg-success">Đang làm</span>
                                            @elseif($employee->status == 'inactive')
                                                <span class="badge bg-danger">Nghỉ việc</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($employee->avatar)
                                                <img src="{{ asset('storage/' . $employee->avatar) }}" alt="avatar" width="40" height="40" class="rounded-circle">
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá nhân viên này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xoá</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Không có nhân viên nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $employees->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
