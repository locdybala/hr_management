<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $positions = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);
        Position::create($request->all());
        return redirect()->route('positions.index')->with('success', 'Thêm chức vụ thành công!');
    }

    public function show(Position $position)
    {
        return view('admin.positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        return view('admin.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);
        $position->update($request->all());
        return redirect()->route('positions.index')->with('success', 'Cập nhật chức vụ thành công!');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Xóa chức vụ thành công!');
    }
}
