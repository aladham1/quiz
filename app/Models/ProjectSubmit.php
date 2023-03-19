<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Exam;
use App\User;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class ProjectSubmit extends Model
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

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}
