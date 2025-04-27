<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KPI extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kpis';

    protected $fillable = [
        'name',
        'description',
        'unit',
        'target_value',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function results()
    {
        return $this->hasMany(KpiResult::class);
    }
}
