<?php

namespace App;

use App\Models\Coupon;
use App\Models\CouponList;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Exam;
use App\Models\Group;
use App\Models\ProjectSubmit;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable, HasUpsertQueries;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar','socail_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function exams() {
        return $this->hasMany(Exam::class);
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }

    public function project_submits() {
        return $this->hasMany(ProjectSubmit::class, 'student_id');
    }

    public function solved() {
        return $this->belongsToMany(Exam::class, 'exam_user', 'student_id', 'exam_id')->as('analysis')->withPivot('percentage', 'questions', 'attempt', 'cert_serial')->withTimestamps();
    }

    public function solved_percentage() {
        return $this->belongsToMany(Exam::class, 'exam_user', 'student_id', 'exam_id')->as('analysis')->withPivot('percentage', 'attempt')->withTimestamps();
    }

    public function following()
    {
        return $this->morphToMany(Group::class, 'groupable')->withTimestamps();
    }

    public function couponLists() {
        return $this->hasMany(CouponList::class);
    }

    public function couponListsCoupons() {
        return $this->hasManyThrough(Coupon::class, CouponList::class);
    }

    public function coupons() {
        return $this->hasMany(Coupon::class);
    }
}
