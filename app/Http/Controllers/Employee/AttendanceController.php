<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->paginate(31);
        return view('employee.attendance.index', compact('attendances', 'month', 'year'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $today = Carbon::today();
        $now = Carbon::now();
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => $today
            ],
            [
                'check_in' => $now->format('H:i'),
                'status' => 'present',
            ]
        );
        if (!$attendance->wasRecentlyCreated) {
            return back()->with('error', 'Bạn đã chấm công hôm nay!');
        }
        return back()->with('success', 'Chấm công thành công!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $today = Carbon::today();
        $now = Carbon::now();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->first();
        if (!$attendance) {
            return back()->with('error', 'Bạn chưa chấm công vào!');
        }
        if ($attendance->check_out) {
            return back()->with('error', 'Bạn đã chấm công ra hôm nay!');
        }
        $attendance->update(['check_out' => $now->format('H:i')]);
        return back()->with('success', 'Chấm công ra thành công!');
    }
}
