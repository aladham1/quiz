@extends('dashboard.layouts.master')
@section('css')
<script src="https://apis.google.com/js/platform.js" async defer></script>
/*<meta name="google-signin-client_id" content="43316633750-4gfplbbhqmgg2i5bgjhqgj93ip51n9b8.apps.googleusercontent.com">*/
<style>
    span.g-signin2 {
        width: 100%;
        display: inline-block;
    }

    span.g-signin2>div {
        width: 100% !important;
        height: auto !important;
        padding: 6px 8px;
        border-radius: 22px;
        position: relative;
    }

    span.abcRioButtonContents {
        font-size: 18px !important;
        font-weight: 500;
        color: #000;
        margin: 0;
    }

    .abcRioButtonIcon {
        position: absolute;
        left: 10px;
        top: 6px;
    }
.google{
    background-color: #ffffff;
    border-radius: 5px;
    padding: 15px 20px;
}
    .google img{
        padding-right: 10px;
    }
    .google span{
        color: #5d1688;
    }
</style>
@endsection

@section('content')
<aside class="lgbg">
    <div class="lgbg2">
        <div class="lgin1"><img src="images/logo_1.svg" /></div>
        @if ($errors->any())
        <div class="alert alert-danger w-75 mx-auto mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="lgin2">
{{--            <a href="{{route('guest.redirectToGoogle')}}">Login with Google</a>--}}
            <a class="google" href="{{route('guest.redirectToGoogle')}}">
                               <span class="content-svg">
                                 <img width="30" src="{{asset('images/google.png')}}" alt="">
                               </svg>
                               </span>
                <span>Login with Google</span>
            </a>
        </div>

        <div class="lgin3">
            <span>OR</span>
        </div>

        <div class="lgfrm">

            <form method="POST" action="{{ route('login') }}" autocomplete="off" class="lgFrm">
                <div class="lgIrow">
                    <input type="text" class="lginfld icnusr" name="email" placeholder="User name" />
                </div>

                <div class="lgIrow">
                    <input type="password" class="lginfld icnkey" id="pss" name="password" placeholder="Password" />
                    <span class="lgEye" onclick="togglePasswordView()">VIEW</span>
                </div>
                @csrf
                <div class="lgfrgt">
                    <a style="color:#66b8d9" href="{{ route('password.request') }}">
                        <span>Forgot your password?</span>
                    </a>
                </div>

                <div class="lgBtnBx">
                    <input class="lgbtn1" type="submit" value="Login">
                </div>
            </form>
        </div>

        <div class="lgBtnBx2">
            <a href="{{ route('register') }}">
                <span class="lgbtn2">Create an account</span>
            </a>
        </div>

    </div>
</aside>

<div class="loader" style="display:none"></div>
@endsection

@section('scripts')
<script>
    function togglePasswordView() {
        var x = document.getElementById("pss");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function onSignIn(googleUser) {
        var profile = googleUser.getBasicProfile();
        //   console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
        //   console.log('Name: ' + profile.getName());
        //   console.log('Image URL: ' + profile.getImageUrl());
        //   console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
        var form_html = '<form method="POST" action="' + base_url + 'submitSignup" id="hiddensgFrm">' +
            '<input type="hidden" name="name" value="' + profile.getName() + '">' +
            '<input type="hidden" name="email" value="' + profile.getEmail() + '">' +
            '<input type="hidden" name="social" value="1">' +
            '<input type="hidden" name="image" value="' + profile.getImageUrl() + '">' +

            '</form>';
        $('body').append(form_html);
        googleUser.disconnect();
        $('#hiddensgFrm').submit();
    }

    function lginFormSubmit() {
        console.log("firing this google login");
        showLoader();
        $('.lgFrm').submit()
    }

    $(document).ready(function () {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            console.log('User signed out.');
        });
    });

</script>
@endsection

