@extends('dashboard.layouts.master')

@section('css')
    <link href="{{asset('css/cert_style.css')}}" rel="stylesheet" type="text/css" />
    <link href="css/cert_style2.css" rel="stylesheet" type="text/css" />
    <style>
        @page { size: auto;  margin: 0mm; }
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }

    </style>
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>
    @endsection
@section('content')
    @auth
       <div style="width: 80%; margin: auto">
           <div @if($reward_data['cert_lang'] == 'en' || $reward_data['cert_lang'] == 'english')
                    class="certificate_en"
                @elseif($reward_data['cert_lang'] == 'ar' || $reward_data['cert_lang'] == 'arabic')
                    class="certificate_ab" @endif >
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
       </div>
    @endauth
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            window.print()
        })
    </script>
@endsection
