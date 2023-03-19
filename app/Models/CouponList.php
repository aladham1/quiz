<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Coupon;
use App\User;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class CouponList extends Model
{
    use HasUpsertQueries;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function coupons() {
        return $this->hasMany(Coupon::class);
    }
}
