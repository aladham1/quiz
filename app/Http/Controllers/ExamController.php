<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExam;
use App\Models\Exam;
use App\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\DataTables\ResultsDataTable;

class ExamController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $exam = $request->route('exam');

            if ($request->route()->named('exams.intro')) {
                if (($exam->login_required && (!auth()->check() && $request->cookie('guest_fields', false))) || (!$request->route()->named('exams.attend', ['exam' => $exam->id]) && (!auth()->check() && $request->cookie('guest_fields', false)))) {
                    if ($request->input('guest_fields', false) && $request->method() == 'POST') {
                        //dd($request->cookie('guest_fields'));

                        $response = $next($request);

                        $six_months = 6 * 43800;
                        $guest_fields = $request->input('guest_fields', false);
                        //special guest_naem cookie for guest_naem field. It will also be named specially in the guest login forms
                        return $response->cookie('guest_fields', json_encode($guest_fields), $six_months);

                    } elseif (isset($exam->login_fields)) {
                        return response()->view('dashboard.guest_login', ['exam' => $exam]);
                    } else {
                        return redirect(route('login'));
                    }
                }
            }

            $exam_requirement = json_decode($exam->preq, true);
            if (($exam_requirement['type'] == 2 && auth()->user()->stars < $exam_requirement['value'])) {
                return;
            }

            if ($exam_requirement['type'] == 1) {
                $user = User::where('id', auth()->id())->with(['solved_percentage' => function ($q) use ($exam_requirement) {
                    $q->where('exams.id', 7)->select('pass_percentage');
                }])->first();
                $exam_passed = $user->solved->toArray();
            }

            return $next($request);
        })->only(['attend']);
    }

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
    public function create()
    {
        $exams = Exam::where('user_id', \auth()->id())->select('id', 'title')->get();
        return view('exam.create-update', ['exams' => $exams]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->all();//validated();
        $validated['Exams'] = json_decode($validated['Exams'], true);
        //print_r($validated['Exams']['Exam1']);
        $this->map_files_to_columns($request, $validated);

        //dd($validated['Exams']);
        //Done Below (TODO: extract the exam model, insert it then insert relation through relation function in exam model named after related model)
        $exams = $validated['Exams'];
        foreach ($exams as $i => $exam) {
            $exam['Exam'] = array_map(array($this, 'json_array_vars'), $exam['Exam']);
            $exam['Exam']['user_id'] = Auth::id();
            $exam_model = Exam::create($exam['Exam']);
            //print_r($exam['Exam']);
            unset($exam['Exam']);
            if (isset($exam['Intro'])) {
                $exam['Intro'] = array_map(array($this, 'json_array_vars'), $exam['Intro']);
                //print_r($relation_data);
                //print_r($exam['Intro']);
                $exam_model->Intro()->create($exam['Intro']);
                unset($exam['Intro']);
            }

            foreach ($exam as $relation_name => $relation_data) {
                foreach ($relation_data as $k => $model) {
                    //print_r($relation_data[$k]);
                    $relation_data[$k] = array_map(array($this, 'json_array_vars'), $relation_data[$k]);
                }
                //print_r($relation_data);
                $exam_model->$relation_name()->createMany($relation_data);
            }
        }
        return response('Exam(s) Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam, ResultsDataTable $dataTable)
    {
        $users_query = User::whereHas('solved', function ($q) use ($exam) {
            $q->where('exams.id', $exam->id);
        });
        $all_users = $users_query->count();
        $guests = $users_query->where('email', 'like', 'guest_tickettemp_user_%')->count();
        $members = $all_users - $guests;
        $all_attempts = $exam->students()->count();
        $passed = $exam->students()->wherePivot('percentage', '>=', $exam->pass_percentage)->count();
        $failed = $all_attempts - $passed;
        $exam->load('groups.owner:id,name');
        return $dataTable->with('exam', $exam)->render('exam.info', ['exam' => $exam, 'registered_count' => $members, 'guests_count' => $guests, 'total_users_count' => $all_users, 'passed_count' => $passed, 'failed_count' => $failed, 'total_attempts_count' => $all_attempts]);
    }

    /**
     * Display the exam reward.
     *
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function showReward(Exam $exam)
    {
        $reward_type = $exam->reward_type;
        $reward_data = ['coupon_list' => $exam->coupon_list,
            'hardware_name' => $exam->hardware_name,
            'special_control_char' => $exam->special_control_char,
            "reward_message" => $exam->reward_message,
            'reward_video' => $exam->reward_video,
            'reward_image' => isset($exam->reward_message) ? Storage::url($exam->reward_message) : null,
            'cert_lang' => $exam->cert_lang,
            'sponser' => isset($exam->sponser) ? Storage::url($exam->sponser) : null,

        ];
        if ($reward_type == 4) {
            $reward_data['user_name'] = auth()->user()->name;
            $reward_data['exam_owner'] = $exam->owner->name;
            $reward_data['exam_title'] = $exam->title;
            $analysis_data = $exam->owner->solved;
            $reward_data['cert_id'] = $analysis_data->first()->analysis->pivot_cert_serial;
            $reward_data['creation_time'] = $analysis_data->first()->analysis->created_at->format('h:i:s A');
            $reward_data['creation_date'] = $analysis_data->first()->analysis->created_at->format('d-m-Y');
        }
        return response()->json([
            'reward_type' => $reward_type,
            'reward_data' => $reward_data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        $exams = Exam::where('user_id', \auth()->id())->select('id', 'title')->get();

        $questions = $this->get_available_question_types($exam);
        $questions_tmp = [];
        foreach ($questions as $key => $q) {
            $questions_tmp = array_merge($questions_tmp, [$q => $exam->$q()->get()->makeHidden(['created_at', 'updated_at', 'deleted_at'])->toArray()]);
        }

        $intro_items = $exam->Intro()->select('title', 'image', 'audio', 'video', 'paragraph', 'table', 'file', 'order_button')->first();
        $intro_items = $intro_items == null ? [] : $intro_items->toArray();
        $this->order_with_type($intro_items, $intro_items, null);
        $this->order_with_type($questions_tmp, $questions_tmp, null);

        $exam_copy = clone $exam;// paths without urls (original values in DB)

        $exam->icon = (isset($exam->icon) && Storage::exists($exam->icon)) ? Storage::url($exam->icon) : null;
        $exam->reward_image = (isset($exam->reward_image) && Storage::exists($exam->reward_image)) ? Storage::url($exam->reward_image) : null;
        $exam->sponser = (isset($exam->sponser) && Storage::exists($exam->sponser)) ? Storage::url($exam->sponser) : null;

        $intro_items_copy = $intro_items; // paths without urls (original values in DB)
        $questions_tmp_copy = $questions_tmp;// paths without urls (original values in DB)

        foreach ($intro_items as $key => $item) {
            if (Str::contains($key, ['image', 'audio', 'file'])) {
                $intro_items[$key] = Storage::url($intro_items[$key]);
            }
        }

        foreach ($questions_tmp as $key => $item) {
            foreach ($item as $column => $value) {
                if (Str::contains($column, ['image', 'audio', 'file'])) {
                    if (isset($item[$column])) {
                        $item[$column] = json_decode($item[$column], true) || $item[$column];
                        if (is_array($item[$column])) {
                            if (count($item[$column]) == 1) {
                                $questions_tmp[$key][$column] = Storage::url($item[$column][array_key_first($item[$column])]);
                            } else {
                                foreach ($item[$column] as $key2 => $image) {
                                    $questions_tmp[$key][$column][$key2] = Storage::url($item[$column][$key2]);
                                }
                            }

                        } else {
                            $questions_tmp[$key][$column] = Storage::url($item[$column]);
                        }
                    }
                }
            }
        }
        $view_data = ['exam' => $exam_copy, 'intro' => $intro_items_copy,
            'exams' => $exams, 'questions' => $questions_tmp_copy, 'data_copy_with_urls' => ['Exam' => $exam, 'Intro' => $intro_items, 'questions' => $questions_tmp]];

        return view('exam.create-update', $view_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $original_exam)
    {
        $validated = $request->all();//validated();
        $validated['Exams'] = json_decode($validated['Exams'], true);

        //print_r($validated['Exams']['Exam1']);
        $this->map_files_to_columns($request, $validated);
        $media_to_be_deleted = [];
        $empty_array = [];
        $exams = $validated['Exams'];

        foreach ($exams as $i => $exam) {
            $exam['Exam'] = array_map(array($this, 'json_array_vars'), $exam['Exam']);
            $exam['Exam']['user_id'] = Auth::id();
            //$exam_model = Exam::upsert(array_diff_key($exam['Exam'], ['created_at' => null, 'updated_at' => null, 'deleted_at' => null]), 'id', array_diff(array_keys($exam['Exam']), ['id', 'created_at', 'updated_at', 'deleted_at']));
            $exam_model = Exam::findOrFail($exam['Exam']['id']);

            foreach ($exam['Exam'] as $column => $value) {
                if ($column != "have_preq_exam") {
                    $exam_model->$column = $value;
                    if ($column == 'icon' || $column == 'reward_image' || $column == 'sponser') {
                        if ($exam_model->$column != $exam_model->getOriginal($column)) {
                            $media_to_be_deleted[] = $exam_model->getOriginal($column);
                        }
                    }
                }
                continue;
            }
            $exam_model->save();
            unset($exam['Exam']);

            if (isset($exam['Intro'])) {
                $exam['Intro'] = array_map(array($this, 'json_array_vars'), $exam['Intro']);
                //$exam['Intro']['exam_id'] = $exam_model->id;
                //$exam_model->Intro()->upsert(array_diff_key($exam['Intro'], ['created_at' => null, 'updated_at' => null, 'deleted_at' => null]), 'id', array_diff(array_keys($exam['Intro']), ['id', 'created_at', 'updated_at', 'deleted_at']));
                $exam_model->load('Intro');
                $intro = $exam_model->Intro;
                foreach ($exam['Intro'] as $column => $value) {
                    $intro->$column = $value;
                }

                $original_media = ['audio' => $intro->getOriginal('audio'), 'image' => $intro->getOriginal('image'), 'file' => $intro->getOriginal('file')];
                $new_data = ['audio' => $intro->audio, 'image' => $intro->image, 'file' => $intro->file];
                $this->order_with_type($original_media, $original_media, null);
                $this->order_with_type($new_data, $new_data, null);
                $this->check_discarded($new_data, $original_media, $media_to_be_deleted);
                $intro->save();
                unset($exam['Intro']);

            } else {
                $exam_model->loadCount('Intro');
                if ($exam_model->intro_count == 1) {
                    $media = $exam_model->Intro()->select('image', 'audio', 'file')->first()->toArray();
                    $exam_model->Intro()->forceDelete();
                    $this->order_with_type($media, $media, null);
                    $this->check_discarded($empty_array, $media, $media_to_be_deleted);
                }
            }

            foreach ($exam as $relation_name => $relation_data) {
                $updated_models = [];
                $new_models = [];
                foreach ($relation_data as $k => $model) {
                    //print_r($relation_data[$k]);
                    $relation_data[$k] = array_map(array($this, 'json_array_vars'), $relation_data[$k]);
                    $relation_data[$k] = array_diff_key($relation_data[$k], ['created_at' => null, 'updated_at' => null, 'deleted_at' => null]);
                    $relation_data[$k]['exam_id'] = $exam_model->id;
                    isset($relation_data[$k]['id']) ? $updated_models[] = $relation_data[$k] : $new_models[] = $relation_data[$k];

                }

                $options_exists = $this->search_for_keys($relation_data, 'options');
                $pieces_exists = $this->search_for_keys($relation_data, 'pieces');
                $to_fetch = ['id', 'image', 'audio'];
                if ($options_exists) $to_fetch[] = 'options';
                if ($pieces_exists) $to_fetch = ['id', 'pieces'];
                $exam_model->load($relation_name . ':' . implode(',', $to_fetch) . ',exam_id');
                $old_models_ids_with_media = $exam_model->$relation_name;

                //$old_models_ids_with_media->get();

                //collect removed questions by deferring new ids from old ids retrieved by load method on $exam_model
                $old_ids = $old_models_ids_with_media->map(function ($item, $key) {
                    return $item->only(['id']);
                })->flatten()->all();
                $updated_models_ids = array_column($updated_models, 'id');
                $deleted_models_ids = array_diff($old_ids, $updated_models_ids);
                $deleted_models_media = [];
                if (count($deleted_models_ids) > 0) {
                    $deleted_models_media = $old_models_ids_with_media->whereIn('id', $deleted_models_ids)->map(function ($item, $key) {
                        return $item->only(['image', 'audio']);
                    })->flatten()->filter()->all();
                }

                //check deleted media
                $old_media = $old_models_ids_with_media->whereIn('id', $updated_models_ids)->map(function ($item, $key) {
                    return $item->only(['image', 'audio']);
                })->flatten()->filter()->all();
                $new_media = array_filter(array_merge(array_column($updated_models, 'image'), array_column($updated_models, 'audio')));
                //dd($new_media, $old_media);

                $this->decode_flatten($old_media);
                $this->decode_flatten($new_media);
                $this->decode_flatten($deleted_models_media);

                //dd($new_media, $old_media, $deleted_models_media);

                if ($options_exists) {
                    $options_files = $this->get_options_or_pieces_media_from_collection($old_models_ids_with_media, array_merge($updated_models_ids, $deleted_models_ids), 'options');
                    $updated_models_options_files = array_column($updated_models, 'options');
                    array_walk($updated_models_options_files, array($this, 'decode'));
                    $this->extract_media($updated_models_options_files);
                    $this->check_discarded($updated_models_options_files, $options_files, $media_to_be_deleted);
                }

                if ($pieces_exists) {
                    $pieces_files = $this->get_options_or_pieces_media_from_collection($old_models_ids_with_media, array_merge($updated_models_ids, $deleted_models_ids), 'pieces');
                    $updated_models_pieces_files = array_column($updated_models, 'pieces');
                    array_walk($updated_models_pieces_files, array($this, 'decode'));
                    $this->extract_media($updated_models_pieces_files);
                    $this->check_discarded($updated_models_pieces_files, $pieces_files, $media_to_be_deleted);
                }

                $this->check_discarded($new_media, $old_media, $media_to_be_deleted);
                $this->check_discarded($empty_array, $deleted_models_media, $media_to_be_deleted); // delete the media of deleted questions as well

                //update questions and insert new and discard deleted
                //print_r($relation_data);
                if (count($deleted_models_ids) > 0) {
                    $exam_model->$relation_name()->whereIn('id', $deleted_models_ids)->forceDelete();
                }

                if (count($updated_models) > 0) {

                    $columns_to_update = array_diff(array_keys($updated_models[array_key_first($updated_models)]), ['id', 'created_at', 'updated_at', 'deleted_at']);
                    $exam_model->$relation_name()->upsert($updated_models, 'id', $columns_to_update);
                }

                if (count($new_models) > 0) {
                    $exam_model->$relation_name()->createMany($new_models);
                }
            }
        }
        print_r($media_to_be_deleted);
        Storage::delete($media_to_be_deleted);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->back();
    }

    /**
     * Attend the specified exam.
     *
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function attend(Request $request, Exam $exam = null)
    {
        $intro = $questions = [];
        $questions_sum = 0;
        if (isset($exam)) {
            $user = \auth()->user();
            $examSolved = $exam->have_preq_exam - 1000;
            $result = $user->solved()->wherePivot('exam_id', $examSolved)->latest()->first();
            if ($exam->have_preq_exam && $user->id != $exam->user_id) {
                if (!$result || $result->analysis->latest()->first()->percentage < $exam->pass_percentage) {
//                    abort(404);
                }
            }
            $questions = $this->get_available_question_types($exam);

            if (($request->route()->named('exams.attend', ['exam' => $exam->id]) && url()->previous() == route('exams.intro', ['exam' => $exam->id])) || ($request->route()->named('exams.attend', ['exam' => $exam->id]) && url()->previous() == route('exams.intro', ['exam' => $exam->id]))) {
                $questions_tmp = [];
                foreach ($questions as $key => $q) {
                    //$question_method_name = Str::snake($q);
                    if ($q != 'Project') {
                        $questions_tmp = array_merge($questions_tmp, [$q => $exam->$q()->get()->toArray()]);
                    }
                }
                $keys_order = [];
                if ($exam->random) {
                    $collector = [];
                    //dd($questions_tmp);
                    foreach ($questions_tmp as $key => $qs_array) {
                        $qs_array = array_map(function ($e) use ($key) {
                            $e['type'] = $key;
                            return $e;
                        }, $qs_array);
                        $collector = array_merge($collector, $qs_array);
                        unset($questions_tmp[$key]);
                    }
                    $questions_tmp = $collector;
                    shuffle($questions_tmp);
                    unset($collector);
                } else {
                    //dd($questions_tmp);
                    $this->order_with_type($questions_tmp, $questions_tmp, null);
                    //$keys_order = array_keys($questions_tmp);
                    //dd($questions_tmp);
                }

                $keys_order = array_map(function ($key, $value) {
                    return $value['id'] . '_' . ($value['type'] ?? explode('_', $key)[1]);
                }, array_keys($questions_tmp), $questions_tmp);

                session(['questions_keys_order' => $keys_order]);
                //deferring guest from user
                if (Auth::check()) {
                    return view('exam.attend', ['questions' => $questions_tmp, 'exam' => $exam]);
                } else {
                    $six_months = 6 * 43800;
                    $guest_ticket = Str::random(12);
                    if ($request->cookie('guest_ticket', false)) {
                        $guest_ticket = $request->cookie('guest_ticket');
                    }

                    return response()->view('exam.attend', ['questions' => $questions_tmp, 'exam' => $exam])->cookie('guest_ticket', $guest_ticket, $six_months);
                }

            } elseif ($request->route()->named('exams.intro', ['exam' => $exam->id]) || $request->route()->named('exams.intro', ['exam' => $exam->id])) {

                $exam->loadCount($questions);

                foreach ($questions as $key => $q) {
                    $q_prop = Str::snake($q . '_count');
                    if ($q_prop != 'project_count') $questions_sum += $exam->$q_prop;
                }
                $questions_sum == 0 ? session(['no_questions' => 'true']) : false;
                $intro = $exam->intro()->select('title', 'image', 'audio', 'video', 'paragraph', 'table', 'file', 'order_button')->first();
                $intro = $intro == null ? [] : $intro->toArray();
                $this->order_with_type($intro, $intro, null);
            } else {
                return /* Auth::check() ? redirect(route('exams.intro', ['exam' => $exam->id])) : */ redirect(route('exams.intro', ['exam' => $exam->id]));
            }
        }

        //dd($intro);
        return view('intro.exam_intro', ['intro_items' => $intro, 'exam' => $exam, 'questions_sum' => $questions_sum]);
    }

    /**
     * Mark the specified exam.
     *
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function mark(Request $request, Exam $exam)
    {
        //redirect if didn't attend exam
        if (url()->previous() != route('exams.attend', ['exam' => $exam->id]) && url()->previous() != route('exams.attend', ['exam' => $exam->id])) {
            return /* Auth::check() ? redirect(route('exams.intro', ['exam' => $exam->id])) : */ redirect(route('exams.intro', ['exam' => $exam->id]));
        }

        //STEP 0 : Preparation
        $user_id = Auth::user()->id ??
            User::where('email', 'guest_tickettemp_user_' . $request->cookie('guest_ticket'))->first()->id ??
            User::create(['name' => $request->cookie('guest_name', 'guest user'), 'email' => 'guest_tickettemp_user_' . $request->cookie('guest_ticket'), 'password' => Hash::make(Str::random())])->id;

        $attempts = $exam->students()->wherePivot('student_id', '=', $user_id)->count();
        $attempts++;
        $questions = $this->get_available_question_types($exam);
        $questions['project_submits'] = function ($query) use ($user_id) {
            return $query->where('student_id', $user_id)->select('remark');
        };
        $exam->load($questions);

        $correct_answers = 0;
        $false_questions = [];
        $input = $request->all();
        $total = 0;
        //STEP 1: marking answers
        foreach ($input as $question => $student_answer) {
            $id_with_type = explode('_', $question);
            $model_name = $id_with_type[1];
            if (isset($model_name) && in_array($model_name, $questions)) {
                $total++;
                if ($model_name == 'Puzzle') {
                    $answers = json_decode($exam->$model_name->firstWhere('id', $id_with_type[0])->pieces, true);
                    $correct_pieces = 0;

                    $student_answer = json_decode($student_answer, true);
                    foreach ($answers as $key => $answer) {
                        $answers[$key]['X'] = isset($student_answer) ? ($answers[$key]['X'] / $answers[$key]['scale']) * $student_answer[$key]['scale'] : 0;
                        $answers[$key]['Y'] = isset($student_answer) ? ($answers[$key]['Y'] / $answers[$key]['scale']) * $student_answer[$key]['scale'] : 0;
                        $answers[$key]['width'] = isset($student_answer) ? ($answers[$key]['width'] / $answers[$key]['scale']) * $student_answer[$key]['scale'] : 0;
                        $answers[$key]['height'] = isset($student_answer) ? ($answers[$key]['height'] / $answers[$key]['scale']) * $student_answer[$key]['scale'] : 0;
                        if (isset($student_answer)) {
                            if (
                                ($answers[$key]['X'] - 20) <= $student_answer[$key]['X'] && $student_answer[$key]['X'] <= ($answers[$key]['X'] + 20)
                                && ($answers[$key]['Y'] - 20) <= $student_answer[$key]['Y'] && $student_answer[$key]['Y'] <= ($answers[$key]['Y'] + 20)
                            ) {
                                $correct_pieces++;
                            }
                        }
                    }
                    if ($correct_pieces == count($answers)) {
                        $correct_answers++;
                    } else {
                        $false_questions[$question] = $student_answer;
                    }
                } else {
                    $student_answer = trim($student_answer);

                    $answer = $exam->$model_name->firstWhere('id', $id_with_type[0])->answer;
                    if ($model_name == 'WordGame') {
                        //dd($student_answer, $answer,  mb_strtoupper(str_replace(['i', 'ı'], ['İ', 'I'],$student_answer)), mb_strtoupper(str_replace(['i', 'ı'], ['İ', 'I'],$answer)));
                        mb_strtoupper(str_replace(['i', 'ı'], ['İ', 'I'], $student_answer)) == mb_strtoupper(str_replace(['i', 'ı'], ['İ', 'I'], $answer)) ? $correct_answers++ : $false_questions[$question] = $student_answer;
                    } else { //MCQ or any other future type
                        mb_strtoupper($student_answer) == mb_strtoupper($answer) ? $correct_answers++ : $false_questions[$question] = $student_answer;
                    }
                }
            }
        }

        //STEP 2: calculating percentage
        $percentage = round(($correct_answers / $total) * 100);
        $pass = true;
        $stars = 0;
        if ($percentage >= 90 && $percentage <= 100) {
            $stars = 3;
        } else if ($percentage >= 75 && $percentage <= 89) {
            $stars = 2;
        } else if ($percentage >= 60 && $percentage <= 74) {
            $stars = 1;
        } else {
            $pass = false;
        }
        //updating stars count if necessary
        if ($pass && Auth::check()) {
            $user = User::find($user_id);
            $user->stars = $user->stars + $stars;
            $user->save();
        }

        //STEP 3: saving exam results & returning the result.blade.php (thank you page with post exam options)
        $analysis_data = ['percentage' => $percentage, 'questions' => json_encode($false_questions), 'attempt' => $attempts, 'cert_serial' => null];
        $cert_serial = null;
        $exam->reward_type == 4 ? $analysis_data['cert_serial'] = Str::random(12) : false;
        //if (Auth::check()) {
        $exam->students()->attach($user_id, $analysis_data);
        //}

        /*else {
            $analysis_data['guest_id'] = $user_id;
            // passing null as array to stop type casting happening on attach method: createAttachRecords
            //https://laravel.io/forum/06-29-2014-custom-pivot-table-that-accepts-null-values-using-attach-does-not-work-with-null-values-why-how-to-work-around
            $exam->students()->attach([null], $analysis_data);
        }*/
        //dd($request, $exam->project_submits, $analysis_data, $total, $correct_answers);

        return view('exam.result', ['exam' => $exam, 'pass' => $pass, 'percentage' => $percentage, 'cert_serial' => $cert_serial, 'attempt' => $attempts]);
    }

    /**
     * analyze the specified exam.
     *
     * @param \App\Models\Exam $exam
     *
     * @return \Illuminate\Http\Response
     */
    public function analyze(Request $request, Exam $exam, int $attempt)
    {
        $user_id = Auth::user()->id ??
            User::where('email', 'guest_tickettemp_user_' . $request->cookie('guest_ticket'))->first()->id;
        $analysis_data = User::find($user_id)->solved()->wherePivot('exam_id', '=', $exam->id)->wherePivot('attempt', '=', $attempt)->get();
        //dd($user_id, User::find($user_id)->solved()->get());
        $wrong_questions = json_decode($analysis_data->first()->analysis->questions, true);

        $questions = $this->get_available_question_types($exam);

        $questions_tmp = [];
        foreach ($questions as $key => $q) {
            //$question_method_name = Str::snake($q);
            if ($q != 'Project') {
                $questions_tmp = array_merge($questions_tmp, [$q => $exam->$q()->get()->toArray()]);
            }
        }

        if (session('questions_keys_order')) {
            $order_keys = session('questions_keys_order');
            foreach ($order_keys as $key => $value) {
                $id_with_model = explode('_', $value);
                foreach ($questions_tmp[$id_with_model[1]] as $key2 => $q) {
                    if ($q['id'] == $id_with_model[0]) {
                        $questions_tmp[$value] = $q;
                        unset($questions_tmp[$id_with_model[1]][$key2]);
                        break;
                    }
                }
            }
            $questions_tmp = array_filter($questions_tmp);
        } else {
            if ($exam->random) {
                $collector = [];
                foreach ($questions_tmp as $key => $qs_array) {
                    $qs_array = array_map(function ($e) use ($key) {
                        return $e['type'] = $key;
                    }, $qs_array);
                    $collector = array_merge($collector, $qs_array);
                    unset($questions_tmp[$key]);
                }
                $questions_tmp = $collector;
                shuffle($questions_tmp);
                unset($collector);
            } else {
                $this->order_with_type($questions_tmp, $questions_tmp, null);
            }
        }

        $view_data = ['questions' => $questions_tmp, 'exam' => $exam, 'ans' => true, 'wrong_questions' => $wrong_questions];
        if (Auth::check()) {
            return view('exam.attend', $view_data);

        } else {
            $six_months = 6 * 43800;
            $guest_ticket = Str::random(12);
            if ($request->cookie('guest_ticket', false)) {
                $guest_ticket = $request->cookie('guest_ticket');
            }

            return response()->view('exam.attend', $view_data)->cookie('guest_ticket', $guest_ticket, $six_months);
        }
    }

    /**
     * Convert array elements in model array to json
     *
     * @param any $element
     */
    protected function json_array_vars($element)
    {
        if (is_array($element)) {
            return json_encode($element);
        } else {
            return $element;
        }
    }

    protected function map_files_to_columns($request, &$validated)
    {
        foreach ($validated as $key => $value) {
            if ($key != 'Exams' && $request->file($key) != null) {
                $levels = explode('_', $key);
                $model = preg_replace('/\d+/', '', $levels[0]);
                //print_r([$model, $currentLevel]);
                $fullName = Str::random(12) . '.' . $request->file($key)->guessExtension();
                $fullPath = $request->file($key)->storeAs(preg_replace('/\d+/', '', $levels[1]), $fullName, 'public');
                if (strstr($key, 'Intro')) {
                    $this->dynamic_assign($validated['Exams'], $levels, $fullPath, true, $key);
                } else {
                    $this->dynamic_assign($validated['Exams'], $levels, $fullPath, false, $key);
                }

            }
        }
    }

    /**
     * RECURSIVE: Enter dynamic (of unknown depth) multidimensional array until reaching the desired key to assign to
     *
     * @param array &$array the array of unknown depth
     * @param array $levels : the array of keys to loop over recursively until reaching the last one (will be considered the desired key to assign to)
     * @param any $value : value to be assigned
     */
    protected function dynamic_assign(&$array, $levels, $value, $intro = false, $levels_original_str)
    {
        $element = array_shift($levels);
        $element = str_replace("-", "_", $element);
        //print_r($element);
        //print_r($array);
        if (count($levels) > 0) {
            if (array_key_exists($element, $array)) { //for exam
                $this->dynamic_assign($array[$element], $levels, $value, $intro, $levels_original_str);
            } else {
                $element2 = preg_replace('/\d+/i', '', $element);
                if (array_key_exists($element2, $array)) { // for questions and intros highest level
                    //if ($intro) {
                    //    $this->dynamic_assign($array[$element2][$element], $levels, $value, $intro, $levels_original_str);
                    //} else {
                    $this->dynamic_assign($array[$element2][$element], $levels, $value, $intro, $levels_original_str);
                    //}
                } /*else {
                    $element2 = intval(preg_replace('/\D+/i', '', $element)) - 1;
                    //$id = intval(preg_replace('/\d/i', '', $element)) - 1;
                    $val = false;
                    //print($element);
                    if (array_key_exists('o', $array)) {
                        $val = $array;
                    } else {
                        foreach ($array as $key => $value) {
                            if ($value['o'] == $element2 ) {
                                $val = $value;
                                break;
                            }
                        }
                    }
                    if ($val) { // for intro items
                        $this->dynamic_assign($val, $levels, $value);
                    } else {
                        $element = preg_replace('/(?<=image).+/', '', $element);
                        if (!isset($array[$element])) {
                            $array[$element] = [$value];
                        } else {
                            if (is_array($array[$element])) {
                                $array[$element][] = $value;
                            } else {
                                $array[$element] = [$array[$element], $value];
                            }
                        }
                    }
                }*/
            }
        } else {
            $element = preg_replace('/(?<=image).+/', '', $element);
            if (!isset($array[$element]) || $array[$element] == $levels_original_str || $array[$element] == str_replace("_data", '', $levels_original_str)) {
                $array[$element] = $value;
            } else {
                if (is_array($array[$element])) {
                    $array[$element][] = $value;
                } else {
//                    $array[$element] = [$array[$element], $value];
                    $array[$element] = $value;
                }
            }
        }
    }

    /**
     * searching for the order element
     */
    protected function order_with_type(&$array, &$original, $type_in_key)
    {
        //echo "<pre>";
        //var_dump($array);
        //echo "</pre><br>";

        foreach ($array as $column => $subarray) {
            if (!isset($subarray) || $subarray == []) {
                unset($array[$column]);
                continue;
            }
            $subarray = is_array($subarray) ? $subarray : json_decode($subarray, true);
            //echo "before: <pre>";
            //print_r($subarray);
            //echo "</pre><br>";

            $data = $this->r($subarray);
            //echo "before: <pre>";
            //print_r($data);
            //echo "</pre><br>";
            foreach ($data as $key => $item) {
                $str_part_of_key = $type_in_key ?? $column;
                $num = $item['o'] ?? $item['order'];
                $original[$num . '_' . $str_part_of_key] = $item['data'] ?? $item;
            }
            unset($array[$column]);
            //echo "result: <pre>";
            //print_r($data);
            //echo "</pre><br>";
        }
        ksort($array, 1);
    }

    protected function r(&$array)
    {
        foreach ($array as $key => $subarray) {
            $subarray = is_array($subarray) ? $subarray : json_decode($subarray, true);
            if (isset($subarray['o']) || isset($subarray['order'])) {
                return $array;
            } else {
                $this->r($subarray);
            }
        }
    }

    /**
     * Auotmated way to get the exam question types available
     */
    protected function get_available_question_types(&$exam)
    {
        $related_questions = get_class_methods($exam);
        $questions = array_diff(scandir(app_path('Models/Questions')), ['.', '..']);
        $questions = array_map(function ($e) {
            return str_replace('.php', '', $e);
        }, $questions);
        $questions = array_intersect($questions, $related_questions);
        return $questions;
    }

    /**
     * Detecting any discarded media. Passing $new as an empty array to remove all media on removing its owning model
     */
    protected function check_discarded(&$new, &$original, &$collector)
    {
        $discarded = array_diff($original, $new);
        $collector = array_merge($collector, $discarded);
    }

    /**
     * RECURSIVE: search dynamic (of unknown depth) multidimensional array until finding the desired key
     * NOTE: the array elements must be uniform i.e: same structure e.g.: questions array
     *
     * @param array &$array the array of unknown depth
     * @param array $key : the key to search for
     */
    protected function search_for_keys(&$array, $key)
    {
        $array_keys = array_keys($array);
        if (in_array($key, $array_keys)) {
            return true;
        } elseif (is_array($array[$array_keys[0]])) {
            return $this->search_for_keys($array[$array_keys[0]], $key);
        } else {
            return false;
        }
    }

    protected function get_options_or_pieces_media_from_collection(&$original_collection, $ids, $column = 'options')
    {
        $options = $original_collection->whereIn('id', $ids)->map(function ($item, $key) use ($column) {
            return $item->only([$column]);
        })->flatten()->filter()->all();
        //dd($options);
        array_walk($options, array($this, 'decode'));
        //dd($options);
        $this->extract_media($options);
        //dd($options);
        //$options = array_walk($options, array($this, 'extract_media_columns'));
        return $options;
    }

    protected function decode(&$value, &$key)
    {
        $value = json_decode($value, true);
    }

    protected function extract_media(&$array)
    {
        $temp_array = [];
        foreach ($array as $key => $value) {
            $temp_array = array_filter(array_merge($temp_array, array_column($value, 'image'), array_column($value, 'audio')));
        }
        $array = $temp_array;
        unset($temp_array);
    }

    /**
     * decode array items and flatten it in one walk... primary use is for collecting media files paths in JSON form
     */
    protected function decode_flatten(&$array)
    {
        $new_array = [];
        foreach ($array as $k => $value) {
            $new_array = array_merge($new_array, json_decode($array[$k], true) ?? [$array[$k]]);
            unset($array[$k]);
        }
        $array = $new_array;
        unset($new_array);
    }

}
