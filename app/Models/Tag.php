<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Group;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class Tag extends Model
{
    use SoftDeletes, HasUpsertQueries;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public function groups() {
        return $this->morphedByMany(Group::class, 'taggable')->withTimestamps();
    }

    public function exams() {
        return $this->morphedByMany(Exam::class, 'taggable')->withTimestamps();
    }
}
