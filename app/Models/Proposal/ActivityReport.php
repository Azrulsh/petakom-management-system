<?php

namespace App\Models\Proposal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'activity_title',
        'activity_date',
        'venue',
        'actual_attendance',
        'actual_expenditure',
        'summary',
        'achievements',
        'challenges',
        'recommendations',
        'prepared_by',
        'status',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'actual_expenditure' => 'decimal:2',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
