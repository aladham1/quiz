<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CouponList;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class Coupon extends Model
{
    use HasUpsertQueries;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function couponList() {
        return $this->belongsTo(CouponList::class);
    }
}
