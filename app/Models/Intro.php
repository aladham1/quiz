<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Exam;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;
class Intro extends Model
{
    use SoftDeletes, HasUpsertQueries;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function exam() {
        return $this->belongsTo(Exam::class);
    }
}
