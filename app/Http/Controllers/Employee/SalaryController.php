<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalaryController extends Controller
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

        // Tính số ngày làm việc chuẩn trong tháng (thứ 2 - thứ 6)
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $workDays = 0;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekday()) {
                $workDays++;
            }
        }

        // Đếm số ngày công thực tế (present, late, early_leave)
        $attendanceCount = \App\Models\Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('status', ['present', 'late', 'early_leave'])
            ->whereRaw('WEEKDAY(date) < 5') // chỉ tính thứ 2-6
            ->distinct('date')
            ->count('date');
        $salaryPerDay = $employee->salary && $workDays > 0 ? $employee->salary / $workDays : 0;
        $totalSalary = round($salaryPerDay * $attendanceCount, 0);

        $salaryData = [
            'work_days' => $workDays,
            'attendance_days' => $attendanceCount,
            'salary_per_day' => $salaryPerDay,
            'total_salary' => $totalSalary,
            'month' => $month,
            'year' => $year,
        ];

        return view('admin.salary.index', compact('salaryData', 'month', 'year', 'employee'));
    }
}
