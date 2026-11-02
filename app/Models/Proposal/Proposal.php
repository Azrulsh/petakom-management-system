<?php

namespace App\Models\Proposal;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    // Proposal Owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Activity Reports
    public function activityReports()
    {
        return $this->hasMany(ActivityReport::class);
    }

    // Approval History
    public function reviews()
    {
        return $this->hasMany(ProposalReview::class)
            ->latest('reviewed_at');
    }

    // Latest Reviewer
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
