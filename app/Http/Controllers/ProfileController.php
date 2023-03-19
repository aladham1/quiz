<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $relations = new Collection;
        $id = Auth::id();
        if ($id) {
            $relations = [
                'exams' => function ( $query) { //exams:id,title,icon,user_id
                    $query->with([
                        'project_submits'=> function ( $query) {
                            $query->with('student')->latest();
                        },
                        'coupons'
                    ])->latest();
                },
                'groups' => function ( $query) {
                    $query->withCount(['followers', 'exams'])->latest();
                },
                'solved' => function ( $query) use($id) { //groups:id,image,title,private,password,user_id
                    $query->with(['owner', 'project_submits' => function ( $query) use($id) {
                        $query->where('student_id', '=', $id)->latest();
                    },])->withCount(['MultipleChoiceQuestion','WordGame','Puzzle','Project'])->latest();
                },
                'project_submits' => function ( $query) {
                    $query->with('exam')->latest();
                },
            ];
        } else {
            $relations = [
                'exams' => function ( $query) { //exams:id,title,icon,user_id
                    $query->with([
                        'project_submits'=> function ( $query) {
                            $query->with('student')->latest();
                        },
                        'coupons'
                    ])->latest();
                },
                'groups' => function ( $query) {
                    $query->withCount(['followers', 'exams'])->latest();
                },
            ];
        }

        $user = User::find($id)->load($relations)->loadCount('following');
        $user_avatar = isset($user->avatar) ? url(Storage::url($user->avatar)) : url('images/user.svg');
        $exams = $user->exams;
        $groups = $user->groups;
        $project_submits = $exams[0]->project_submits ?? new Collection;
        $user_submitted_projects = $user->project_submits;
        $solved_exams = $user->solved;
        $default_grp_img = url('images/placeholder.jpeg');
        return view('dashboard.profile', ['id' => $id, 'user' => $user, 'user_avatar' => $user_avatar,
            'exams' => $exams, 'groups' => $groups, 'project_submits' => $project_submits,
            'user_submitted_projects' => $user_submitted_projects, 'solved_exams' => $solved_exams,
        'default_grp_img' => $default_grp_img]);
    }
}
