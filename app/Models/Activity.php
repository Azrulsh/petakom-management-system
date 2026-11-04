<?php

namespace App\Models;

use App\Models\User;
use App\Models\Proposal\Proposal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'proposal_id',
        'title',
        'description',
        'activity_category',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'venue',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Activity Creator
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Related Approved Proposal
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
