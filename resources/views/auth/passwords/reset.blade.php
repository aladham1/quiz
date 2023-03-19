@extends('dashboard.layouts.master')

@section('content')
    <aside class="lgbg">
        <div class="lgbg2">
    <div class="lgin1"><img src="{{ url('images/logo_1.svg') }}"/></div>


    <div class="frgfrm">
       
            
        <form method="POST" action="{{ route('password.update') }}" id="fgFrm"> 
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="lgIrow">
                <input type="email" class="lginfld icneml @error('email') is-invalid @enderror" name="email" placeholder="Email / Phone">
            </div>
            @error('email')
                <span class="invalid-feedback" style="display: inline-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
                <div class="lgIrow">
                    <input id="password" type="password" placeholder="{{ __('Password') }}" class="lginfld icneml @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" style="display: inline-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="lgIrow">
                    <input id="password-confirm" type="password" placeholder="{{ __('Confirm Password') }}" class="lginfld icneml" name="password_confirmation" required autocomplete="new-password">
                </div>
            <div class="frgBtnBx">
                <span class="lgbtn1" onclick="javascript: showLoader(); $('#fgFrm').submit()">Reset password</span>
            </div>
        </form>
    </div>

    <div class="rgtnBx2">
        already have an account? <a href="{{ route('login') }}"><span>LOGIN</span></a>
    </div>

    </div>
    </aside>
    <div class="loader" style="display:none"></div>
@endsection