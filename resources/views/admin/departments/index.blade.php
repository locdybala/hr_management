@extends('layouts.nav')

@section('title', 'Quản lý phòng ban')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">



                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2>Quản lý phòng ban</h2>
                        <a href="{{ route('departments.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Thêm phòng ban
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form method="GET" action="{{ route('departments.index') }}"
                            class="mb-3 row g-2 align-items-center">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Tìm kiếm tên phòng ban..." value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Tìm
                                    kiếm</button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên phòng ban</th>
                                        <th>Trưởng phòng</th>
                                        <th>Ngày thành lập</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departments as $key => $department)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->manager ? $department->manager->name : 'Chưa có' }}</td>
                                            <td>{{ $department->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $department->status ? 'success' : 'danger' }}">
                                                    {{ $department->status ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('departments.edit', $department) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('departments.destroy', $department) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                {{ $departments->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
