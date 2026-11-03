<?php

namespace App\Models\Election;

use App\Models\Registration\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'student_id',
        'position',
        'manifesto',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Election that the candidate participates in
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // Student who registered as a candidate
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Admin or Coordinator who reviewed the application
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
