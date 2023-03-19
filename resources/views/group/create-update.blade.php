@extends('dashboard.layouts.master')

@section('css')
    <style>
      .deletable:checked ~ #deletebtn {
        display: inline-block;
        opacity: 1;
      }
    </style>
@endsection

@section('content')
    
<header class="clrhdr">
  <div class="leftIcn">
    <div class="backicn">BACK</div>
  </div>

    <div class="pgnme">
        Group settings  
    </div>

    <div class="rgtIcn">
        
    </div>
</header>

<form id="groupForm" enctype="multipart/form-data" method="POST" action="@if (Route::current()->named('groups.create')){{ route('groups.store') }}@elseif (Route::current()->named('groups.edit')){{ route('groups.update', ['group' => $group->id]) }}@endif" style="height: 100%">
  @if (Route::current()->named('groups.edit')) @method('PUT') @endif
<section class="grupPg" style="height: unset">
        <aside class="whtBx1">
            @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <div class="q1t1">Quest icon</div>
            <div class="q1t2" onclick="clickImg()"><img id="blah" src="{{ isset($group->image) ? Storage::url($group->image) : url('images/placeholder.jpeg') }}">
                
            </div>
            <input type="file" accept="image/*" class="group_img" name="group_img" onchange="readURL(this);" style="height:0; width:0; opacity:0"/>
            <input type="hidden" class="group_image" id="group_image_data" name="group_image_data" value="">
            <div class="inSet">
                <h6><span>Group title</span></h6>
                <input type="text" class="infld group_title" name="title" value="{{ $group->title ?? '' }}" placeholder="">
                <span class="notTxt">* Max length 20</span>
            </div>
            
    
            <div class="inSet">
                <h6><span>Group info</span></h6>
                <textarea name="description" class="infld group_info">{{ $group->description ?? '' }}</textarea>
            </div>
            
            <div class="inSet">
                <h6><span>Password</span></h6>
                <input name="password" type="text" class="infld group_pass" value="{{ $group->password ?? '' }}" placeholder="">
            </div>
    
            <div class="inSet">
                <h6><span>Tags</span> <span class="qinfo">?</span></h6>@csrf
                <input type="text" name="tags" class="infld group_tags" value="{{ $tags ?? '' }}" placeholder="separate tags by space (math addition)">
            </div>
    
            <div class="inSet"><div class="chkList">
                <input type="checkbox" id="private" name="private" @if(isset($group) && $group->private) checked @endif> 
                <label for="private"><span>Keep group private</span> <span class="qinfo">?</span></label> 
            </div></div>
        </aside>
        <aside class="gryBxGQ" style="padding: 20px 10px">
          <div class="gqHdline" style="font-weight: 500">Group Quests</div>
        </aside>
        <div class="accordion" id="accordionExamLists">
            @if (Route::current()->named('groups.edit'))
              <div class="card current_group_exams" style="margin:0px 10px;">
                <div class="card-header bg-success" id="headingOne">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-center text-white font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Existing
                    </button>
                  </h2>
                </div>
              
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExamLists">
                  <div class="card-body" id="existing">
                      <ul class="gqList userSelectGrp" id="existingList">
                          @foreach ($exams as $exam)
                              <li class="xmrm_{{ $exam->id }}">
                                  <input type="checkbox" class="xm_list deletable" id="existing_xmlst_{{ $exam->id }}" name="exams[]" value="{{ $exam->id }}">
                                  <label for="existing_xmlst_{{ $exam->id }}" style="margin-bottom: 0% !important">
                                      <div class="lblIn">
                                          <div class="gqlImg">
                                              <img src="{{ isset($exam->icon) ? Storage::url($exam->icon) : url('images/placeholder.jpeg') }}"/>
                                          </div>
                                          <div class="gqlT1">CODE: <b>{{ $exam->id + 1000 }}</b></div>
                                          <div class="gqlT2">{{ $exam->title ?? 'untitled' }}</div>
                                      </div>
                                  </label>
                                  <span class="gqlDlt " onclick="changeList('#deletedList', '#existing_xmlst_{{ $exam->id }}')">X</span>
                              </li>
                          @endforeach
                        </ul>
                  </div>
                </div>
              </div>
            @endif
            <div class="card new_exams" style="margin:0px 10px;">
              <div class="card-header" id="headingTwo" style="background-color: dodgerblue;">
                <h2 class="mb-0">
                  <button class="btn btn-block text-center text-white font-weight-bold collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Newly Added
                  </button>
                </h2>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExamLists">
                <div class="card-body" id="newlyadded">
                    <ul id="newlyaddedList" class="gqList"></ul>
                </div>
              </div>
            </div>
            <div class="card deleted_exams" style="margin:0px 10px; margin-bottom:10px;" >
              <div class="card-header bg-danger" id="headingThree">
                <h2 class="mb-0">
                  <button class="btn btn-block text-center text-white font-weight-bold collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Deleted
                  </button>
                </h2>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExamLists">
                <div class="card-body" id="deleted">
                    <ul id="deletedList" class="gqList"></ul>
                </div>
              </div>
            </div>
          </div>
        
        <input type="hidden" class="exam_list_val"> 
 
</section>   
<div class="grpBtnBar">
    <a class="btnvio" href="" onclick="javascript: window.history.back()">CANCEL</a>
    <input style="border:none" class="btnpnk" type="submit" value="DONE">
</div>
</form>

<div id="deletebtn" onclick="changeList('#deletedList', '.deletable')" style="display:none; opacity: 0; transition: opacity 300ms ease-in-out;
right: 100px;background: unset;background-color: blueviolet;border-radius: 20px;width: 100px;text-align: center;font-size: 14px;color: azure;height: 55px;padding-top: 6px;font-weight: 700;" class="roundBtn3" onclick="">
    delete selected
  </div>

<div class="roundBtn3" id="grpStOpn" onclick="showGroupExamPop()">PLUS BUTTON</div>

<!-- Group Popup -->
<div class="prgPopBx" id="grpStPop" style="display: none;">
    <div class="clrhdr2">
        <div class="leftIcn"></div>
        
        <div class="pgnme">
            Exams available
        </div>
        
        <div class="rgtIcn"></div>
    </div>

    <div class="grStnsBx" style="overflow: auto;">
        <ul class="gqList all_exams">
            @foreach ($user_exams as $user_exam)
                <li>
                    <input type="checkbox" class="xm_list" id="xmlst_{{ $user_exam->id }}" value="{{ $user_exam->id }}"/>
                    <label for="xmlst_{{ $user_exam->id }}" style="margin-bottom: 0% !important">
                        <div class="lblIn">
                            <div class="gqlImg">
                                <img src="{{ isset($user_exam->icon) ? Storage::url($user_exam->icon) : url('images/placeholder.jpeg') }}"/>
                            </div>
                            <div class="gqlT1">CODE: <b>{{ $user_exam->id + 1000 }}</b></div>
                            <div class="gqlT2">{{ $user_exam->title }}</div>
                        </div>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>    
<input type="hidden" class="group_id" value="{{ $group->id ?? '' }}">
    <div class="tblPbtnBr">
        <div id="" class="canBtn2 pcancel" onclick="closeGrpExam()">Cancel</div>
        <div class="donBtn2" onclick="changeList('#newlyaddedList', '.gqList.all_exams .xm_list')">Add</div>
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


<div class="loader" style="display:none"></div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset("js/group_funcs.js") }}"></script>
<script>
    $('#groupForm').validate({
        ignore: [],
        rules: {
          title: {
            required: true,
            minlength: 2,
            maxlength: 20
          },
          group_img: {
            accept: "image/*",
            maxsize: 3000000,
          },
        },
        messages: {
          title: {
            required: "Group name is required",
            minlength: "the minimum length for group name is 2 characters",
            maxlength: "the maximum length for group name is 20 characters"
          },
          group_img: {
            accept: "Only images are accepted"
          },
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
          $('input[type="checkbox"][name$="exams[]"]').prop("checked", true);
          form.submit();
        },
      });
</script>
@endsection
