<?php

namespace App\Models;

use App\Models\Registration\Student;
use App\Models\Election\Committee;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Proposal\ProposalReview;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    
// ==========================================
// PETAKOM USER ROLES
// ==========================================

public const ROLE_ADMIN = 'admin';

public const ROLE_STUDENT = 'student';

public const ROLE_LECTURER = 'lecturer';

public const ROLE_COORDINATOR = 'coordinator';


// ==========================================
// GENERAL ROLE CHECK
// ==========================================

public function hasRole($roles): bool
{
    return in_array($this->role, (array) $roles, true);
}


// ==========================================
// SPECIFIC ROLE CHECK
// ==========================================

public function isAdmin(): bool
{
    return $this->hasRole(self::ROLE_ADMIN);
}

public function isStudent(): bool
{
    return $this->hasRole(self::ROLE_STUDENT);
}

public function isLecturer(): bool
{
    return $this->hasRole(self::ROLE_LECTURER);
}

public function isCoordinator(): bool
{
    return $this->hasRole(self::ROLE_COORDINATOR);
}


// ==========================================
// COMMITTEE MEMBERSHIP CHECK
// ==========================================

public function isCommittee(): bool
{
    return $this->isStudent()
        && $this->isActiveCommitteeMember();
}

    /**
     * User has one Student profile.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * User can have committee memberships
     * through their Student profile.
     */
    public function committees()
    {
        return $this->hasManyThrough(
            Committee::class,
            Student::class,
            'user_id',
            'student_id',
            'id',
            'id'
        );
    }

    /**
     * Check whether user is an active committee member.
     */
    public function isActiveCommitteeMember(): bool
    {
        return $this->committees()
            ->where('committees.status', 'active')
            ->exists();
    }

    // Proposal reviews performed by this user
    public function proposalReviews()
    {
        return $this->hasMany(
            ProposalReview::class,
            'reviewer_id'
        );
    }
    
}
