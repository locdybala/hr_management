<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Performance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'year',
        'quarter',
        'overall_score',
        'strengths',
        'weaknesses',
        'improvements',
        'status',
        'reviewer_id',
    ];

    protected $casts = [
        'overall_score' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function kpiResults()
    {
        return $this->hasMany(KpiResult::class);
    }
}
