<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $departments = $query->with('manager')->orderBy('id', 'desc')->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $managers = Employee::all();
        return view('admin.departments.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Phòng ban đã được thêm thành công.');
    }

    public function edit(Department $department)
    {
        $managers = Employee::all();
        return view('admin.departments.edit', compact('department', 'managers'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')
            ->with('success', 'Phòng ban đã được cập nhật thành công.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Phòng ban đã được xóa thành công.');
    }
}
