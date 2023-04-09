<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Tag;
use App\Notifications\GroupNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->query('query', false)) {
            $id = request()->query('query');
            $groups = Group::where('private', '=', 0)->where(function ($query) use ($id) {
                $query->where('code','like', "%{$id}%")->orWhere('title', 'like', "%$id%")
                    ->orWhereHas('exams', function ($q) use ($id) {
                        $q->where('exams.id', '=', $id)
                            ->orWhere('title', 'like',  "%$id%");
                    });
            })
                ->withCount(['followers', 'exams',])
                ->with(
                    [
                        'owner:id,name'
                    ]
                )->get();
            $following = User::find(Auth::id())->load('following:id');

            $following = $following->following->pluck('id')->toArray();
            foreach ($groups as $k => $g) {
                $groups[$k]['image'] = isset($g['image']) ? Storage::url($g['image']) : url('/images/placeholder.jpeg');
                in_array($g['id'], $following) ? $g['following'] = true : $g['following'] = false;

            }

            return response()->json($groups);
        }

        $user = User::find(Auth::user()->id);
        $groups = $user->following()->with('owner')->withCount(['followers', 'exams'])->get();
        return view('group.following', ['groups' => $groups]);
    }


    public function create()
    {

        $user = User::find(Auth::user()->id)->load(
            [
                'exams' => function ($query) {
                    $query->latest();
                },
            ]);
        $user_exams = $user->exams;
        return view('group.create-update', ['exams' => [], 'user_exams' => $user_exams,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $exams = $user->exams()->pluck('id')->all();
        //dd($exams);
        $request->merge([
            'exam_ids' => $exams
        ]);
        $data = $this->get_rules(false);
        $valid = $request->validate($data['rules']);
        //dd($valid);
        $image_path = null;

        if (isset($valid['group_image_data'])) {
            $image_path = $this->save_base64_image($valid['group_image_data']);
            //dd($image_path);
        }

        $group = $user->groups()->create([
            'title' => $valid['title'],
            'description' => $valid['description'],
            'image' => $image_path,
            'password' => $valid['password'],
            'private' => isset($valid['private']),
        ]);
        $group->update([
            'code' => 'G' . ((int)$group->id + 100)
        ]);
        $tags = explode(' ', $valid['tags']);
        $tag_model_arrays = [];
        foreach ($tags as $key => $tag) {
            $tag_model_arrays[$key] = ['tag' => trim($tag)];
        }
        //dd([$tags, $valid['tags']]);
        Tag::insertOrIgnore($tag_model_arrays);
        unset($tag_model_arrays);
        $tags_ids = Tag::whereIn('tag', $tags)->pluck('id')->all();
        $group->tags()->attach($tags_ids);

        if (isset($valid['new_exams'])) {
            $new = $valid['new_exams'];
            $group->exams()->attach($new);
        }

        return redirect('profile')->with('message', 'done');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, Request $request)
    {
        $group->load('owner');
        $group->loadCount(['exams', 'followers', 'news']);
        if ($request->wantsJson()) {
            $group->image = $group->image ? Storage::url($group->image) : asset('images/placeholder.jpeg');
            $group->owner->avatar = Storage::url($group->owner->avatar);
            return response()->json([
                "group" => $group
            ]);
        }
        $owner = $group->owner;
        $followers = $group->followers()->latest()->paginate(20);
        $exams = $group->exams()->with('owner')->latest()->paginate(20);
        $news = $group->news()->latest()->paginate(20);
        return view('group.details', ['group' => $group, 'owner' => $owner, 'followers' => $followers, 'exams' => $exams, 'news' => $news]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $user = User::find(Auth::user()->id)->load(
            [
                'exams' => function ($query) {
                    $query->latest();
                },
            ]);
        $user_exams = $user->exams;
        $group->load(['exams' => function ($query) {
            $query->with('owner');
        }, 'owner']);
        $exams = $group->exams;

        $tags = implode(' ', $group->tags()->pluck('tag')->all());
        return view('group.create-update', ['user_exams' => $user_exams, 'tags' => $tags, 'group' => $group, 'exams' => $exams]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $exams = User::find(Auth::user()->id)->exams()->pluck('id')->all();
        $request->merge([
            'exam_ids' => $exams
        ]);
        $data = $this->get_rules(true);
        $valid = $request->validate($data['rules']);

        if (isset($valid['group_image_data'])) {
            $image_path = $this->save_base64_image($valid['group_image_data']);
            isset($group->image) ? Storage::delete($group->image) : null;
            $group->image = $image_path;
        }

        $new_data = [
            'title' => $valid['title'],
            'description' => $valid['description'],
            'password' => $valid['password'],
            'private' => isset($valid['private']),
        ];
        $group->fill($new_data);

        $tags = $group->tags()->pluck('tag')->all();
        $valid['tags'] = explode(' ', $valid['tags']);

        $new_tags = array_diff($valid['tags'], $tags);
        if (count($new_tags) > 0) {
            $new_tags_models_array = [];
            foreach ($new_tags as $key => $tag) {
                $new_tags_models_array[$key] = ['tag' => $tag];
            }
            Tag::insertOrIgnore($new_tags_models_array);
            unset($new_tags_models_array);
            $new_tags_ids = Tag::whereIn('tag', $new_tags)->pluck('id')->all();
            $group->tags()->attach($new_tags_ids);
        }

        $deleted_tags = array_diff($tags, $valid['tags']);
        if (count($deleted_tags) > 0) {
            $deleted_tags = Tag::whereIn('tag', $deleted_tags)->pluck('id')->all();
            $group->tags()->detach($deleted_tags);
        }

        if (isset($valid['new_exams']) && count($valid['new_exams']) > 0) {
            $group->exams()->attach($valid['new_exams']);
        }
        if (isset($valid['deleted_exams']) && count($valid['deleted_exams']) > 0) {
            $group->exams()->detach($valid['deleted_exams']);
        }

        $group->save();
        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();
    }

    protected function save_base64_image($base64_data)
    {
        $pure_b64_string = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $base64_data));
        $path = 'Group/' . Str::random(12) . '.png';
        Storage::disk('public')->put($path, base64_decode($pure_b64_string));
        return $path;
    }

    protected function get_rules(bool $update)
    {
        $rules = [
            'title' => 'required|min:2|max:20',
            'description' => 'nullable',
            'group_image_data' => 'nullable|regex:/^data\:image\/png\;base64.+/',
            'password' => 'nullable',
            'tags' => 'nullable',
            'private' => 'sometimes|accepted',
            //'new_exams' => 'required_without:exams',
            'new_exams.*' => 'in_array:exam_ids.*',

        ];
        $msgs = [];

        if ($update) {
            //$rules['exams'] = 'required_without:new_exams';
            $rules['exams.*'] = 'in_array:exam_ids.*';
            $rules['deleted_exams.*'] = 'in_array:exam_ids.*';
        }

        return ['rules' => $rules, 'msgs' => $msgs];
    }

    public function togglePrivacy(Group $group)
    {
        $group->private = !$group->private;
        $group->save();
    }

    public function addExam(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $exams = $user->exams()->pluck('id')->all();

        $request->merge([
            'exam_ids' => $exams,
        ]);

        $valid = $request->validate([
            'new_exams.*' => 'bail|required|in_array:exam_ids.*',
        ]);

        $group = Group::find($request->get('gid'));
        if ($group->owner->id == Auth::user()->id || $group->password == $request->get('password')) {
            if (isset($valid['new_exams'])) {
                $new = $valid['new_exams'];
                $group->exams()->attach($new);
            }
        } else {
            return response('unauthorized', 403);
        }
        return 1;

    }

    public function follow(Request $request)
    {
        $request->validate([
            'group' => 'required|integer',
        ]);
        $group = Group::find($request->input('group'));
//        if ($group->password){
//            if ($request->password != $group->password){
//                return response()->json('error', 401);
//            }
//        }
        $group->followers()->attach([Auth::id()]);
    }

    public function unfollow(Request $request)
    {
        $request->validate([
            'group' => 'required|integer',
        ]);
        $group = Group::find($request->input('group'));
        $group->followers()->detach([Auth::id()]);
    }

    public function sendNotification(Request $request)
    {
        $request->merge([
            'ids' => User::find(auth()->id())->groups()->get('id')->toArray(),
        ]);
        $request->validate([
            'id' => 'required|in_array:ids.*',
            'title' => 'string',
            'msg' => 'required|string',
            'is_news' => 'boolean',
        ], ['msg.required' => 'Notification message is required']);
        $group = Group::find($request->input('id'));
        if ($request->input('is_news')) {
            $group->news()->create([
                'type' => 1,
                'title' => $request->input('title'),
                'body' => $request->input('msg'),
            ]);
        }

        $group_details = [
            'title' => $request->input('title') . ' - ' . $group->title . " Group Notification - Questanya",
            'msg' => $request->input('msg'),
            'url' => route('groups.show', ['group' => $group->id]),
            'user_ids' => $group->followers()->select('iphone_app_id', 'android_app_id')->get()->filter()->toArray(),
        ];
        $group->notifyNow(new GroupNotification($group_details));
    }

    public function showDesc(Group $group)
    {
        return $group->description;
    }

    public function qrcods(Group $group)
    {
       return view('group.qrcodes', ['group' => $group->load('exams')]);
    }
}
