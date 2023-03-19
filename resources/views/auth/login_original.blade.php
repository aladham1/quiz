@extends('layouts.app')
@section('styles')
<style>
    .custom-grad {
        background: rgb(97,0,170);
        background: -moz-linear-gradient(342deg, rgba(97,0,170,1) 0%, rgba(103,0,156,1) 72%, rgba(232,0,219,1) 100%);
        background: -webkit-linear-gradient(342deg, rgba(97,0,170,1) 0%, rgba(103,0,156,1) 72%, rgba(232,0,219,1) 100%);
        background: linear-gradient(342deg, rgba(97,0,170,1) 0%, rgba(103,0,156,1) 72%, rgba(232,0,219,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#6100aa",endColorstr="#e800db",GradientType=1);
        background-size: cover;
        background-repeat: no-repeat;
    }
    button[type=submit] {
        border-radius: 1.5rem !important; 
        background-color: rgba(255,105,180, 1);
        border: 1px solid rgba(255,105,180, 1);
    }
    .register {
        border-radius: 1.5rem !important; 
        background-color: rgba(255,105,180, 1);
        border: 1px solid rgba(255,105,180, 1);
    }

    a:not(.register) {
       color: rgba(255,105,180, 1) !important;
       text-decoration: underline;
    }

    .form-group .input-group-append, .form-group .input-group-prepend {
        transition: box-shadow 400ms ease;
    }
    .input-group-prepend {
        border-top-left-radius: 0.85rem !important;
        border-bottom-left-radius: 0.85rem !important;
        background-color: white;
        z-index: 4;
    }
    
    .input-group-append {
        border-top-right-radius: 0.85rem !important;
        border-bottom-right-radius: 0.85rem !important;
        background-color: white;
        z-index: 4;
    }
    .input-group-prepend .input-group-text {
        border-top-left-radius: 0.85rem !important;
        border-bottom-left-radius: 0.85rem !important;
        background-color: white !important;
        border-right: none !important;
    }
    .input-group-append .input-group-text {
        border-top-right-radius: 0.85rem !important;
        border-bottom-right-radius: 0.85rem !important;
        background-color: white !important;
        border-left: none !important;
    }

    input.form-control {
      transition: box-shadow 500ms ease;
      border-left: none !important;
      border-right: none !important;
      box-shadow: none;
    }

    input.form-control:focus {
      box-shadow: 4px 0px 6px 0.2rem rgba(255,105,180, 1);
    }

    input.form-control:active {
      box-shadow: 4px 0px 6px 0.2rem rgba(255,105,180, 1);
    }
    input.form-control:focus + .input-group-append {
        box-shadow: 9px 0px 6px 0.2rem rgba(255,105,180, 1); 
    }

    input.form-control:active + .input-group-append {
        box-shadow: 9px 0px 6px 0.2rem rgba(255,105,180, 1); 
    }
    .prepend-shadow {
        box-shadow: -2px 0px 6px .2rem rgba(255,105,180, 1); 
    }

    .form-group.row {
        justify-content: center !important;
    }

    input {
      text-align: center !important;
    }
    input::placeholder {
      text-align: center !important;
    }
    ::-webkit-input-placeholder {
      text-align: center !important;
    }
    
    :-moz-placeholder {
      text-align: center !important;
    }
</style>
@endsection
@section('content')
<div class="container font-weight-bold h-100">
    <div class="row justify-content-center align-items-center h-100 ">
        <div class="col-lg-9 text-center text-white">
                <h3 class="font-weight-bold mb-0">{{ __('Login') }}</h3>
                    <form class="mt-3" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group mb-2">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    </div>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email') }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <div class="input-group-append"> 
                                        <span class="input-group-text"><i class="fas fa-at" style="visibility: hidden"></i></span>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key fa-rotate-180"></i></span>
                                    </div>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" name="password" required autocomplete="new-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text"  onclick="var s = $(this).parent().prev('input'); s.attr('type') == 'password' ? s.attr('type', 'text'): s.attr('type', 'password')"><i class="far fa-eye" style="cursor: pointer"></i></span>
                                    </div>
                                    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-right mt-1 mb-2">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group row justify-content-left">
                            <div class="col-md-5 text-left">
                                <div class="form-check">
                                    <input class="form-check-input" style="margin-top: 0.2rem" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-5">
                                <hr>
                            </div>
                            <div class="col-2" style="line-height: 2rem">
                                <p>OR</p>
                            </div>
                            <div class="col-5">
                                <hr>
                            </div>
                            <div class="col-md-8">
                                <a class="btn btn-primary register btn-block" href="{{ route('register') }}">{{ __('Create Account') }}</a>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection
