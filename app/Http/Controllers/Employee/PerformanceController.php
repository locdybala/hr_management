<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PerformanceReview;
use App\Models\KpiResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $query = PerformanceReview::with(['reviewer', 'kpiResults.kpi'])
            ->where('employee_id', $employee->id)
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'desc');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('quarter')) {
            $query->where('quarter', $request->quarter);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->paginate(10);
        $years = range(date('Y'), date('Y') - 5);
        $quarters = [1, 2, 3, 4];
        $statuses = ['draft', 'submitted', 'approved', 'rejected'];

        // Lấy giá trị year và quarter từ request hoặc sử dụng giá trị mặc định
        $year = $request->filled('year') ? $request->year : date('Y');
        $quarter = $request->filled('quarter') ? $request->quarter : ceil(date('n') / 3);

        return view('employee.performance.index', compact(
            'reviews', 
            'years', 
            'quarters', 
            'statuses', 
            'employee',
            'year',
            'quarter'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $kpis = Kpi::where('is_active', true)->get();
        $years = range(date('Y'), date('Y') - 5);
        $quarters = [1, 2, 3, 4];
        return view('employee.performance.create', compact('employee', 'kpis', 'years', 'quarters'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:2100',
            'quarter' => 'required|integer|in:1,2,3,4',
            'overall_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,submitted',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'improvements' => 'nullable|string',
            'kpi_results' => 'required|array',
            'kpi_results.*.kpi_id' => 'required|exists:kpis,id',
            'kpi_results.*.target_value' => 'required|numeric',
            'kpi_results.*.actual_value' => 'required|numeric',
            'kpi_results.*.score' => 'required|numeric|min:0|max:100',
            'kpi_results.*.comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $review = PerformanceReview::create([
                'employee_id' => $employee->id,
                'reviewer_id' => $user->id,
                'year' => $request->year,
                'quarter' => $request->quarter,
                'overall_score' => $request->overall_score,
                'status' => $request->status,
                'strengths' => $request->strengths,
                'weaknesses' => $request->weaknesses,
                'improvements' => $request->improvements,
            ]);

            foreach ($request->kpi_results as $result) {
                KpiResult::create([
                    'performance_review_id' => $review->id,
                    'kpi_id' => $result['kpi_id'],
                    'target_value' => $result['target_value'],
                    'actual_value' => $result['actual_value'],
                    'score' => $result['score'],
                    'comment' => $result['comment'],
                ]);
            }

            DB::commit();
            return redirect()->route('employee.performance.index')
                ->with('success', 'Thêm phiếu đánh giá KPI thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $review = PerformanceReview::with('kpiResults')
            ->where('employee_id', $employee->id)
            ->findOrFail($id);

        if ($review->status === 'approved' || $review->status === 'rejected') {
            return back()->with('error', 'Không thể chỉnh sửa phiếu đánh giá đã được duyệt hoặc từ chối!');
        }

        $kpis = Kpi::where('is_active', true)->get();
        $years = range(date('Y'), date('Y') - 5);
        $quarters = [1, 2, 3, 4];
        $kpiResults = $review->kpiResults->keyBy('kpi_id');
        return view('employee.performance.edit', compact('review', 'employee', 'kpis', 'years', 'quarters', 'kpiResults'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $review = PerformanceReview::where('employee_id', $employee->id)
            ->findOrFail($id);

        if ($review->status === 'approved' || $review->status === 'rejected') {
            return back()->with('error', 'Không thể chỉnh sửa phiếu đánh giá đã được duyệt hoặc từ chối!');
        }
        
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|min:2000|max:2100',
            'quarter' => 'required|integer|in:1,2,3,4',
            'overall_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,submitted',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'improvements' => 'nullable|string',
            'kpi_results' => 'required|array',
            'kpi_results.*.kpi_id' => 'required|exists:kpis,id',
            'kpi_results.*.target_value' => 'required|numeric',
            'kpi_results.*.actual_value' => 'required|numeric',
            'kpi_results.*.score' => 'required|numeric|min:0|max:100',
            'kpi_results.*.comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $review->update([
                'year' => $request->year,
                'quarter' => $request->quarter,
                'overall_score' => $request->overall_score,
                'status' => $request->status,
                'strengths' => $request->strengths,
                'weaknesses' => $request->weaknesses,
                'improvements' => $request->improvements,
            ]);

            $review->kpiResults()->delete();
            foreach ($request->kpi_results as $result) {
                KpiResult::create([
                    'performance_review_id' => $review->id,
                    'kpi_id' => $result['kpi_id'],
                    'target_value' => $result['target_value'],
                    'actual_value' => $result['actual_value'],
                    'score' => $result['score'],
                    'comment' => $result['comment'],
                ]);
            }

            DB::commit();
            return redirect()->route('employee.performance.index')
                ->with('success', 'Cập nhật phiếu đánh giá KPI thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Không tìm thấy thông tin nhân viên!');
        }

        $review = PerformanceReview::with(['reviewer', 'kpiResults.kpi'])
            ->where('employee_id', $employee->id)
            ->findOrFail($id);

        return view('employee.performance.show', compact('review', 'employee'));
    }
}
