<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::orderBy('first_name')->get();
        $query = Attendance::with('employee');

        // Lọc theo ngày (mặc định là hôm nay)
        $date = $request->input('date', Carbon::today()->toDateString());
        $query->whereDate('date', $date);

        // Lọc theo nhân viên
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Lọc theo tháng/năm
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15);

        return view('admin.attendance.index', compact('attendances', 'employees', 'date'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('admin.attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'check_in_location' => 'nullable|string|max:255',
            'check_out_location' => 'nullable|string|max:255',
            'status' => 'required|in:present,late,early_leave,absent',
            'note' => 'nullable|string',
        ]);
        Attendance::create($validated);
        return redirect()->route('attendance.index')->with('success', 'Thêm chấm công thành công!');
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $employees = Employee::orderBy('first_name')->get();
        return view('admin.attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'check_in_location' => 'nullable|string|max:255',
            'check_out_location' => 'nullable|string|max:255',
            'status' => 'required|in:present,late,early_leave,absent',
            'note' => 'nullable|string',
        ]);
        $attendance->update($validated);
        return redirect()->route('attendance.index')->with('success', 'Cập nhật chấm công thành công!');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Xóa chấm công thành công!');
    }
}
