<?php
namespace App;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FaceAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('handleGoogleCallback');

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();
Route::get('/exam-playing/{exam}', 'ExamController@attend')->name('exams.attend');

Route::group(['prefix' => 'guest', 'as' => 'guest.'], function () {

    Route::get('/', function () {
        return view('dashboard.guest_login');
    })->name('login');

    Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('redirectToGoogle');

    Route::get('/start-exam/{exam}', 'ExamController@attend')->name('exams.intro');


    Route::post('/exam/{exam}/thankyou', 'ExamController@mark')->name('exams.mark');

    Route::get('/exams/{exam}/analyze/{attempt}', 'ExamController@analyze')->name('exams.analyze')->where('attempt', '[0-9]+');

    Route::get('/exams/{exam}/submit-project', 'ProjectSubmitController@create')->name('exams.project_submits.create');
});

/*
Route::get('/start-exam/4015',function() {
    return redirect()->route('guest.exams.intro', ['exam' => 4015]);
});
Route::get('/start-exam/4040',function() {
    return redirect()->route('guest.exams.intro', ['exam' => 4040]);
});
Route::get('/start-exam/4041',function() {
    return redirect()->route('guest.exams.intro', ['exam' => 4041]);
});
Route::get('/start-exam/4042',function() {
    return redirect()->route('guest.exams.intro', ['exam' => 4042]);
});
Route::get('/start-exam/4043',function() {
    return redirect()->route('guest.exams.intro', ['exam' => 4043]);
}); */

Route::get('/dashboard/exams/8/intro',function() {
    return redirect()->route('exams.intro', ['exam' => 1]);
});
Route::get('/dashboard/exams/9/intro',function() {
    return redirect()->route('exams.intro', ['exam' => 2]);
});
Route::get('/dashboard/exams/10/intro',function() {
    return redirect()->route('exams.intro', ['exam' => 3]);
});
Route::get('/dashboard/exams/11/intro',function() {
    return redirect()->route('exams.intro', ['exam' => 4]);
});
Route::get('/dashboard/exams/12/intro',function() {
    return redirect()->route('exams.intro', ['exam' => 5]);
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::match(['get', 'post'], '/start-exam/{exam}', 'ExamController@attend')->name('exams.intro');
Route::post('/exam/{exam}/thankyou', 'ExamController@mark')->name('exams.mark');
Route::get('/exams/{exam}/analyze/{attempt}', 'ExamController@analyze')->name('exams.analyze')->where('attempt', '[0-9]+');
Route::get('/exams/{exam}/submit-project', 'ProjectSubmitController@create')->name('exams.project_submits.create');

Route::group([/*'prefix' => 'dashboard',*/ 'middleware' => 'auth'], function () {
//    Route::get('/exam-playing/{exam}', 'ExamController@attend')->name('exams.attend');

//    Route::get('/start-exam/{exam}', 'ExamController@attend')->name('exams.intro');

    Route::get('/home', function () {
        return view('dashboard.home');
    })->name('home');

    Route::get('/discover', function () {
        return view('group.discover');
    })->name('discover');



    Route::get('/profile', 'ProfileController@index')->name('profile');

    Route::get('/edit-profile', function () {
        return view('dashboard.edit_profile');
    })->name('profile.edit');

    Route::post('/update-profile', function () {
        request()->validate([
            'user_image_data' => 'nullable|bail|starts_with:data:image/png;base64,',
            'user_name' => 'bail|required',
        ], ['user_image_data.starts_with' => 'wrong image format']);

        $u = User::find(auth()->id());
        $base64_data = request()->input('user_image_data', false);
        $path = $u->avatar;
        if($base64_data){
            $pure_b64_string = str_replace(' ', '+', str_replace('data:image/png;base64,', '', $base64_data));
            $path = 'users/'.Str::random(12).'.png';
            Storage::disk('public')->put($path, base64_decode($pure_b64_string));
            if ($u->avatar != 'users/default.png') {
                Storage::delete([$u->avatar]);
            }
        }

        $u->name = request()->input('user_name');
        $u->phone = request()->input('phone');
        $u->type = request()->input('type');
        $u->avatar = $path;
        $u->save();
        return redirect(route('home'));
    })->name('profile.update');

    Route::get('/teacher/{id}', function ($id) {
        if ($id == Auth::id()) {
            return redirect(route('profile'));
        }
        return view('dashboard.profile', ['id' => $id]);
    })->name('teachers.show');

    Route::get('/new-game', function () {
        return view('new-game');
    })->name('games.new.form');

    Route::get('/create-exam', 'ExamController@create')->name('exams.create');
    Route::get('/edit_exam/{exam}', 'ExamController@edit')->name('exams.edit');

    Route::get('/exam-info/{exam}', 'ExamController@show')->name('exams.show');
    Route::get('/exams/{exam}/showReward', 'ExamController@showReward')->name('exams.showReward');
    Route::get('/printReward/{exam}/printReward', 'ExamController@printReward')->name('exams.printReward');

    Route::get('/create-group', 'GroupController@create')->name('groups.create');
    Route::get('/groups_qrcods/{group}', 'GroupController@qrcods')->name('groups.qrcods');
    Route::get('/edit-group/{group}', 'GroupController@edit')->name('groups.edit');
    Route::post('/groups/{group}/privacy', 'GroupController@togglePrivacy')->name('groups.togglePrivacy');
    Route::get('/groups/{group}/desc', 'GroupController@showDesc')->name('groups.showDesc');
    Route::post('/groups/follow', 'GroupController@follow')->name('groups.follow');
    Route::post('/groups/unfollow', 'GroupController@unfollow')->name('groups.unfollow');
    Route::post('/groups/sendNotification', 'GroupController@sendNotification')->name('groups.notify');
    Route::post('/groups/addExam', 'GroupController@addExam')->name('groups.addExam');

    Route::resources([
        'exams' => 'ExamController',
        'groups' => 'GroupController',
    ]);

    Route::get('/project_submits/{projectSubmit}/comment', 'ProjectSubmitController@showComment')->name('exams.project_submits.showComment');
    Route::resource('exams.project_submits', 'ProjectSubmitController')->except('create')->scoped();

    Route::get('/intro/preview', function () {
        return  view('intro.exam_intro', ['intro_items' => [], 'exam' => null, 'questions_sum' => 0]);
    })->name('intro.temp.preview');

    Route::get('/getfile/{file}', function ($file) {
        if (Storage::exists($file)) {
            return Storage::url($file);
        } else {
            return response('Not Found', 404);
        }
    })->name('storage.getfile')->where('file', '.*');

    Route::post('/new-game', 'GameController@store')->name('games.new');

    Route::get('/games/play/{id}', 'GameController@show')->name('games.play');

    Route::get('/games', function () {
        //return view('home');
        return view('games');
    })->name('games.all');

    Route::get('/games/progress/{id}', function ($id) {
        //$game = Puzzle::find($id);
        //if ($game->puzzle == 'No puzzle provided') {
        //    return response('Added name and description, uploading question image...', 200);
        //} elseif (!isset($game->pieces)) {
        //    return response('Uploaded question image, adding answers...', 200);
        //} elseif ($game->thumb == 'https://picsum.photos/id/1031/80/80?grayscale&blur=2') {
        //    return response('Nearly finished, adding thumbnail...', 200);
        //} else {
        //    return response('All done, enjoy', 200);
        //}
    })->name('games.progress');
});

Route::post('/faceapp2/{cmd}', function ($cmd) {
    //Log::info(request());
    if ($cmd == 'index.php') {
        //return request();
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', request()->input('image')));
        $path = uniqid().'.jpg';
        Storage::put('faceapp/'.$path, $data);
        $fa = FaceAuth::Create([
            'image' => $path,
            'is_allowed' => request()->input('is_allowed', false),
            'hardware_name' => request()->input('hardware_name')
        ]);
        return $fa->toJson();
    }
    if ($cmd == 'delete_face.php') {
        return FaceAuth::destroy(request()->input('id'));
    }
})->where('cmd', '.*');

Route::post('/faceapp/{cmd}', function ($cmd) {
    //Log::info(request());
    if ($cmd == 'index.php') {
        //return request();
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', request()->input('image')));
        $path = uniqid().'.jpg';
        Storage::put('faceapp/'.$path, $data);
        $fa = FaceAuth::Create([
            'image' => $path,
            'is_allowed' => request()->input('is_allowed', false),
            'hardware_name' => request()->input('hardware_name')
        ]);
        return $fa->toJson();
    }
    if ($cmd == 'delete_face.php') {
        return FaceAuth::destroy(request()->input('id'));
    }
})->where('cmd', '.*');

Route::get('/faceapp/{cmd}', function ($cmd) {
    //Log::info(request());
    if ($cmd == 'index.php') {
        //return request();
    }
    if ($cmd == 'get_all_face.php') {
        return FaceAuth::all()->toJson();
    }

    if (Str::startsWith($cmd, 'images/')) {
        return Storage::download(Str::replaceFirst('faceapp/faceapp/', 'faceapp/',Str::replaceFirst('images/', 'faceapp/', $cmd)));
    }
    //return (new FaceAuthController)->create(request(), $cmd);
    //Route::get('images/{file}', function ($file) {

    //})->name('faceapp.image.download')->where('file', '.*');
    //Route::get('get_all_face.php','FaceAuthController@index');
    //Route::post('delete_face.php','FaceAuthController@delete');
    //Route::post('{cmd}','FaceAuthController@create');
})->where('cmd', '.*');

Route::get('/faceapp2/{cmd}', function ($cmd) {
    //Log::info(request());
    if ($cmd == 'index.php') {
        return request();
    }
    if ($cmd == 'get_all_face.php') {
        return FaceAuth::all()->toJson();
    }

    if (Str::startsWith($cmd, 'images/')) {
        return Storage::download(Str::replaceFirst('faceapp/faceapp/', 'faceapp/',Str::replaceFirst('images/', 'faceapp/', $cmd)));
    }
    //return (new FaceAuthController)->create(request(), $cmd);
    //Route::get('images/{file}', function ($file) {

    //})->name('faceapp.image.download')->where('file', '.*');
    //Route::get('get_all_face.php','FaceAuthController@index');
    //Route::post('delete_face.php','FaceAuthController@delete');
    //Route::post('{cmd}','FaceAuthController@create');
})->where('cmd', '.*');



Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/download/{file}', function ($file) {
    return Storage::download($file);
})->name('storage.download')->where('file', '.*');

Route::fallback(function () {
    return redirect()->route('home');
});


Route::get('/foo-ss', function () {
    \Artisan::call('storage:link');
});
