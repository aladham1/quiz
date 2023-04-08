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
            background: url( '{{ url('/images/audio.svg') }}' ) no-repeat center center #66b8d9;
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
            {{ $project->exam->title }}
        </div>

        <div class="rgtIcn">

        </div>
    </header>


    <section class="pge8">
        <div class="q1t2 mt-2" >
            <img id="blah" style="border-radius: 50%; border: 3px solid #1f5373" src="{{ isset($project->student->avatar) ? Storage::url($project->student->avatar) : url('images/prf.png') }}">
        </div>
        <h6 class="text-center my-3">Submitter: {{ $project->student->name }}</h6>
        <aside class="whtBx1">
            <div class="q1t1">Submission details</div>
                @php
                    $project->image = json_decode($project->image, true) ?? $project->image;
                    $project->video = json_decode($project->video, true) ?? $project->video;
                    $project->audio = json_decode($project->audio, true) ?? $project->audio;
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

                @elseif (isset($project->video))
                    @if( is_array($project->video))
	        			@foreach ($project->video as $video)
                            <div class="qsimg1" style="height: 190px;">
                                <iframe src="https://www.youtube.com/embed/{{ $video['video_id'] ?? $video }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='{{ $video['width'] ?? '100%' }}' height='{{ $video['height'] ?? '' }}' allowfullscreen></iframe>
                                <br>
                            </div>

	        			@endforeach
	        		@else
                        <div class="qsimg1" style="height: 190px;">
                            <iframe src="https://www.youtube.com/embed/{{ $project->video['video_id'] ?? $project->video }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='{{ $project->video['width'] ?? '100%' }}' height='{{ $project->video['height'] ?? '' }}' allowfullscreen></iframe>
                            <br>
                        </div>

                    @endif

                @elseif (isset($project->audio))
                    @if( is_array($project->audio))
	        		    @foreach ($project->audio as $track)
	        		    	<div class="qsimg1" style="height: 50px;">
	        		    		<audio controls>
	        		    			<source src="{{ Storage::url($track) }}">
	        		    			Your browser does not support the audio element.
	        		    		</audio>
	        		    	</div>
                        @endforeach
                    @else
                        <div class="qsimg1" style="height: 50px;">
                            <audio controls>
                                <source src="{{ Storage::url($project->audio) }}">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    @endif
	        	@endif
            </div>

            <div class="audtxt">{!! $project->description !!}</div>

        </aside>

        <aside class="chkSet">

            <div class="inSet">
                <h6><span>Comments </span></h6>
                <textarea type="text" class="infld project_submission_notes">{!! $project->remark_notes ?? '' !!}</textarea>
            </div>

        </aside>

    </section>

    <div class="tblPbtnBr">
        @if($project->remark_== 2 || $project->remark == null)
            <div id="" class="pfbtn pfBtnf" onclick="markSubmission(0, '{{ route('exams.project_submits.update', ['exam' => $project->exam->id, 'project_submit' => $project->id]) }}', true)">Fail</div>
            <div class="pfbtn pfBtnp" onclick="markSubmission(1, '{{ route('exams.project_submits.update', ['exam' => $project->exam->id, 'project_submit' => $project->id]) }}', true)">Pass</div>
        @elseif($project->remark==1)
            <div class="pfbtn pfBtnp w-100" onclick="markSubmission(1, '{{ route('exams.project_submits.update', ['exam' => $project->exam->id, 'project_submit' => $project->id]) }}', true)">Passed (Click to add any new comments)</div>
        @elseif($project->remark==0)
            <div id="" class="pfbtn pfBtnf w-100" onclick="markSubmission(0, '{{ route('exams.project_submits.update', ['exam' => $project->exam->id, 'project_submit' => $project->id]) }}', true)">Failed (Click to add any new comments)</div>
        @endif
        </div>

    <div id="mask2" class="mask"  style="display:none; z-index:9999999" onclick="popp1close()"></div>


    <div class="loader" style="display:none"></div>
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

@endsection
