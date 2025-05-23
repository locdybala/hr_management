<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $employee_id = $request->input('employee_id', '');

        $employees = Employee::orderBy('first_name')->get();
        $salaryList = [];

        // Tính số ngày làm việc chuẩn trong tháng (thứ 2 - thứ 6)
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $workDays = 0;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekday()) {
                $workDays++;
            }
        }

        $query = Employee::query();
        if ($employee_id) {
            $query->where('id', $employee_id);
        }
        $filteredEmployees = $query->orderBy('first_name')->get();

        foreach ($filteredEmployees as $employee) {
            $attendanceCount = Attendance::where('employee_id', $employee->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->whereIn('status', ['present', 'late', 'early_leave'])
                ->whereRaw('WEEKDAY(date) < 5')
                ->distinct('date')
                ->count('date');
            $salaryPerDay = $employee->salary && $workDays > 0 ? $employee->salary / $workDays : 0;
            $totalSalary = round($salaryPerDay * $attendanceCount, 0);

            $salaryList[] = [
                'employee' => $employee,
                'work_days' => $workDays,
                'attendance_days' => $attendanceCount,
                'salary_per_day' => $salaryPerDay,
                'total_salary' => $totalSalary,
                'month' => $month,
                'year' => $year,
            ];
        }

        return view('admin.salary.index', compact('salaryList', 'month', 'year', 'employees', 'employee_id'));
    }
}
