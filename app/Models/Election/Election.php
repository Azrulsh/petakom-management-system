<?php

namespace App\Models\Election;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'academic_session',
        'description',
        'nomination_start',
        'nomination_end',
        'status',
        'created_by',
    ];

    protected $casts = [
        'nomination_start' => 'datetime',
        'nomination_end' => 'datetime',
    ];

    // User who created the election
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Election has many candidates
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

}
