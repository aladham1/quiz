@extends('dashboard.layouts.master')

@php
    $guest_prefix = ''; //Auth::check() ? '' : 'guest.';
    $total_submitted_projects = $exam->project_submits->count();
    $successful_projects = $exam->project_submits->where('remark', true)->count();
@endphp

@section('content')
    <section class="resBg">
        <div class="resWd">
            @if ($pass)
                <div class="resBx1">
                    <img src="{{ url('images/tr_1.svg') }}"/>
                </div>
                <div class="resBx2">!أحسنت</div>
            @else
                <div class="resBx1">
                    <img src="{{ url('images/tr_2.svg') }}"/>
                </div>
                <div class="resBx2">حاول مره اخرى!</div>
            @endif

            <div class="resBx3">%{{ $percentage }}</div>

            <div class="resBx4">
                <div style="width:{{ $percentage }}%" class="resBx4in"></div>
            </div>
            <div class="resBx5">{{ $exam->title }}</div>

            @if($pass && count($exam->project) > 0 )
                <div class="resBx6">
                    <a href="{{ route($guest_prefix . 'exams.project_submits.create', ['exam' => $exam->id]) }}" style="color:white">
                        <div class="resBtn1 prjtBtn" style="background:#F784C8">
                            <span class="icnP"></span>
                                @if($total_submitted_projects > 0) Re-submit Project @else Submit Project @endif
                        </div>
                    </a>
                </div>
            @endif
            <div class="resBx7">
                <div class="bttnBlue @if( $pass || $exam->retake==0 ) bttnof @endif "> <!-- for gray color "bttnof" -->
                    @if( $pass || $exam->retake==0 )
                        <span class="btn7i"><img src="{{ url('images/retexam.svg') }}"/></span>
                        <span class="btn7t">RETAKE EXAM</span>
                    @else
                        <a class="prjBtn" href="{{ route($guest_prefix.'exams.intro', ['exam' => $exam->id]) }}">
                            <span class="btn7i"><img src="{{ url('images/retexam.svg') }}"/></span>
                            <span class="btn7t">RETAKE EXAM</span>
                        </a>
                    @endif
                </div>
                <div @if($pass && (count($exam->project) == 0 || $successful_projects > 0)) class="bttnyel" onclick="showReward()"
                    @else class="bttnyel bttnof" onclick="" @endif><!-- for gray color "bttnof" -->
                    <span class="btn7i"><img src="{{ url('images/reward.svg') }}"/></span>
                    <span class="btn7t">REWARD</span>
                </div>
            </div>

            <div class="resBx8">
                @if($exam->review_type !=0 )
                    <div class="bttnWh bttnWh1" >
                        <a href="{{ route($guest_prefix . 'exams.analyze', ['exam' => $exam->id, 'attempt' => $attempt]) }}">
                            <span class="btn8i"><img src="{{ url('images/b81.svg') }}"/></span>
                            <span class="btn8t">ANALYSE EXAM</span>
                        </a>
                    </div>
                @else
                    <div class="bttnWh bttnWh1" style="background: #9B9B9B; border-bottom: 5px solid #838383; color:white">
                        <span class="btn8i" style="opacity:0.5"><img src="{{ url('images/b81-w.svg') }}"/></span>
                        <span class="btn8t" style="color:white; opacity:0.5">ANALYSE EXAM</span>
                    </div>
                @endif

                <div class="bttnWh bttnWh2">
                    <a href="{{ route('home') }}">
                        <span class="btn8i"><img src="{{ url('images/b82.svg') }}"/></span>
                        <span class="btn8t">BACK HOME</span>
                    </a>
                </div>
                <div class="bttnWh bttnWh3" onclick="shareData('{{ $exam->title }}','{{ $exam->title }} - {{ $exam->owner->name }}', '{{ route('exams.intro', ['exam' => $exam->id]) /*route('guest.exams.intro', ['exam' => $exam->id])*/ }}')">
                    <span class="btn8i"><img src="{{ url('images/b83.svg') }}"/></span>
                    <span class="btn8t">SHARE EXAM</span>
                </div>
            </div>

        </div>
    </section>

    @if($pass && (count($exam->project) == 0 || $successful_projects > 0))
        <div class="rewMsk" style="display:none"></div>
        <div class="rewPop" style="display:none">
            <div class="rpBx1">
                <div class="rpBx2">
                    <img class="re_img_1" src="{{ url('images/rew1.svg') }}">
                    <!-- [1] rew1.svg || [2] rew2.svg -->
                </div>
                <div class="rpBx3">
                    <div class="rpBx3T1">
                        <img class="re_img_2" src="{{ url('images/rew_txt1.svg') }}">
                        <!-- [1] rew_txt1.svg || [2] rew_txt2.svg -->
                    </div>
                    <div class="rpBx3T2">
                        <!-- ALL New Content Here -->

                        @if ($exam->reward_type == 0)
                            Bluetooth Coupon not supported for web
                        @elseif($exam->reward_type == 1)
                            {{ $exam->reward_message }}
                        @elseif($exam->reward_type == 2)
                            <img src="{{ Storage::url($exam->reward_image) }}" />
                        @elseif($exam->reward_type == 3)
                            <?php $service = new App\Services\MyServices() ?>
                            <iframe style="max-width: 100%"  width="100%" src="https://www.youtube.com/embed/{{ $service->getVideoId($exam->reward_video) }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0"></iframe>
                        @elseif($exam->reward_type == 4)
                            @auth
                                <div @if($exam->cert_lang == 'en' || $exam->cert_lang == 'english')
                                     class="certificate_en"
                                     @elseif($exam->cert_lang == 'ar' || $exam->cert_lang == 'arabic')
                                     class="certificate_ab" @endif onclick="print_cert({{ $exam->id }})">
                                    <!--certificate start-->
                                        <div class="srtfVew " id="printFrist" style="background: #fff; padding: 15px;">
                                            <div class="crt1" style="border: 1px solid #511285; padding: 15px;">
                                                <div class="crt2" style="text-align: center; position: relative;">

                                                    <div class="spnrLgo sponsor_img" style="float: left; width: 100%">
                                                    @if($exam->sponser)
                                                        <img src="{{ Storage::url($exam->sponser) }}">
                                                    @endif
                                                    </div>
                                                    <div class="crLne1 " style="margin: 0 auto 20px; width: 25%; float: right">
{{--                                                        <img src="{{ url('images/logo5.svg') }}" style="max-width: 100%;">--}}
                                                    </div>
                                                    @if($exam->cert_lang == 'en' || $exam->cert_lang == 'english')
                                                      <!--English-->
                                                        <div class="crLne2" >CERTIFICATE</div>
                                                        <div class="crLne3" style="color: #6422A1; font-size: 18px; margin: 0 0 20px 0;">OF ACHIEVEMENT</div>
                                                        <div class="crLne4" style="color: #707070; font-size: 16px;">
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">This is to certify that</div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                <div class="crLne42" style="    display: inline-block; font-style: italic;"><span class="cert_student_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">{{ Auth::user()->name }}</span></div>
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                has passed an electronic exam on Questanya platform, titled with
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_exam_name" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">{{ $exam->title }}</span></div>
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                on<div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_date" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">{{ date('d-m-Y') }}</span></div>@ <div class="crLne42" style="display: inline-block; font-style: italic;"><span class="cert_xm_time" style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);">{{ date('h:i:s A') }}</span></div>
                                                            </div>
                                                            <div class="crLne41" style="padding: 0 0 12px 0;">
                                                                Wishing them further success and excellence
                                                            </div>
                                                        </div>
                                                        <div class="crLne60" style="text-align: left;">
                                                            <div class="crLne6" style="font-size: 12px; text-align: center; min-width: 120px; display: inline-block;">
                                                                <div class="crLne7" style="border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><span class="cert_xm_maker" style="font-weight: bold; padding: 0 2px; font-style: italic; color: #707070" >{{ $exam->owner->name }}</span></div>
                                                                <div class="crLne8" style="color:#707070">Exam Maker</div>
                                                            </div>
                                                        </div>
                                                    @elseif($exam->cert_lang == 'ar' || $exam->cert_lang == 'arabic')
                                                      <!--Arabic-->
        		                		                <div class="crLne2">شهــــــــــــادة</div>
        		                		                <div class="crLne3">إنجـــــــاز</div>
        		                		                <div class="crLne4">
        		                		                	<div class="crLne41">هذه الشهادة تؤكد اجتياز</div>
        		                		                	<div class="crLne41">
        		                		                		<div class="crLne42"><span class="cert_student_name" style="font-weight: bold;">{{ Auth::user()->name }}</span></div>
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		للاختبار الإلكتروني على منصة كويستانيا والذي بعنوان
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		<div class="crLne42"><span class="cert_exam_name" style="font-weight: bold;">{{ $exam->title }}</span></div>
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		<div class="crLne42"><span class="cert_xm_date" style="font-weight: bold;">{{ date('d-m-Y') }}</span></div>@ <div class="crLne42"><span class="cert_xm_time" style="font-weight: bold;">{{ date('h:i:s A') }}</span></div> في يوم
        		                		                	</div>
        		                		                	<div class="crLne41">
        		                		                		متمنين لهم دوام النجاح والتميز
        		                		                	</div>
        		                		                </div>

        		                		                <div class="crLne60">
        		                		                	<div class="crLne6">
        		                		                		<div class="crLne7"><span class="cert_xm_maker" style="font-weight: bold; color:#707070">{{ $exam->owner->name }}</span></div>
        		                		                		<div class="crLne8" style="color:#707070">صانع الاختبار
        		                		                		</div>
        		                		                	</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    <!--certificate end-->
                                </div>
                            @endauth
                            @guest
                                <h3>
                                    In order to see your certificate, you have to be a memeber of Questanya
                                </h3>
                            @endguest
                        @endif
                    </div>
                </div>
                <div class="rpBx4" onclick="hideReBox()">
                    <img class="re_img_3" src="{{ url('images/rew_bttn1.svg') }}">
                    <!-- [1] rew_bttn1.svg || [2] rew_bttn2.svg -->
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            var i=0;
                var pbtn = setInterval(function(){
                if(i%2==0){
                    $('.prjtBtn').animate({backgroundColor: '#F232A4'}, 'slow'); console.log("doing");
                    //$('.prjtBtn a').css("color","#fff !important");
                    $('.re_img_1').attr("src","{{ url('images/rew2.svg') }}");
                    $('.re_img_2').attr("src","{{ url('images/rew_txt2.svg') }}");
                    $('.re_img_3').attr("src","{{ url('images/rew_bttn2.svg') }}");

                }else{
                    $('.prjtBtn').animate({backgroundColor: '#F784C8'}, 'slow');
                    //$('.prjtBtn a').css("color","#F232A4 !important");
                    $('.re_img_1').attr("src","{{ url('images/rew1.svg') }}");
                    $('.re_img_2').attr("src","{{ url('images/rew_txt1.svg') }}");
                    $('.re_img_3').attr("src","{{ url('images/rew_bttn1.svg') }}");
                }
                i++;
            },1000);

        })

        function showReward(){
            $( ".rewPop" ).fadeIn();
            $( ".rewMsk" ).fadeIn();
        }
        function hideReBox(){
            $( ".rewPop" ).fadeOut();
            $( ".rewMsk" ).fadeOut();
        }

        function print_cert(licens_id){
            var win = window.open('http://127.0.0.1:8000/printReward/'+licens_id+'/printReward', '_blank');
        }
    </script>
@endsection
