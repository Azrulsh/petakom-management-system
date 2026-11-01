<?php

namespace App\Models\Registration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'appointed_at' => 'date',
    ];

    // Committee belongs to a Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
