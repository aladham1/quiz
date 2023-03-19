<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Models\Exam;
use App\Models\Tag;
use App\Models\Notification;
use Illuminate\Notifications\Notifiable;
use Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;
class Group extends Model
{
    use SoftDeletes, Notifiable, HasUpsertQueries;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function owner() 
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function followers()
    {
        return $this->morphedByMany(User::class, 'groupable')->withTimestamps();
    }

    public function exams()
    {
        return $this->morphedByMany(Exam::class, 'groupable')->withTimestamps();
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    public function groupCategories()
    {
        return $this->morphedByMany(GroupCategory::class, 'groupable')->withTimestamps();
    }

    /**
     * Get all of the group's news.
     */
    public function news()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
