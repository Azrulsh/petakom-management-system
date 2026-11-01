<?php

namespace App\Models\Registration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'full_name',
        'email',
        'phone',
        'programme',
        'year_of_study',
        'status',
    ];

    public function committee()
    {
        return $this->hasMany(Committee::class);
    }
}
