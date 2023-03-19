@extends('dashboard.layouts.master')

@php
    $guest_prefix = Auth::check() ? '' : 'guest.';
@endphp

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.7/cropper.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.7/cropper.js"></script>

<style>
    .imgPopBx {    position: fixed;
    top: 0;
    bottom: 0;
    width: 100%;
    z-index: 9999999;
    background: #000;
    overflow-y: auto;
    padding: 50px 0;}
    .imgPopBx .imgbx {height:100%; text-align: center;}
    .imgPopBx .imgbx img {max-width:100%;transform: translateY(-50%);
        position: relative;
    }
    .audicn_btn {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: url( '{{ url('/images/audio.svg') }}' ) no-repeat center center #f232a4;
            background-size: auto auto;
            background-size: 22px;
            border-radius: 50%;
            transition: all ease-in-out 0.05s;
        }
</style>
@endsection

@section('content')
    
    <header class="clrhdr">
        <div class="leftIcn">

        </div>

        <div class="pgnme">
            SUBMIT PROJECT
        </div>

        <div class="rgtIcn">

        </div>
    </header>


    <section class="pge8">

        <aside class="whtBx1">
            <div class="q1t1">Project details</div>
            @php
                $project->image = json_decode($project->image, true) ?? $project->image;
                $project->video = json_decode($project->video, true) ?? $project->video;
                $project->audio = json_decode($project->audio, true) ?? $project->audio;
                $project->file = json_decode($project->file, true) ?? $project->file;
            @endphp
            <div class="audibx" style="text-align: center">
                @if (isset($project->image))
	        		@if( is_array($project->image))
	        			@foreach ($project->image as $img)
	        				<img style="height:100px" src="{{ Storage::url($img) }}"/>
	        			@endforeach
                        
	        		@else
	        			<img style="height:100px" src="{{ Storage::url($project->image) }}"/>
                    @endif
                @endif
                <hr>
                @if (isset($project->video))
                    @if( is_array($project->video))
	        			@foreach ($project->video as $video)
                            <div class="qsimg1">
                                <iframe src="https://www.youtube.com/embed/{{ $video['video_id'] ?? $video }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='{{ $video['width'] ?? '100%' }}' height='{{ $video['height'] ?? '' }}' allowfullscreen></iframe>
                                <br>
                            </div>
                    
	        			@endforeach
	        		@else
                        <div class="qsimg1">
                            <iframe src="https://www.youtube.com/embed/{{ $project->video['video_id'] ?? $project->video }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='{{ $project->video['width'] ?? '100%' }}' height='{{ $project->video['height'] ?? '' }}' allowfullscreen></iframe>
                            <br>
                        </div>
                
                    @endif
                @endif
                <hr>
                @if (isset($project->audio))
                    @if( is_array($project->audio))
	        		    @foreach ($project->audio as $track)
	        		    	<div class="qsimg1">
	        		    		<audio controls>
	        		    			<source src="{{ Storage::url($track) }}">
	        		    			Your browser does not support the audio element.
	        		    		</audio>
	        		    	</div>
                        @endforeach
                    @else
                        <div class="qsimg1">
                            <audio controls>
                                <source src="{{ Storage::url($project->audio) }}">
                                Your browser does not support the audio element.
                            </audio>
                        </div>    
                    @endif
	        	@endif
                <hr>
                @if (isset($project->file))
                    @if( is_array($project->file))
	        		    @foreach ($project->file as $file)
	        		    	<div class="qsimg1">
	        		    		<a href="{{ Storage::url($file) }}" download>Download file</a>
	        		    	</div>
                        @endforeach
                    @else
                        <div class="qsimg1">
                            <a href="{{ Storage::url($project->file) }}" download>Download file</a>
                        </div>    
                    @endif
	        	@endif
                
            </div>
            <hr>
            <div class="audtxt">{!! $project->description !!}</div>

        </aside>

        <aside class="chkSet">

            <div class="inSet">
                <h6><span>Project description </span></h6>
                <textarea type="text" class="infld project_text" id="editor"></textarea>
            </div>

            <div class="icnLstbx">
                <ul class="pIcnLst" style="display: flex; justify-content: center;">
                   <input type="file" accept="image/*" style="height:0; width:0; opacity:0" class="projectImg">
                   <li onclick="openIntroPop('projects_image')">
                        <aside class="imgBtn" >
                            <img src="{{ url('images/p_img.svg') }}">
                            <h6>Image</h6>
                        </aside>

                    </li>
                    
                    <li id="vdoPopB1" onclick="openIntroPop('projects_video')">
                        <aside class="vdoBtn" >
                            <img src="{{ url('images/p_video.svg') }}">
                            <h6>Video</h6>
                        </aside>

                    </li>
                    
                    <li id="audioPB1" onclick="openIntroPop('projects_audio')">
                        <aside class="adoBtn">
                            <img src="{{ url('images/p_audio.svg') }}">
                            <h6>Audio</h6>
                        </aside>
                        
                    </li>
                
                </ul>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="q1t2 project_data_img p-3 mt-3" style="display:none;" ></div>
                </div>
                <div class="col-lg-12">
                    <div class="q1t2 project_data_audio p-3 mt-3" style="display:none; width: 100%; height: 54px; text-align: center" ></div>
                </div>
                <div class="col-lg-12">
                    <div class="q1t2 project_data_video p-3 mt-3" style="display:none; width: 100%; height: 155px; text-align: center" ></div>
                </div>
            </div>

            <input type="hidden" class="licens_id" value=" /*NOTE: here should be exams attempts count*/">
        </aside>

    </section>

    <div class="tblPbtnBr">
        <div id="" class="canBtn2 pcancel" onclick="javascript: window.history.back()">Cancel</div>
        <div class="donBtn2" onclick="submitProjectByStudent('{{ route($guest_prefix . 'exams.project_submits.store', ['exam' =>$exam->id]) }}', {{ session('no_questions', 'false') }}, '{{ route($guest_prefix . 'exams.intro', ['exam' => $exam->id]) }}')">Submit</div>
    </div>

    <div id="mask2" class="mask"  style="display:none; z-index:9999999" onclick="popp1close()"></div>
    <!-- video popup -->
    <div class="txtFldPop" id="vdoPop" style="display: none; z-index:99999999">
        <div class="tfpIn">
            <div class="tfpBx1">
                <div class="inSet">
                    <h6><span>Youtube Link</span></h6>
                    <input type="text" class="infld intro_video" placeholder="">
                </div>
            </div>
            <div class="tfpBx2">
                <div id="" class="canBtn pcancel">Cancel</div>
                <div class="donBtn video_svbtn" onclick="saveDataPopIntro('video')">Done</div>
            </div>
        </div>
    </div>

     <!-- Audio popup -->
    <div class="txtFldPop" id="audPop" style="display: none;  z-index:99999999">
        <div class="tfpIn">
            <div class="tfpBx1">
                <div class="audioSet" id="hide_on_audio_record">
                    <div id="hide_on_audio_upload">
                        <span class="audicn_btn" onclick="$('#hide_on_audio_record').hide();$('.audioSet.record').show();"></span>
                        <p class="my-1">OR</p>
                    </div>
                    <button class="btn font-weight-bold" style="background-color: #F232A4; color: white;" role="button" onclick="$('#audio_file_upload').trigger('click')"><i class="fas fa-upload"></i> Upload</button>
                    <input type="file" accept="audio/*" style="display: none" id="audio_file_upload" 
                    onchange="recorded_audio = this.files[0]; $('#hide_on_audio_upload').hide(); $('#audio_file_name').text(this.files[0].name);">
                    <p><b id="audio_file_name"></b></p>
                </div>
                <div class="audioSet record" style="display: none">
                    <h6 class="aud_timer">00 : 00</h6>
                    <span class="audIcnBtn" onclick="startRecording()"></span>
                    <ol id="recordingsList"></ol>
                </div>
            </div>
            <div class="tfpBx2">
                <div id="" class="canBtn pcancel" onclick="reset_audio_panel()">Cancel</div>
                <div class="donBtn audio_svbtn" onclick="saveDataPopIntro('audio')">Done</div>
            </div>
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
            <img class="cropperJs" id="crpImg" src="{{ url('images/1.png') }}"/>
            <canvas id="canvasArea"  >
        </div>    

        <div class="tblPbtnBr">
            <div id="" class="canBtn2 pcancel" onclick="javascript: $('.cropperJsPop').hide();">Cancel</div>
            <div class="donBtn2 cropDImage" onclick="cropImageEdit()">Done</div>
        </div>    
    </div> 

    <div class="loader" style="display:none"></div>
@endsection

@section('pkgs')

<script src="{{ asset("ckeditor/ckeditor.js") }}"></script>
    <script>
        var offlineDBname = "project_submit";
        
    </script>
@endsection

@section('scripts')
    <script>
        $(".projectImg").change(function(){
            //upload image intro draft

            readURL(this);
        });

        function readURL(input) { 
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    OpenCroperPop('project_submit',e.target.result);
                    $('.cropDImage').attr("onclick","cropImage('project_submit')"); 
                    return false;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
      
    </script>

    <script>
        var submission_editor;
        //CKEDITOR.replace('editor2');
        //CKEDITOR.replace('editor3');
        
            //submission_editor.config.baseFloatZIndex = 99999999;
        submission_editor = CKEDITOR.replace('editor');     
            
            /*
                .create( document.querySelector( '#editor' ),{
                
                    toolbar: {
    					items: [
    						'heading',
    						'|',
                            'alignment',
    						'bold',
    						'italic',
    						'link',
    						'bulletedList',
    						'numberedList',
    						'|',
    						'indent',
    						'outdent',
    						'|',
    						'imageUpload',
    						'blockQuote',
    						'insertTable',
    						'mediaEmbed',
    						'undo',
                            'redo'
    					]
    				},
    				language: ['en','ar'],
    				image: {
    					toolbar: [
    						'imageTextAlternative',
    						'imageStyle:full',
    						'imageStyle:side'
    					]
    				},
    				table: {
    					contentToolbar: [
    						'tableColumn',
    						'tableRow',
    						'mergeTableCells'
    					]
    				},
                    ckfinder: {
                        uploadUrl: base_url+'includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
                    }
                } )
                .then( editor => {
                    myEditor = editor;
                } )
                .catch( error => {
                        console.error( error );
                } );
            */
    </script>
@endsection
