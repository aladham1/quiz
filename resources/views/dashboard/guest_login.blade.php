@php
    $exam_fields = explode(',', $exam->login_fields);
@endphp

@extends('dashboard.layouts.master')

@section('content')
<header class="clrhdr">
    <div class="leftIcn">
    </div>

    <div class="pgnme pt-0">
        <img height="45"
        src="{{ asset('images/logo.png') }}">
    </div>

    <div class="rgtIcn">
        
    </div>
</header>

<section class="pageBody" style="height: calc(100% - 50px);"> 
    <div style="margin: auto; max-width: 600px !important">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="q1t2" style="background-color: transparent" onclick="$('#usr_img').trigger('click');">
            <img id="blah" class="profile_pic_edit"
            src="{{ isset($exam->icon) ? Storage::url($exam->icon, true) : url('images/placeholder.jpeg') }}">
        </div>
        <h5 class="text-center my-4">
            {{ $exam->title }}
        </h5>
        <form id="usrForm" enctype="multipart/form-data" method="POST" action="{{ request()->fullUrl() }}" style="height: 100% !important">
            @csrf
            @foreach ($exam_fields as $field)
                <div class="inSet">
                    <h6 style="font-size: 21px;"><span>{{ $field }}</span></h6>
                    <input type="text" class="infld" style="border-radius: 20px" name="guest_fields[]" value="" placeholder="{{ $field }}" required>
                    <div class="invalid-feedback">the field is required</div>
                </div>
            @endforeach
            <div class="text-center">
                <button style="cursor: pointer; background: #5939C6; border: unset; border-radius: 20px; color: #fff; height: 50px; text-align: center; font-size:18px; width: 250px; font-weight: bold; line-height: 48px; filter:drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.25));"
                onclick="document.getElementById('usrForm').submit();">ابدأ التحدى</button>
            </div>
        </form>
    </div>
</section>
@endsection
@section('scripts')
<script>
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
</script>    
@endsection