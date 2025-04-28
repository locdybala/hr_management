<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kpi_results';

    protected $fillable = [
        'performance_review_id',
        'kpi_id',
        'target_value',
        'actual_value',
        'score',
        'comment',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'actual_value' => 'decimal:2',
        'score' => 'decimal:2',
    ];

    // Relationships
    public function performanceReview()
    {
        return $this->belongsTo(PerformanceReview::class, 'performance_review_id');
    }

    public function kpi()
    {
        return $this->belongsTo(Kpi::class);
    }

    // Methods
    public function calculateScore()
    {
        if ($this->target_value == 0) {
            return 0;
        }

        return ($this->actual_value / $this->target_value) * 100;
    }
}
