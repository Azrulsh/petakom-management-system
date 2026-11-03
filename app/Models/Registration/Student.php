<?php

namespace App\Models\Registration;

use App\Models\User;
use App\Models\Election\Candidate;
use App\Models\Election\Committee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        'full_name',
        'email',
        'phone',
        'programme',
        'year_of_study',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function committee()
    {
        return $this->hasMany(Committee::class);
    }

    // Student can participate in multiple elections
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
    
}