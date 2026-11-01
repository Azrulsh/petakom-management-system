<?php

namespace App\Models\Registration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Registration\Student;

class Committee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'position',
        'academic_session',
        'appointed_at',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
