<?php

namespace App\Models\Proposal;

use App\Models\User;
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
        'created_by',
        'status',
        'submitted_at',
        'review_remarks',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'actual_expenditure' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Related Proposal
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * User who created the report
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who reviewed the report
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
