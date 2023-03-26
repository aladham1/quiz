<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        if (!$this->loginOrRegister($user, 'Google')) {
            return \redirect('/')
                ->with('message', 'The email or name has already been taken.')
                ->with('icon', 'error')
                ->with('title', __('common.error'));
        }
        return \redirect()->route('home');
    }

    public function loginOrRegister($data, $type)
    {
        $name = $data->name ;
        $email = $data->email;
        $socail_id = null;
        if ($type == 'Google') {
            $socail_id = $data->id;
            $check_user = User::where('socail_id', $socail_id)->first();
        } else {
            $check_user = User::where('email', $email)->first();
        }

        if ($check_user) {
            Auth::login($check_user, true);
            $user = \auth()->user();

        } else {
            $request_data = request()->all();
            $request_data['name'] = $name;
            $request_data['email'] = $email;
            $validator = \Validator::make($request_data, [
                'email' => 'required|unique:users',
                'name' => 'required|unique:users',
            ]);
            if ($validator->fails()) {
                return false;
            }

            if (empty($email)) {
                $email = 'email' . str_random(2) . '@email.com';
            }
            if (empty($mobile)) {
                $mobile = mt_rand(100000, 999999);
            }
            $user = User::create([
                'name' => $name,
                'email' => $email,
                "socail_id" => $socail_id,
            ]);

            \auth()->login($user);
        }
        return true;
    }
}
