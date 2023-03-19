<?php

namespace App\Models\Questions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class MultipleChoiceQuestion extends Model
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
