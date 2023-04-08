@extends('dashboard.layouts.master')
@section('content')
    <aside class="regbg cracc">
        <div class="regbg2">

            <div class="reg12">
                <div class="reg1"><img src="<?=$base_url?>/images/sign_bg.png"/></div>
                <div class="reg2"><img src="<?=$base_url?>/images/logo_2.svg"/></div>
            </div>

            <div class="lgfrm">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                      id="sgFrm">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="lgIrow">
                        <input type="text" class="lginfld icnusr" name="name" placeholder="Full name"/>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                    @csrf

                    <div class="lgIrow">
                        <input type="email" class="lginfld icneml" name="email" placeholder="Email"/>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>

                    <div class="lgIrow">
                        <input type="password" class="lginfld icnkey" id="pss1" name="password" placeholder="Password"/>
                        <span class="lgEye" onclick="togglePasswordView('pss1')">VIEW</span>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>

                    <div class="lgIrow">
                        <input type="password" class="lginfld icnkey" id="pss2" name="password_confirmation"
                               placeholder="Confirm Password"/>
                        <span class="lgEye" onclick="togglePasswordView('pss2')">VIEW</span>
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                        @enderror
                    </div>
                    <div class="lgIrow">
                        <input checked type="radio" value="0" id="set2" name="type">
                        <label for="set2" style="color: #FFF; padding-right: 50px; font-size: 18px"><span>Student</span> </label>

                        <input type="radio" value="1" id="set1" name="type">
                        <label for="set1" style="color: #FFF; font-size: 18px"><span>Teacher</span> </label>
                    </div>
                    <div class="rgBtnBx">
                        <input type="submit" class="lgbtn1" value="Create account">
                    </div>
            </div>

            <div class="rgtnBx2">
                already have an account? <a href="{{ route('login') }}"><span>LOGIN</span></a>
            </div>

        </div>
    </aside>
@endsection

@section('scripts')
    <script>
        function togglePasswordView(id) {
            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function submitSgFrm() {
            var pass1 = $('#pss1').val();
            var pass2 = $('#pss2').val();
            if (pass1 == pass2) {
                $('#sgFrm').submit();
            } else {
                swal("Error", "Passwords are not matching", "error");
            }
        }

    </script>

@endsection

