<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Intro;
use App\Models\Questions\WordGame;
use App\Models\Questions\MultipleChoiceQuestion;
use App\Models\Questions\Puzzle;
use App\Models\Questions\Project;
use App\User;
use App\Models\Group;
use App\Models\ProjectSubmit;
use App\Models\Tag;
use App\Models\Notification;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;

class Exam extends Model
{
    use SoftDeletes, HasUpsertQueries;


    protected $appends = ['have_preq_exam'];
    public function resolveRouteBinding($value, $field = null)
    {
        $value2 = $value;
        /*if ($value == 4015) {
            $value2 = 1;
        } elseif ($value == 4040) {
            $value2 = 2;
        } elseif ($value == 4041) {
            $value2 = 3;
        } elseif ($value == 4042) {
            $value2 = 4;
        } elseif ($value == 4043) {
            $value2 = 5;
        }*/
        return $this->where('id', $value2)->firstOrFail();
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function Intro() {
        return $this->hasOne(Intro::class);
    }

    public function MultipleChoiceQuestion() {
        return $this->hasMany(MultipleChoiceQuestion::class);
    }

    public function WordGame() {
        return $this->hasMany(WordGame::class);
    }

    public function Puzzle() {
        return $this->hasMany(Puzzle::class);
    }

    public function Project() {
        return $this->hasMany(Project::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function students() {
        return $this->belongsToMany(User::class, 'exam_user', 'exam_id', 'student_id')->as('students')->withPivot('percentage', 'questions', 'attempt', 'cert_serial')->withTimestamps();
    }

    public function students_shallow() {
        return $this->belongsToMany(User::class, 'exam_user', 'exam_id', 'student_id')->as('students')->withPivot('percentage', 'attempt')->withTimestamps();
    }

    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable')->withTimestamps();
    }

    public function project_submits() {
        return $this->hasMany(ProjectSubmit::class);
    }

    public function projectSubmits() {
        return $this->project_submits();
    }

    public function coupons() {
        return $this->hasMany(Coupon::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /**
     * Get all of the exam's news.
     */
    public function news()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function getHavePreqExamAttribute()
    {
        $exam_requirement = json_decode($this->preq, true);

        if ($exam_requirement['type'] == 1){
            return $exam_requirement['value'];
        }
    }

    public function solved() {
        return $this->belongsToMany(User::class, 'exam_user', 'exam_id',
            'student_id')->as('analysis')->withPivot('percentage', 'questions', 'attempt',
            'cert_serial')->withTimestamps();
    }
}
