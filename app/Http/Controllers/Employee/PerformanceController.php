<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Performance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $year = $request->input('year', now()->year);
        $quarter = $request->input('quarter', ceil(now()->month / 3));

        $performances = Performance::where('employee_id', $employee->id)
            ->whereYear('review_date', $year)
            ->where('quarter', $quarter)
            ->orderBy('review_date', 'desc')
            ->paginate(10);

        return view('employee.performance.index', compact('performances', 'year', 'quarter', 'employee'));
    }
}
