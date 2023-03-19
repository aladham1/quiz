<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class Notification extends Model
{
    use SoftDeletes, HasUpsertQueries;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the owning notifiable model.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }
}
