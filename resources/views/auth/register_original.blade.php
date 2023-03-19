@extends('layouts.app')
@section('styles')
<style>
    .custom-grad {
        background: rgb(97,0,170);
        background-size: cover;
        background-repeat: no-repeat;
    }

    button[type=submit] {
        border-radius: 1.5rem !important; 
        background-color: rgba(255,105,180, 1);
        border: 1px solid rgba(255,105,180, 1);
    }

    a {
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
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-lg-9 text-center text-white">
                <h3 class="font-weight-bold mb-0">{{ __('New Account') }}</h3>
                <p>(*) Denotes required field</p>
                    <form class="mt-1" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="far fa-user"></i></span>
                                    </div>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="{{ __('Full Name') }}*" required autocomplete="name" autofocus>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="far fa-user" style="visibility: hidden"></i></span>
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group mb-2">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    </div>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email') }}*" name="email" value="{{ old('email') }}" required autocomplete="email">
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

                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key fa-rotate-180"></i></span>
                                    </div>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}*" name="password" required autocomplete="new-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" onclick="var s = $(this).parent().prev('input'); s.attr('type') == 'password' ? s.attr('type', 'text'): s.attr('type', 'password')"><i class="far fa-eye" style="cursor: pointer"></i></span>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-5">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key fa-rotate-180 "></i></span>
                                    </div>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}*" required autocomplete="new-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" onclick="var s = $(this).parent().prev('input'); s.attr('type') == 'password' ? s.attr('type', 'text'): s.attr('type', 'password')"><i class="far fa-eye" style="cursor: pointer"></i> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="mb-1">
                                    <button class="btn btn-success btn-lg py-2 text-center" style="border-radius: 1.25rem;" type="button" role="button" onclick="document.getElementById('avatar').click();">{{ __('Upload Avatar') }} <i class="fas fa-cloud-upload-alt"></i></button>
                                </div>
                                <input id="avatar" hidden type="file" accept="image/*" class=" @error('avatar') is-invalid @enderror" name="avatar" value="{{ old('avatar') }}">

                                @error('avatar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 mb-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Create Account') }}
                                </button>
                            </div>
                            <p>{{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Login') }}</a></p>
                        </div>
                    </form>
                
            </div>
        </div>
    </div>
</div>
@endsection
