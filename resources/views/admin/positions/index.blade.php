@extends('layouts.nav')

@section('title', 'Quản lý chức vụ')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Danh sách chức vụ</h3>
                        <a href="{{ route('positions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Thêm chức vụ
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('positions.index') }}" class="mb-3 row g-2 align-items-center">
                            <div class="col-auto">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Tìm kiếm tên chức vụ..." value="{{ request('search') }}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Tìm
                                    kiếm</button>
                            </div>
                        </form>
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Tên chức vụ</th>
                                        <th>Mô tả</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($positions as $key => $position)
                                        <tr>
                                            <td>{{ $positions->firstItem() + $key }}</td>
                                            <td>{{ $position->name }}</td>
                                            <td>{{ $position->description }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $position->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ $position->status == 'active' ? 'Hoạt động' : 'Ẩn' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('positions.edit', $position) }}"
                                                    class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('positions.destroy', $position) }}" method="POST"
                                                    class="d-inline">
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
                                            <td colspan="5" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $positions->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
