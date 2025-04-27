<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'year',
        'quarter',
        'overall_score',
        'strengths',
        'weaknesses',
        'improvements',
        'status',
    ];

    protected $casts = [
        'year' => 'integer',
        'quarter' => 'integer',
        'overall_score' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function kpiResults()
    {
        return $this->hasMany(KpiResult::class, 'performance_review_id');
    }
}
