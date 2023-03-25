<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ProjectSubmit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectSubmitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Exam $exam)
    {
        if (
            (url()->previous() != route('exams.intro', ['exam' => $exam->id]) && url()->previous() != route('exams.intro', ['exam' => $exam->id])) &&
            (url()->previous() != route('exams.mark', ['exam' => $exam->id]) && url()->previous() != route('guest.exams.mark', ['exam' => $exam->id])) &&
            url()->previous() != route('profile')
           ) {
            return Auth::check() ? redirect(route('exams.intro', ['exam' => $exam->id])) : redirect(route('exams.intro', ['exam' => $exam->id]));
        }
        if ( Auth::check() ) {
            return view('project.submit', ['project' => $exam->Project()->first(), 'exam' => $exam]);

        } else {
            $six_months = 6 * 43800;
            $guest_ticket = Str::random(12);
            if ( $request->cookie('guest_ticket', false) ) {
                $guest_ticket = $request->cookie('guest_ticket');
            }

            return response()->view('guest.project.submit', ['project' => $exam->Project()->first(), 'exam' => $exam])->cookie('guest_ticket', $guest_ticket, $six_months);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Exam $exam)
    {
        $files = $request->allFiles();
        //dd(phpinfo());
        $data = [];
        $data['description'] = $request->input('submission_text');
        foreach ($files as $key => $file) {
            $ext = $request->file($key)->guessExtension();
            $path = $request->file($key)->storePubliclyAs('Project Submits', Str::random(12) . '.' . $ext);
            $data[$key] = $path;
        }
        $user_id = Auth::user()->id ??
                   User::where('email', $request->cookie('guest_ticket'))->first()->id ??
                   User::create(['name' => 'guest_tickettemp_user', 'email' => $request->cookie('guest_ticket'), 'password' => Hash::make(Str::random())])->id;
        $data['student_id'] = $user_id;
        $count = $exam->project_submits()->where('student_id', $user_id)->count();
        $count++;
        $data['pending'] = true;
        $data['attempt'] = $count;
        $exam->project_submits()->create($data);

        return response(1);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectSubmit  $projectSubmit
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam, ProjectSubmit $projectSubmit)
    {
        $projectSubmit->load(['student', 'exam']);
        return view('project.student_submission_review', ['project' => $projectSubmit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectSubmit  $projectSubmit
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam, ProjectSubmit $projectSubmit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectSubmit  $projectSubmit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam, ProjectSubmit $projectSubmit)
    {
        $request->validate([
            'remark' => 'boolean',
            'remark_notes' => 'sometimes|string',
        ]);
        $projectSubmit->pending = false;
        $projectSubmit->remark = $request->input('remark');
        $projectSubmit->remark_notes = $request->input('remark_notes');
        $projectSubmit->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectSubmit  $projectSubmit
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectSubmit $projectSubmit)
    {
        //
    }

    public function showComment(ProjectSubmit $projectSubmit)
    {
        return $projectSubmit->remark_notes;
    }
}
