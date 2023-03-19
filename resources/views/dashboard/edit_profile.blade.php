@extends('dashboard.layouts.master')

@section('css')
    <style>
        .qsTxt5 img {
             width: 20px; 
        }
        .grpTxt6 img{
            width: 20px;  
        }
    </style>
@endsection

@section('content')
<header class="clrhdr">
    <div class="leftIcn" style="cursor: pointer;" onclick="window.history.back()">
        <div class="backicn">BACK</div>
    </div>

    <div class="pgnme">
        Edit Profile
    </div>

</header>

<section class="pageBody"> 
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
            style="border-radius: 50%; border: 3px solid #511285" 
            src="{{ isset(auth()->user()->avatar) ? Storage::url(auth()->user()->avatar) : url('images/prf.png') }}">
        </div>
        <form id="usrForm" enctype="multipart/form-data" method="POST" action="{{ route('profile.update') }}" style="height: 100% !important">
            @csrf
            <input type="file" accept="image/*" class="usr_img" id="usr_img" onchange="OpenCroperPop('profile_pic_edit',this.files[0])" style="height:0; width:0; opacity:0"/>
            <input type="hidden" class="profile_img" id="user_image_data" name="user_image_data" value="">
            <div class="inSet">
                <h6><span>Name</span></h6>
                <input type="text" class="infld" style="border-radius: 20px" name="user_name" value="{{ auth()->user()->name ?? '' }}" placeholder="">
            </div>
            <div class="inSet">
                <h6><span>Email</span></h6>
                <input type="text" class="infld" style="border-radius: 20px; background-color: #CCCCCC" disabled name="email" value="{{ auth()->user()->email ?? '' }}" placeholder="">
            </div>
            <div class="inSet">
                <h6><span>Phone number</span></h6>
                <input type="text" class="infld" style="border-radius: 20px" name="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="">
            </div>
        </form>
    </div>
</section>

<div class="tblPbtnBr">
    <div id="" class="canBtn2 pcancel" onclick="window.history.back()">Cancel</div>
    <div class="donBtn2">
        <button style="cursor: pointer; background: #F232A4; border: unset; width: 100%; border-radius: 0; color: #fff; height: 50px; text-align: center; font-size:18px; font-weight: bold; line-height: 48px;"
         onclick="document.getElementById('usrForm').submit();">Save</button>
    </div>
</div> 

<!-- Cropper Image Popup -->
<div class="imgPopBx cropperJsPop" id="" style="display: none;">
<div class="clrhdr2">
    <div class="leftIcn">
        <div class="backicn pcancel">BACK</div>
    </div>

    <div class="pgnme">
        Select Image
    </div>

    <div class="rgtIcn">
        
    </div>
</div>

<div class="imgbx">
    <img class="cropperJs" id="crpImg" src=""/>
    <canvas id="canvasArea">
</div>    

<div class="tblPbtnBr">
    <div id="" class="canBtn2 pcancel" onclick="javascript: $('.cropperJsPop').hide(); cropper.destroy();">Cancel</div>
    <div class="donBtn2 cropDImage" onclick="cropImage()">Done</div>
</div>    
</div> 
@endsection