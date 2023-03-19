@extends('dashboard.layouts.master')

@section('content')
<aside class="lgbg">
    <div class="lgbg2">
<div class="lgin1"><img src="{{ url('images/logo_1.svg') }}"/></div>


<div class="frgfrm">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" id="fgFrm"> 
        @csrf
        <div class="lgIrow">
            <input type="email" class="lginfld icneml @error('email') is-invalid @enderror" name="email" placeholder="Email / Phone">
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <div class="frgBtnBx">
            <span class="lgbtn1" onclick="javascript: showLoader(); $('#fgFrm').submit()">{{ __('Send Password Reset Link') }}</span>
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
