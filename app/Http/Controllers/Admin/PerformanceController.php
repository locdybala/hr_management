<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Kpi;
use App\Models\KpiResult;
use App\Models\PerformanceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerformanceController extends Controller
{
    // 1. Danh sách phiếu đánh giá KPI
    public function index(Request $request)
    {
        $query = PerformanceReview::with(['employee', 'reviewer'])
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'desc');
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('quarter')) {
            $query->where('quarter', $request->quarter);
        }
        $reviews = $query->paginate(15);
        $employees = Employee::all();
        $years = range(date('Y'), date('Y') - 5);
        $quarters = [1, 2, 3, 4];
        return view('admin.performance.index', compact('reviews', 'employees', 'years', 'quarters'));
    }

    // 2. Form thêm mới phiếu đánh giá KPI
    public function create()
    {
        $employees = Employee::all();
        $kpis = Kpi::where('is_active', true)->get();
        $years = range(date('Y'), date('Y') - 5);
        $quarters = [1, 2, 3, 4];
        return view('admin.performance.create', compact('employees', 'kpis', 'years', 'quarters'));
    }

    // 2. Lưu phiếu đánh giá KPI mới
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'year' => 'required|integer|min:2000|max:2100',
            'quarter' => 'required|integer|in:1,2,3,4',
            'overall_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,submitted,approved,rejected',
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
                'employee_id' => $request->employee_id,
                'reviewer_id' => auth()->id(),
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
            return redirect()->route('performance.index')
                ->with('success', 'Thêm phiếu đánh giá KPI thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    // 3. Form sửa phiếu đánh giá KPI
    public function edit($id)
    {
        $review = PerformanceReview::with('kpiResults')->findOrFail($id);
        $employees = Employee::all();
        $kpis = Kpi::where('is_active', true)->get();
        $years = range(date('Y'), date('Y') - 5);
        $quarters = [1, 2, 3, 4];
        $kpiResults = $review->kpiResults->keyBy('kpi_id');
        return view('admin.performance.edit', compact('review', 'employees', 'kpis', 'years', 'quarters', 'kpiResults'));
    }

    // 3. Lưu cập nhật phiếu đánh giá KPI
    public function update(Request $request, $id)
    {
        $review = PerformanceReview::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'year' => 'required|integer|min:2000|max:2100',
            'quarter' => 'required|integer|in:1,2,3,4',
            'overall_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:draft,submitted,approved,rejected',
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
                'employee_id' => $request->employee_id,
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
            return redirect()->route('performance.index')
                ->with('success', 'Cập nhật phiếu đánh giá KPI thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    // 4. Xem chi tiết phiếu đánh giá KPI
    public function show($id)
    {
        $review = PerformanceReview::with(['employee', 'reviewer', 'kpiResults.kpi'])->findOrFail($id);
        return view('admin.performance.show', compact('review'));
    }
}
