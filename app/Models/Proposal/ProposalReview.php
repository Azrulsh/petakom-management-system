<?php

namespace App\Models\Proposal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ProposalReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'decision',
        'remarks',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationship with Proposal
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    // Relationship with Reviewer
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
