<?php

namespace App\Models\Proposal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'activity_category',
        'background',
        'objectives',
        'proposed_date',
        'end_date',
        'venue',
        'target_participants',
        'estimated_budget',
        'prepared_by',
        'status',
        'review_remarks',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'proposed_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'estimated_budget' => 'decimal:2',
    ];

    public function activityReports()
    {
        return $this->hasMany(ActivityReport::class);
    }
}
