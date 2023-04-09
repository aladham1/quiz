@extends('dashboard.layouts.master')
@section('css')
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
    <style>
        .swiper-wrapper,
        .swiper-slide {
            height: auto;
        }

        .swiper-container {
            height: 100%;
        }

        .swal-overlay--show-modal {
            z-index: 9999999;
        }

        .project_title {
            line-height: 25px;
            height: 250px !important;
        }

        .rew_text {
            line-height: 25px;

        }

        html, body {
            height: 100% !important;
        }

        .audicn_btn {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: url({{ url('/images/audio.svg') }}) no-repeat center center #f232a4;
            background-size: auto auto;
            background-size: 22px;
            border-radius: 50%;
            transition: all ease-in-out 0.05s;
        }
    </style>
    <style>
        .pushed {
            transition: margin-left 200ms ease 100ms;
            height: 0px;
            visibility: hidden;
        }

        .pulled {
            transition: margin-left 200ms ease 100ms;
            height: 0px;
            visibility: hidden;
        }

        .trns {
            transition: left 400ms ease, height 20ms ease;
        }

        .relaxed {
            transition: margin-left 200ms ease 100ms;
            height: 100%;
        }

        .neutral-bg {
            min-height: 100px;
            background-color: #c3c3c3;
            border: 1px solid #b6b6b6;
            border-radius: 4px;
        }

        .no-bg {
            background-color: none;
            border: none;
        }

        @import 'nib';
        button {
            cursor: pointer;
            font-size: 16px !important;
        }

        .content {
            padding: 0 !important;
        }

        .canvas-wrapper {
            transition: all 300ms ease;
            overflow: auto;
            position: fixed;
            z-index: 9999;
            top: 0px;
            background: rgba(0, 0, 0, 0.5);
            padding: 0px;
            left: 0px;
            height: calc(100% - 50px);
        }

        .canvas-active {
            transition: all 300ms ease;
            margin: 45px auto;
            margin-top: 3px;
        }

        .control-btns-div {
            position: fixed;
            z-index: 9999;
            bottom: 0px;
            text-align: center;
            width: 100%;
        }

        .control-btns-div button {
            width: 50%;
            text-align: center;
            height: 50px;
        }

        .control-btns-div button:first-child {
            background-color: #511285;
        }

        .control-btns-div button:last-child {
            background-color: #F232A4;
        }

        .piece-selected {
            border: 3px solid var(--indigo) !important;
        }

        .piece-selected .show-when-piece-selected {
            display: unset !important;
        }
    </style>
    <style>
        .imgPopBx {
            position: fixed;
            top: 0;
            bottom: 0;
            width: 100%;
            z-index: 9999998;
            background: #000;
            overflow-y: auto;
            padding: 50px 0;
        }

        .imgPopBx .imgbx {
            height: 100%;
            text-align: center;
        }

        .imgPopBx .imgbx img {
            max-width: 100%;
            transform: translateY(-50%);
            position: relative;
        }
    </style>
    <style>
        .fade-in-swal {
            opacity: 0;
            animation: fadeIn 700s ease;
        }

        .fade-out-swal {
            opacity: 1;
            animation: fadeOut 700s ease;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0
            }
            100% {
                opacity: 1
            }
        }

        @keyframes fadeOut {
            0% {
                opacity: 1
            }
            100% {
                opacity: 0;
                display: none;
            }
        }
    </style>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
@endsection

@section('content')
    <header class="clrhdr">
        <div class="leftIcn">
            <div class="backicn">BACK</div>
        </div>

        <div class="pgnme header_name">
            Create a quest
        </div>

        <div class="rgtIcn eyeBtn" style="display: none">
            <a href="{{ route('intro.temp.preview') }}" target="_blank" class="viewicn preview_link">EYE</a>
        </div>
    </header>
    <section class="slidPg slidPgx2 ">

        <div class="swiper-container">
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    Tutorial
                </div>
                <div class="swiper-slide">
                    <div class="ssinBx">
                        <aside class="whtBx1">
                            <div class="q1t1">Quest icon</div>
                            <div class="q1t2"><img id="blah" height="114px" width="114px"
                                                   src=" {{ asset("images/image.svg")  }} " onclick="chooseImage()"/>
                            </div>
                            <input type="file" accept="image/*" id="imgInp" style="height: 0; width: 0; opacity:0">
                            <input type="hidden" class="imgInp_hidden">

                            <div class="inSet">
                                <h6><span>Quest title</span></h6>
                                <input type="text" class="infld exam_title" placeholder=""/>
                                <!-- <span class="notTxt qs_mx_ttl">* Max length 40</span> -->
                            </div>
<div class="preq_type">
                            <div class="inSet ">
                                <h6><span>Prerequisite</span> <span class="qinfo">?</span></h6>

                                <select class="infld preq_type" onchange="getExtraPreqField(this.value)">
                                    <option value="0">No Prerequisite</option>
                                    <option value="1">Passing Exam Number</option>
                                    <option value="2">Group Total Star</option>
                                </select>
                            </div>

                            <div class="inSet preqVal" style="display: none">
                                <h6><span>Prerequisite Value</span></h6>
                                <div class="forHint">
                                    <select class="infld preq_value select-exam" name="" id="">
                                        @foreach($exams as $examItem)
                                            <option value="{{1000 + (int)$examItem->id}}">{{$examItem->title}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="infld preq_value input-g" placeholder=""/>
                                    <ul class="hintLst" style="display:none">

                                    </ul>
                                </div>


                            </div>
</div>
                        </aside>


                        <aside class="chkSet">
                            <div class="q1t3">Exam settings</div>

                            <ul class="chkList">
                                <li>
                                    <input type="checkbox" value="1" id="set1" name="is_random">
                                    <label for="set1"><span>Randomize questions order</span> <span
                                            class="qinfo">?</span></label>
                                </li>
                                <li>
                                    <input type="checkbox" id="set2" value="1" name="is_retake" checked>
                                    <label for="set2"><span>Can retake exam</span> <span class="qinfo">?</span></label>
                                </li>
                                {{--                            <li style="">--}}
                                {{--                                <input type="checkbox" id="set3" value="1" name="is_chat">--}}
                                {{--                                <label for="set3"><span>Enable chat</span> <span class="qinfo">?</span></label>--}}
                                {{--                            </li>--}}
{{--                                <li style="">--}}
{{--                                    <input type="checkbox" value="1" id="set5" name="is_private">--}}
{{--                                    <label for="set5"><span>Private</span> <span class="qinfo">?</span></label>--}}
{{--                                </li>--}}
                                <li style="">
                                    <input type="checkbox" value="1" id="set6" class="login_checkBox"
                                           name="is_login_required" checked onchange="toggleFieldsBox()">
                                    <label for="set6"><span>Registered users only</span></label>
                                    <br/>
{{--                                    <div class="fieldsBox">--}}
{{--                                        <p style="margin-left: 31px" class="fieldsp"><span>Required Fields</span> <span--}}
{{--                                                class="qinfo tooltip">?</span></p>--}}
{{--                                        <input type="text" class="infld2 login_fields" style="min-width: 150px"--}}
{{--                                               placeholder=""/>--}}
{{--                                    </div>--}}
                                </li>
                                <li>
                                    <input type="checkbox" id="set4" class="timeLimit" onclick="showtmBx()"
                                           name="settings">
                                    <label for="set4"><span>Time limit (minutes) <span
                                                class="qinfo">?</span></span></label>
                                    <br/>
                                    <input type="tel" style='display: none' class="infld2 nmbrOnly tmBx"
                                           placeholder=""/>
                                </li>
                            </ul>

                        </aside>
                    </div>
                </div>

                <div class="swiper-slide ">
                    <div class="ssinBx">
                        <ul class="list-unstyled quest_subject" id="sortable">


                        </ul>
                    </div>
                </div>

                <div class="swiper-slide ">
                    <div class="ssinBx question_lists">
                        <!--hidden field-->
                        <input type="hidden" class="quest_image">
                        <input type="hidden" class="quest_video">
                        <input type="hidden" class="quest_audio">
                        <!--hidden field-->


                    </div>
                </div>

                <div class="swiper-slide">
                    <!-- reward-->
                    <div class="ssinBx">
                        <section class="">

                            <div class="rsltFrm">
                                <div class="inSet">
                                    <h6><span>Quest review</span> <span class="qinfo">?</span></h6>
                                    <select class="infld rev_type swiper-no-swiping">
                                        <option value="0" selected>(Default) Do NOT show wrong answer</option>
                                        <option value="1">Show only wrong questions at the end of the exam</option>
                                        <option value="2">Show wrong questions & Answer ar the end of the exam</option>

                                    </select>
                                </div>
                                <div class="inSet">
                                    <h6><span>Number Of Questions To Show Students </span> <span class="qinfo">?</span>
                                    </h6>
                                    <input type="tel" class="infld count"/>
                                </div>

                                <div class="inSet">
                                    <h6><span>Passing percentage </span> <span class="qinfo">?</span></h6>
                                    <select class="infld pass_percentage swiper-no-swiping">
                                        <option value="60" selected>60%</option>
                                        <option value="70">70%</option>
                                        <option value="100">100%</option>

                                    </select>
                                </div>

                                <div class="inSet">
                                    <h6><span>Reward mode</span> <span class="qinfo">?</span></h6>
                                    <select class="infld rew_mod swiper-no-swiping"
                                            onchange="showRewardMode(this.value)">
                                        <option value="0" selected>Single</option>
                                        <option value="1">Coupon List</option>

                                    </select>
                                </div>

                                <div class="inSet list_coupon" style="display:none">
                                    <h6><span>Select Coupon</span> <span class="qinfo">?</span></h6>
                                    <select class="infld coupon_list swiper-no-swiping">

                                    </select>
                                </div>

                                <div class="singleModeRew" style="">
                                    <div class="inSet">
                                        <h6><span> Reward type</span> <span class="qinfo">?</span></h6>
                                        <select class="infld rew_type swiper-no-swiping"
                                                onchange="showRewardMsg(this.value)">
                                            <option value="0">Bluetooth</option>
                                            <option value="1">Show Text Message</option>
                                            <option value="2">Show Image</option>
                                            <option value="3" >Play Video</option>
                                            <option value="4">Certificate</option>
                                        </select>
                                    </div>

                                    <div class="inSet ble_reward" style="display:none">
                                        <h6><span> Ebot Name</span> <span class="qinfo">?</span></h6>
                                        <input type="text" class="infld hardware_name">
                                    </div>

                                    <div class="inSet ble_reward" style="display:none">
                                        <h6><span> Color Code</span> <span class="qinfo">?</span></h6>
                                        <select class="infld charectar swiper-no-swiping">
                                            <option value="$Q9R">Red</option>
                                            <option value="$Q9Y">Yellow</option>
                                            <option value="$Q9G">Green</option>
                                            <option value="$Q9B">Blue</option>
                                            <option value="$Q9P">Purple</option>
                                            <option value="$Q9W">White</option>
                                        </select>
                                    </div>

                                    <div class="inSet text_reward" style="display:none">
                                        <h6><span> Text Message</span> <span class="qinfo">?</span></h6>
                                        <textarea type="text" class="infld rew_text" class="infld"></textarea>
                                    </div>

                                    <div class="inSet image_reward" style="display:none">
                                        <h6><span> Image</span> <span class="qinfo">?</span></h6>
                                        <input type="file" accept="image/*" class="infld rew_image" id="rew_image">
                                        <img src="#" id="showReward" height="100px" width="100px" style="display:none">
                                    </div>

                                    <div class="inSet video_reward" style="display:none">
                                        <h6><span> Video URL</span> <span class="qinfo">?</span></h6>
                                        <input type="text" class="infld rew_video">
                                    </div>


                                    <div class="inSet cer_reward" style="display:none">
                                        <h6><span> Certificate Language</span> <span class="qinfo">?</span></h6>
                                        <select class="infld cert_lang swiper-no-swiping">
                                            <option value="english">English</option>
                                            <option value="arabic">Arabic</option>
                                        </select>
                                    </div>

                                    <div class="inSet cer_reward" style="display:none">
                                        <h6><span> Sponsor Logo (Optional)</span> <span class="qinfo">?</span></h6>
                                        <input type="file" accept="image/*" class="infld cert_logo">
                                    </div>
                                </div>

                            </div>

                        </section>
                    </div>
                </div>

                <div class="swiper-slide">
                    <!--final review-->
                    <div class="ssinBx">
                        <section class="">

                            <div class="rvwBox">
                                <div class="rvwBox1">

                                    <div class="revImg" class="xm_icon">
                                        <img src=" {{ asset("images/image.svg")  }} ">
                                    </div>

                                    <div class="revT1">
                                        <h1>Quest title</h1>
                                        <h2 class="xm_title">This is the title of my Quest</h2>

                                        <h1>Prerequisite</h1>
                                        <h2 class="xm_preq">No Prerequisite</h2>
                                    </div>

                                    <div class="magic"></div>

                                    <ul class="rvwChk xm_settings">
                                        <li style="">
                                            <input disabled type="checkbox" id="rvchk5" name="rev_check">
                                            <label for="rvchk5"><span>Private</span></label>
                                        </li>
                                        <li>
                                            <input disabled type="checkbox" id="rvchk1" name="rev_check">
                                            <label for="rvchk1"><span>Randomize questions order</span></label>
                                        </li>
                                        <li>
                                            <input disabled type="checkbox" id="rvchk2" name="rev_check">
                                            <label for="rvchk2"><span>Can retake exam</span></label>
                                        </li>
                                        <li>
                                            <input disabled type="checkbox" id="rvchk3" name="rev_check">
                                            <label for="rvchk3"><span
                                                    class="xm_time">Time limit ( 00 minuts )</span></label>
                                        </li>
{{--                                        <li>--}}
{{--                                            <input disabled type="checkbox" id="rvchk4" name="rev_check">--}}
{{--                                            <label for="rvchk4"><span>Enable chat</span></label>--}}
{{--                                        </li>--}}
                                    </ul>

                                    <div class="edtBtnBx">
                                        <a href="#" onclick="javascript: swiper.slideTo(1)" class="edtBtn">EDIT</a>
                                    </div>

                                </div>
                            </div>

                            <div class="rvwBox">
                                <div class="rvwBox2">

                                    <h6>Subject</h6>

                                    <ul class="subjLst xm_subjects">
                                        <li>
                                            <aside class="subTxt">
                                                <div class="subIcn"><img src=" {{ asset("images/p_text.svg")  }} ">
                                                </div>
                                                <div class="subTxt2">
                                                    This is a title for the subject
                                                </div>
                                            </aside>
                                        </li>
                                        <li>
                                            <aside class="subTxt">
                                                <div class="subIcn"><img src=" {{ asset("images/p_note.svg")  }} ">
                                                </div>
                                                <div class="subTxt2">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Auctor diam
                                                    pharetra a, mauris cras. Volutpat nunc blandit vitae est vitae non
                                                    mauris mssmassa a nunc ....
                                                </div>
                                            </aside>
                                        </li>
                                        <li>
                                            <aside class="subTxt">
                                                <div class="subIcn"><img src=" {{ asset("images/p_table.svg")  }} ">
                                                </div>
                                                <div class="subTxt2">
                                                    Table
                                                </div>
                                            </aside>
                                        </li>
                                        <li>
                                            <aside class="subTxt">
                                                <div class="subIcn"><img src=" {{ asset("images/p_img.svg")  }} "></div>
                                                <div class="subTxt2">
                                                    Image
                                                </div>
                                            </aside>
                                        </li>
                                        <li>
                                            <aside class="subTxt">
                                                <div class="subIcn"><img src=" {{ asset("images/p_video.svg")  }} ">
                                                </div>
                                                <div class="subTxt2">
                                                    Youtube video
                                                </div>
                                            </aside>
                                        </li>
                                        <li>
                                            <aside class="subTxt">
                                                <div class="subIcn"><img src=" {{ asset("images/p_audio.svg")  }} ">
                                                </div>
                                                <div class="subTxt2">
                                                    1:16
                                                </div>
                                            </aside>
                                        </li>
                                    </ul>
                                    <div class="edtBtnBx">
                                        <a href="#" onclick="javascript: swiper.slideTo(2)" class="edtBtn">EDIT</a>
                                    </div>

                                </div>
                            </div>

                            <div class="rvwBox">
                                <div class="rvwBox2">

                                    <h6>Questions</h6>

                                    <div class="rqlistBx">
                                        <ul class="revQlist">

                                            <li>
                                                <aside class="rqGap">
                                                    <span class="rqTxt xm_total_mcq">0</span>
                                                    <span><img
                                                            src=" {{ asset("images/multiple_choice.svg")  }} "/></span>
                                                </aside>
                                            </li>
                                            <li>
                                                <aside class="rqGap ">
                                                    <span class="rqTxt xm_total_word_game">0</span>
                                                    <span><img src=" {{ asset("images/word_game.svg")  }} "/></span>
                                                </aside>
                                            </li>
                                            <li>
                                                <aside class="rqGap">
                                                    <span class="rqTxt xm_total_dd">0</span>
                                                    <span><img src=" {{ asset("images/drag_drop.svg")  }} "/></span>
                                                </aside>
                                            </li>
                                            <li>
                                                <aside class="rqGap">
                                                    <span class="rqTxt xm_toatl_project">0</span>
                                                    <span><img src=" {{ asset("images/project.svg")  }} "/></span>
                                                </aside>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="edtBtnBx">
                                        <a href="#" onclick="javascript: swiper.slideTo(3)" class="edtBtn">EDIT</a>
                                    </div>
                                </div>
                            </div>

                            <div class="rvwBox">
                                <div class="rvwBox2">

                                    <h6>Result and reward</h6>

                                    <div class="rrTxt1">
                                        <ul class="rrTxt2">
                                            <li>
                                                <span class="rrTc1">Quest review:</span> <span class="rrTc2 xm_review">Hide wrong answers</span>
                                            </li>
                                            <li>
                                                <span class="rrTc1"> Passing percentage:</span> <span
                                                    class="rrTc2 xm_percentage">60%</span>
                                            </li>
                                            <li>
                                                <span class="rrTc1">Reward mode:</span> <span
                                                    class="rrTc2 xm_reward_mode">Single</span>
                                            </li>
                                            <li>
                                                <span class="rrTc1">Reward type:</span> <span
                                                    class="rrTc2 xm_reward_type">Text</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="edtBtnBx">
                                        <a href="#" onclick="javascript: swiper.slideTo(4)" class="edtBtn">EDIT</a>
                                    </div>
                                </div>
                            </div>


                        </section>

                        <!-- <div class="btnBar">
                <a class="btnvio" onclick="saveExamAsDraft()" href="#">SAVE DRAFT</a>
                <a class="btnpnk" onclick="publishExam()" href="#">PUBLISH</a>
                </div> -->
                    </div>

                </div>

            </div>
        </div>


    </section>
    <div class="roundBtn2" id="popB1" style="display:none" onclick="ExamOption()">PLUS BUTTON</div>
    <div class="roundBtn2" id="popB2" style="display:none" onclick="ExamOption2()">PLUS BUTTON</div>
    <section class="slidPg quest_subject" style="display:none">


    </section>

    </div>
    </div>

    <div class="btnBar finalSvBtn" style=" position: absolute;
    bottom: 0;
    width: 100%;
    z-index: 99999; display:none; left:0; right:0">
        <a class="btnvio" onclick="publishExam(true)" href="#">SAVE DRAFT</a>
        <a class="btnpnk" onclick="publishExam(false)" href="#">PUBLISH</a>
    </div>

    <div class="qactionbar ">
        <div class="qactionbarx2">
            <div class="prev" style="display:none"></div>
            <ul class="pagedot">
                <li class="activ dot">1</li>
                <li class="dot">2</li>
                <li class="dot">3</li>
                <li class="dot">4</li>
                <li class="dot">5</li>
                <li class="dot">6</li>
            </ul>
            <div class="next"></div>
            <div class="magic"></div>
        </div>
    </div>

    <!--HTML for POPUP white / Creat quest 6 -->
    <div id="mask2" class="mask" style="display:none; z-index:9999999" onclick="popp1close()"></div>

    <div class="popBttom" id="popP2">
        <div class="popSet1">
            <div class="mxWd1">
                <ul class="pIcnLst2">

                    <li id="" onclick="openQuestPop('multiple_choice')">
                        <aside class="qqBtn">
                            <span class="mcicon2">Multiple Choice Icon</span>
                            <h6>Multiple Choice</h6>
                        </aside>
                    </li>
                    <li id="" onclick="openQuestPop('word_game')">
                        <aside class="qqBtn">
                            <span class="wrdgmicon2">Words Game Icon</span>
                            <h6>Words game</h6>
                        </aside>
                    </li>

                    <li onclick="openQuestPop('dd')">
                        <aside class="qqBtn">
                            <span class="ddicon2">Drag & Drop Icon</span>
                            <h6> Drag & Drop</h6>
                        </aside>
                    </li>
                    <li onclick="openQuestPop('project')">
                        <aside class="qqBtn">
                            <span class="prjicon2">Project Icon</span>
                            <h6>Project</h6>
                        </aside>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    <div id="mask" class="mask" style="display:none;" onclick="popp1close()"></div>

    <div class="popBttom" id="popP1">
        <div class="popSet1">
            <ul class="pIcnLst" style="height: 230px; overflow-x: hidden !important;">
                <li id="titlPopB1" onclick="openIntroPop('title')">
                    <aside class="txtBtn">
                        <img src=" {{ asset("images/p_text.svg")  }} "/>
                        <h6>Title</h6>
                    </aside>
                </li>
                <li>
                    <aside class="notBtn" onclick="openIntroPop('paragraph')">
                        <img src=" {{ asset("images/p_note.svg")  }} "/>
                        <h6>Paragraph</h6>
                    </aside>
                </li>

                <li>
                    <aside class="tableBtn" onclick="openIntroPop('table')">
                        <img src=" {{ asset("images/p_table.svg")  }} "/>
                        <h6>Table</h6>
                    </aside>
                </li>
                <li>
                    <aside class="imgBtn" onclick="openIntroPop('image')">
                        <img src=" {{ asset("images/p_img.svg")  }} "/>
                        <!-- <input type="hidden" class="intro_image"> -->
                        <h6>Image</h6>
                    </aside>
                </li>
                <input type="file" accept="image/*" class="introImg" style="height:0; width:0; opacity:0">
                <li id="vdoPopB1">
                    <aside class="vdoBtn" onclick="openIntroPop('video')">
                        <img src=" {{ asset("images/p_video.svg")  }} "/>
                        <h6>Video</h6>
                    </aside>
                </li>
                <li>
                    <aside class="adoBtn" onclick="openIntroPop('audio')">
                        <img src=" {{ asset("images/p_audio.svg")  }} "/>
                        <h6>Audio</h6>
                    </aside>
                </li>

                <li>
                    <aside class="adoBtn" onclick="openIntroPop('file')">
                        <img src=" {{ asset("images/attach.svg")  }} ">
                        <h6>Upload file</h6>
                    </aside>
                </li>
                <input type="file" class="introFile" onchange="saveDataPopIntro('file')"
                       style="height:0; width:0; opacity:0">
{{--                <li>--}}
{{--                    <aside class="adoBtn" onclick="openIntroPop('order')">--}}
{{--                        <img src=" {{ asset("images/order.svg")  }} ">--}}
{{--                        <h6>Order button</h6>--}}
{{--                    </aside>--}}
{{--                </li>--}}
            </ul>
        </div>
    </div>


    <!--Project popup-->
    <div class="prgPopBx" id="prjPop" style="display: none;">
        <div class="clrhdr2">
            <div class="leftIcn">
                <div class="backicn pcancel" onclick="closeQuestPop('project')">BACK</div>
            </div>

            <div class="pgnme">
                Project
            </div>

            <div class="rgtIcn">

            </div>
        </div>

        <div class="wgmPBx">
            <div class="prjPset">
                <div class="inSet">
                    <h6><span>Project description</span></h6>
                    <textarea type="text" class="infld project_title" id="project_title" placeholder=""></textarea>
                </div>
                <ul class="pIcnLst wgmIcns">
                    <input type="file" accept="image/*" class="prImg" style="height:0; width:0; opacity:0"
                           id="showPrImg">
                    <li onclick="clickWgQImage('project')">
                        <aside class="imgBtn">
                            <img src=" {{ asset("images/p_img.svg")  }} ">
                            <h6>Image</h6>
                        </aside>
                    </li>
                    <li onclick="openIntroPop('quest_pr_vid')">
                        <aside class="vdoBtn">
                            <img src=" {{ asset("images/p_video.svg")  }} ">
                            <h6>Video</h6>
                        </aside>
                    </li>
                    <li onclick="openIntroPop('quest_pr_audio')">
                        <aside class="adoBtn">
                            <img src=" {{ asset("images/p_audio.svg")  }} ">
                            <h6>Audio</h6>
                        </aside>
                    </li>
                    <li>
                        <aside class="adoBtn" onclick="$('.projectFile').trigger('click')">
                            <img src=" {{ asset("images/attach.svg")  }} ">
                            <h6>Upload file</h6>
                        </aside>
                    </li>
                    <input type="file" class="projectFile" onchange="saveDataPopIntro('project_file')"
                           style="height:0; width:0; opacity:0">
                </ul>
                <aside class="wgImgBx prImgBx" style="display:none">
                    <div class="wgBxHdlne">Question images</div>
                    <div class="wgImgscrl">
                        <ul class="pr_img_list">


                        </ul>
                    </div>
                </aside>
                <aside class="wgAudBx prAudBx" style="display:none">
                    <div class="wgBxHdlne"> Question audio</div>
                    <div class="wgaudTag praudTag center">

                    </div>
                </aside>
                <aside class="wgVdoBx prVdoBx" style="display:none">
                    <div class="wgBxHdlne">Question video</div>
                    <div class="wgvdoTag prvdoTag center">

                    </div>
                </aside>

            </div>

        </div>
        <!--wgmPBx-->

        <div class="tblPbtnBr">
            <div id="" class="canBtn2 pcancel" onclick="closeQuestPop('project')">Cancel</div>
            <div class="donBtn2 pr_svBtn" onclick="saveProject()">Done</div>
        </div>
    </div>

    <!-- Multiple Choice Popup -->
    <div class="prgPopBx" id="mchPop" style="display: none;">
        <div class="mchPopx2 mchPopx22">

            <div class="clrhdr2">
                <div class="clrhdr2x2">
                    <div class="leftIcn">
                        <div class="backicn pcancel" onclick="closeQuestPop('multiple_choice')">BACK</div>
                    </div>

                    <div class="pgnme">
                        Multiple choice
                    </div>

                    <div class="rgtIcn">

                    </div>
                    <div class="magic"></div>
                </div>
            </div>

            <div class="wgmPBx">
                <div class="wgmWht">
                    <div class="inSet">
                        <h6><span>Question text</span></h6>
                        <input type="text" class="infld multiple_question" placeholder="">
                        <!-- <span class="notTxt qs_txt">* Max length 40</span> -->
                    </div>
                    <ul class="pIcnLst wgmIcns">
                        <input type="file" accept="image/*" class="mcImg" style="height:0; width:0; opacity:0"
                               id="showMcImg">
                        <li onclick="clickWgQImage('multiple_choice')">
                            <aside class="imgBtn">
                                <img src=" {{ asset("images/p_img.svg")  }} ">
                                <h6>Image</h6>
                            </aside>
                        </li>
                        <input type="hidden" class="wg_tmp_img">
                        <li onclick="openIntroPop('quest_mc_vid')">
                            <aside class="vdoBtn">
                                <img src=" {{ asset("images/p_video.svg")  }} ">
                                <h6>Video</h6>
                            </aside>
                        </li>
                        <li onclick="openIntroPop('quest_mc_audio')">
                            <aside class="adoBtn">
                                <img src=" {{ asset("images/p_audio.svg")  }} ">
                                <h6>Audio</h6>
                            </aside>
                        </li>
                    </ul>
                    <aside class="wgImgBx mcImgBx" style="display:none">
                        <div class="wgBxHdlne">Question images</div>
                        <div class="wgImgscrl">
                            <ul class="mc_img_list">


                            </ul>
                        </div>
                    </aside>
                    <aside class="wgAudBx mcAudBx" style="display:none">
                        <div class="wgBxHdlne"> Question audio</div>
                        <div class="wgaudTag mcaudTag center">

                        </div>
                    </aside>
                    <aside class="wgVdoBx mcVdoBx" style="display: none">
                        <div class="wgBxHdlne">Question video</div>
                        <div class="wgvdoTag mcvdoTag center">

                        </div>
                    </aside>

                </div>

                <div class="mchGry">
                    <div class="inSet">
                        <aside class="chkLne">
                            <div class="chkLneL">
                                <div class="chkList">
                                    <input type="radio" id="mch1" value="1" checked name="mch_check">
                                    <label for="mch1"><span>Answer 1</span></label>
                                </div>
                            </div>
                            <div class="chkLneR">
                                <ul class="smlRdioIcn">
                                    <input type="hidden" class="quest_image_option_1">
                                    <input type="hidden" class="quest_voice_option_1">
                                    <li>
                                        <input type="radio" value="text" id="txtrdio" name="sml_rdio" checked
                                               onchange="showAnsArea(1,'text')">
                                        <label for="txtrdio" class="txtrdio"><span>Text</span></label>
                                    </li>
                                    <li>
                                        <input type="radio" value="image" id="imgrdio" name="sml_rdio"
                                               onchange="showAnsArea(1,'image')">
                                        <label for="imgrdio" class="imgrdio"><span>Image</span></label>
                                    </li>
                                    <input type="file" accept="image/*" class="ansImg_1"
                                           style="height:0; width:0; opacity:0" onchange="readAnsImg(this,1)"/>
                                    <li>
                                        <input type="radio" value="audio" id="vocrdio" name="sml_rdio"
                                               onchange="showAnsArea(1,'audio')">
                                        <label for="vocrdio" class="vocrdio"><span>Voice</span></label>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                        <div class="ansTxt_1">
                            <input type="text" class="infld qst_ans_1" placeholder="">
                            <!-- <span class="notTxt qs_ans1_mx">* Max length 40</span> -->
                        </div>
                        <div class="ansImgArea_1" style="display:none">

                        </div>
                        <div class="ansAud_1" style="display:none">

                        </div>
                    </div>

                    <div class="inSet">
                        <aside class="chkLne">
                            <div class="chkLneL">
                                <div class="chkList">
                                    <input type="radio" id="mch2" value="2" name="mch_check">
                                    <label for="mch2"><span>Answer 2</span></label>
                                </div>
                            </div>
                            <div class="chkLneR">
                                <ul class="smlRdioIcn">
                                    <input type="hidden" class="quest_image_option_2">
                                    <input type="hidden" class="quest_voice_option_2">
                                    <li>
                                        <input type="radio" value="text" id="txtrdio2" name="sml_rdio2" checked
                                               onchange="showAnsArea(2,'text')">
                                        <label for="txtrdio2" class="txtrdio"><span>Text</span></label>
                                    </li>
                                    <li>
                                        <input type="radio" value="image" id="imgrdio2" name="sml_rdio2"
                                               onchange="showAnsArea(2,'image')">
                                        <label for="imgrdio2" class="imgrdio"><span>Image</span></label>
                                    </li>
                                    <input type="file" accept="image/*" class="ansImg_2"
                                           style="height:0; width:0; opacity:0" onchange="readAnsImg(this,2)"/>
                                    <li>
                                        <input type="radio" value="audio" id="vocrdio2" name="sml_rdio2"
                                               onchange="showAnsArea(2,'audio')">
                                        <label for="vocrdio2" class="vocrdio"><span>Voice</span></label>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                        <div class="ansTxt_2">
                            <input type="text" class="infld qst_ans_2" placeholder="">
                            <!-- <span class="notTxt qs_ans2_mx">* Max length 40</span> -->
                        </div>
                        <div class="ansImgArea_2" style="display:none">
                        </div>
                        <div class="ansAud_2" style="display:none">

                        </div>
                    </div>

                    <div class="inSet">
                        <aside class="chkLne">
                            <div class="chkLneL">
                                <div class="chkList">
                                    <input type="radio" id="mch3" value="3" name="mch_check">
                                    <label for="mch3"><span>Answer 3 (optionl)</span></label>
                                </div>
                            </div>
                            <div class="chkLneR">
                                <ul class="smlRdioIcn">
                                    <input type="hidden" class="quest_image_option_3">
                                    <input type="hidden" class="quest_voice_option_3">
                                    <li>
                                        <input type="radio" value="text" id="txtrdio3" name="sml_rdio3" checked
                                               onchange="showAnsArea(3,'text')">
                                        <label for="txtrdio3" class="txtrdio"><span>Text</span></label>
                                    </li>
                                    <li>
                                        <input type="radio" value="image" id="imgrdio3" name="sml_rdio3"
                                               onchange="showAnsArea(3,'image')">
                                        <label for="imgrdio3" class="imgrdio"><span>Image</span></label>
                                    </li>
                                    <input type="file" accept="image/*" class="ansImg_3"
                                           style="height:0; width:0; opacity:0" onchange="readAnsImg(this,3)"/>
                                    <li>
                                        <input type="radio" value="audio" id="vocrdio3" name="sml_rdio3"
                                               onchange="showAnsArea(3,'audio')">
                                        <label for="vocrdio3" class="vocrdio"><span>Voice</span></label>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                        <div class="ansTxt_3">
                            <input type="text" class="infld qst_ans_3" placeholder="">
                            <!-- <span class="notTxt qs_ans3_mx">* Max length 40</span> -->
                        </div>
                        <div class="ansImgArea_3" style="display:none">

                        </div>
                        <div class="ansAud_3" style="display:none">

                        </div>
                    </div>

                    <div class="inSet">
                        <aside class="chkLne">
                            <div class="chkLneL">
                                <div class="chkList">
                                    <input type="radio" id="mch4" value="4" name="mch_check">
                                    <label for="mch4"><span>Answer 4 (optionl)</span></label>
                                </div>
                            </div>
                            <div class="chkLneR">
                                <ul class="smlRdioIcn">
                                    <input type="hidden" class="quest_image_option_4">
                                    <input type="hidden" class="quest_voice_option_4">
                                    <li>
                                        <input type="radio" value="text" checked id="txtrdio4" name="sml_rdio4" checked
                                               onchange="showAnsArea(4,'text')">
                                        <label for="txtrdio4" class="txtrdio"><span>Text</span></label>
                                    </li>
                                    <li>
                                        <input type="radio" value="image" id="imgrdio4" name="sml_rdio4"
                                               onchange="showAnsArea(4,'image')">
                                        <label for="imgrdio4" class="imgrdio"><span>Image</span></label>
                                    </li>
                                    <input type="file" accept="image/*" class="ansImg_4"
                                           style="height:0; width:0; opacity:0" onchange="readAnsImg(this,4)"/>
                                    <li>
                                        <input type="radio" value="audio" id="vocrdio4" name="sml_rdio4"
                                               onchange="showAnsArea(4,'audio')">
                                        <label for="vocrdio4" class="vocrdio"><span>Voice</span></label>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                        <div class="ansTxt_4">
                            <input type="text" class="infld qst_ans_4" placeholder="">
                            <!-- <span class="notTxt qs_ans4_mx">* Max length 40</span> -->
                        </div>
                        <div class="ansImgArea_4" style="display:none">

                        </div>
                        <div class="ansAud_4" style="display:none">

                        </div>
                    </div>

                </div>
                <!--mchGry-->

            </div>
            <!--wgmPBx-->

            <div class="tblPbtnBr">
                <div class="tblPbtnBrx2">
                    <div id="" class="canBtn2 pcancel" onclick="closeQuestPop('multiple_choice')">Cancel</div>
                    <div class="donBtn2 mcq_svBtn" onclick="saveMultipleChoiceQuest()">Done</div>
                </div>
            </div>

        </div>

    </div>



    <!-- wordgame Popup -->
    <div class="prgPopBx" id="wgmPop" style="display: none;">
        <div class="clrhdr2">
            <div class="leftIcn">
                <div class="backicn pcancel" onclick="closeQuestPop('word_game')">BACK</div>
            </div>

            <div class="pgnme">
                Words game
            </div>

            <div class="rgtIcn">

            </div>
        </div>

        <div class="wgmPBx">
            <div class="wgmWht">
                <div class="inSet">
                    <h6><span>Question text</span></h6>
                    <input type="text" class="infld word_game_title" placeholder="">
                </div>
                <ul class="pIcnLst wgmIcns">
                    <input type="file" accept="image/*" class="wgImg" style="height:0; width:0; opacity:0"
                           id="showWgImg">
                    <li onclick="clickWgQImage()">
                        <aside class="imgBtn">
                            <img src=" {{ asset("images/p_img.svg")  }} ">
                            <h6>Image</h6>
                        </aside>
                    </li>
                    <li onclick="openIntroPop('quest_wg_vid')">
                        <aside class="vdoBtn">
                            <img src=" {{ asset("images/p_video.svg")  }} ">
                            <h6>Video</h6>
                        </aside>
                    </li>
                    <li onclick="openIntroPop('quest_wg_audio')">
                        <aside class="adoBtn">
                            <img src=" {{ asset("images/p_audio.svg")  }} ">
                            <h6>Audio</h6>
                        </aside>
                    </li>

                </ul>
                <aside class="wgImgBx" style="display:none">
                    <div class="wgBxHdlne">Question images</div>
                    <div class="wgImgscrl">
                        <ul class="wg_img_list">


                        </ul>
                    </div>
                </aside>
                <aside class="wgAudBx" style="display:none; ">
                    <div class="wgBxHdlne"> Question audio</div>
                    <div class="wgaudTag center">

                    </div>
                </aside>
                <aside class="wgVdoBx" style="display:none">
                    <div class="wgBxHdlne">Question video</div>
                    <div class="wgvdoTag center">

                    </div>
                </aside>

            </div>

            <div class="wgmGry">
                <div class="inSet">
                    <h6><span>Correct answer</span></h6>
                    <input type="text" class="infld word_game_answer" placeholder="">
                    <!-- <span class="notTxt wg_remain_txt">* Max length 40</span> -->
                </div>
            </div>

        </div>
        <!--wgmPBx-->

        <div class="tblPbtnBr">
            <div id="" class="canBtn2 pcancel" onclick="closeQuestPop('word_game')">Cancel</div>
            <div class="donBtn2 wgSvBtn" onclick="saveWordGameQuest()">Done</div>
        </div>
    </div>


    <!---Drag and drop-->
    <div class="prgPopBx" id="ddPop" style="display: none; background-color: #f4f4f4; overflow-x: hidden;">
        <div class="clrhdr2">
            <div class="leftIcn">
                <div class="backicn pcancel" onclick="closeQuestPop('dd')">BACK</div>
            </div>

            <div class="pgnme">
                Drag & Drop
            </div>

            <div class="rgtIcn">

            </div>
        </div>
        @php
            $max_count = env('MAX_COUNT', 4);
        @endphp
        @include('puzzle.new-game')

        <div class="tblPbtnBr" style="position: relative !important">
            <div id="" class="canBtn2 pcancel" onclick="closeQuestPop('dd')">Cancel</div>
            <div class="donBtn2 ddsaveQsn" onclick="saveDDQuestion()">Done</div>
        </div>

    </div>

    <!---Cropper Image Popup -->
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
            <div id="" class="canBtn2 pcancel" onclick="javascript: $('.cropperJsPop').hide(); cropper.destroy();">
                Cancel
            </div>
            <div class="donBtn2 cropDImage" onclick="cropImage()">Done</div>
        </div>
    </div>

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

    <!-- Title popup -->
    <div class="txtFldPop" id="titlPop" style="display: none;">
        <div class="tfpIn">
            <div class="tfpBx1">
                <div class="inSet">
                    <h6><span>New title</span></h6>
                    <input type="text" class="infld intro_title" placeholder="">
                    <!-- <span class="notTxt ttl_mxTxt">* Max length 40</span> -->
                </div>
            </div>
            <div class="tfpBx2">
                <div id="" class="canBtn pcancel">Cancel</div>
                <div class="donBtn title_svbtn" onclick="saveDataPopIntro('title')">Done</div>
            </div>
        </div>
    </div>

    <div class="txtFldPop" id="orderPop" style="display: none;">
        <div class="tfpIn">
            <div class="tfpBx1">
                <div class="inSet">
                    <h6><span>Product URL</span></h6>
                    <input type="text" class="infld order_url" placeholder="">
                    <!-- <span class="notTxt ttl_mxTxt">* Max length 40</span> -->
                </div>
            </div>
            <div class="tfpBx2">
                <div id="" class="canBtn pcancel">Cancel</div>
                <div class="donBtn order_svbtn" onclick="saveDataPopIntro('order')">Done</div>
            </div>
        </div>
    </div>

    <!-- Audio popup -->
    <div class="txtFldPop" id="audPop" style="display: none;  z-index:99999999">
        <div class="tfpIn">
            <div class="tfpBx1">
                <div class="audioSet" id="hide_on_audio_record">
                    <div id="hide_on_audio_upload">
                        <span class="audicn_btn" id="startBtn"
                              onclick="$('#hide_on_audio_record').hide();$('.audioSet.record').show();"></span>
                        <p class="my-1">OR</p>
                    </div>
                    <button class="btn font-weight-bold" style="background-color: #F232A4; color: white;" role="button"
                            onclick="$('#audio_file_upload').trigger('click')"><i class="fas fa-upload"></i> Upload
                    </button>
                    <input type="file" accept="audio/*" style="display: none" id="audio_file_upload"
                           onchange="recorded_audio = this.files[0]; $('#hide_on_audio_upload').hide(); $('#audio_file_name').text(this.files[0].name);">
                    <p><b id="audio_file_name"></b></p>
                </div>
                <div class="audioSet record" style="display: none">
                    <h6 class="aud_timer" id="timer">00 : 00</h6>
                    <span class="audIcnBtn" id="stopBtn"></span>
                    <ol id="recordingsList"></ol>
                    <audio id="audioPlayer" controls></audio>

                </div>
            </div>
            <div class="tfpBx2">
                <div id="" class="canBtn pcancel" onclick="reset_audio_panel()">Cancel</div>
                <div class="donBtn audio_svbtn" data-record="0" onclick="saveDataPopIntro('audio')">Done</div>
            </div>
        </div>
    </div>


    <!-- Table Popup -->
    <div class="tblPopBx" id="tablePop" style="display: none;">
        <div class="tablAction">
            <div class="taRow">
                <div class="acXa">Rows</div>
                <div class="acXb"><input type="range" min="1" max="10" onchange="renderRows()" value="2"
                                         class="slider IntrPopRow" id="rows"></div>
                <div class="acXc"><span id="rowsv"></span></div>
            </div>
            <div class="taClmn">
                <div class="acXa">Columns</div>
                <div class="acXb"><input type="range" min="1" max="10" onchange="renderColumns()" value="2"
                                         class="slider IntrPopClm" id="columns"></div>
                <div class="acXc"><span id="columnsv"></span></div>
            </div>
        </div>

        <div class="TblHtmls dynamicTable">

        </div>

        <div class="dynamicTable2" style="height:0; width:0; opacity:0">

        </div>

        <div class="tblPbtnBr">
            <div id="" class="canBtn2 pcancel">Cancel</div>
            <div class="donBtn2 table_svbtn" onclick="saveDataPopIntro('table')">Done</div>
        </div>
    </div>


    <!-- Paragraph Popup -->
    <div class="prgPopBx" id="prgPop" style="display: none;">
        <div class="clrhdr2">
            <div class="leftIcn">
                <div class="backicn pcancel">BACK</div>
            </div>

            <div class="pgnme">
                Quest subject
            </div>

            <div class="rgtIcn">

            </div>
        </div>

        <div class="prgBx">
            <textarea class="intro_paragraph " id="editor" name="editor"></textarea>
        </div>

        <div class="tblPbtnBr">
            <div id="" class="canBtn2 pcancel">Cancel</div>
            <div class="donBtn2 paragraph_svbtn" onclick="saveDataPopIntro('paragraph')">Done</div>
        </div>
    </div>


    <div class="loader" style="display:none"></div>

    <div class="savenoti" style="display:none">SAVED CHANGES</div>
@endsection
@section('pkgs')
    <script>
        var route_is_update = {{ \Request::route()->named('exams.edit') ? 'true' : 'false'  }} ? true : false;
        var rootURL = '{{ url('/') }}/';
        var getFileURL = '{{ route('storage.getfile', ['file' => 'file_path']) }}';
        var offlineDBname = route_is_update ? "questions_edit" : "questions";
        localStorage.setItem('offline_DB', offlineDBname);

        $(function () {
            $('.sidMnu').show();
            if (typeof pbtn != 'undefined') {
                clearInterval(pbtn);
            }
            window.onbeforeunload = function () {
                return "Are you sure?";
            }
            $("#post_list").sortable({
                handle: ".drgUpar",
            });
            $("#sortable").sortable({
                handle: ".dragBx .drgAction.drgAction2",
                stop: function (event, ui) {
                    update_order('#sortable', 'Intro_sort');
                }
            });
            $(".question_lists").sortable({
                handle: ".dragBx .drgAction.drgAction2",
                stop: function (event, ui) {
                    update_order('.question_lists', 'Questions_sort');
                }
            });
        });

    </script>

    <script src="{{ asset("ckeditor/ckeditor.js") }}"></script>
    <script src="https://unpkg.com/konva@7.0.3/konva.min.js"></script>
    <script src="{{ asset("js/swiper-bundle.js") }}"></script>
    <script>
        @if(Request::route()->named('exams.edit'))
        var exam = @json($exam, JSON_PRETTY_PRINT);
        var intro = @json($intro, JSON_PRETTY_PRINT);
        var exam_questions = @json($questions, JSON_PRETTY_PRINT);
        var data_copy_with_urls =  @json($data_copy_with_urls, JSON_PRETTY_PRINT);

        function get_url_from_copy(levels, current_level) {
            var remaining_levels = levels.split('_');
            var level = remaining_levels.shift();
            if (remaining_levels.length == 0) {
                level = level.replace('-', '_');
                return current_level[level];
            } else if (level == "Intro" && remaining_levels.length == 1) {
                return current_level[level + '_' + remaining_levels[0].replace('-', '_')];
            } else {
                return get_url_from_copy(remaining_levels.join('_'), current_level[level]);
            }
        }
        @endif
    </script>
@endsection
@section('scripts')

    <script>
        $('.base_url').val(rootURL);
        var submitURL = "{{ Request::route()->named('exams.create') ? route('exams.store') : route('exams.update', ['exam' => $exam]) }}";
        var used = [];
        var elements = null;

        //get all elements
        if (typeof document.getElementsByTagName != 'undefined') {
            elements = document.getElementsByTagName('*');
        }
        //loop over all element
        for (var i = 0; i < elements.length; i++) {

            //loop over element's classes
            var classes = elements[i].className.length ? elements[i].className.split(' ') : '';
            for (var j = 0; j < classes.length; j++) {

                var name = classes[j];

                //add if not exists
                if (name.length > 0 && used.indexOf(name) === -1) {
                    used.push(name);
                }
            }
        }
    </script>
    <script>
        swiper = new Swiper('.swiper-container', {

            pagination: {
                el: '.swiper-pagination',
            },

        });
        swiper.slideTo(1);
        swiper.on('slideChange', function () {
            console.log("CALLING SCROLL TOP");

            $('.slidPg').scrollTop(0);

            if (swiper.activeIndex > 0) {
                $('.prev').fadeIn();
            } else {
                $('.prev').fadeOut();
            }


            $('.dot').removeClass('activ')
            $('.dot').each(function (m) {
                if (m == swiper.activeIndex) {
                    $(this).addClass('activ');
                }
            })
            if (swiper.activeIndex == 2) {
                $('#popB1').fadeIn();
                $('.eyeBtn').fadeIn();
                $('#popB2').fadeOut();
                saveExamDraft(parseInt(swiper.activeIndex - 1));
                $('.header_name').text("Quest subject");

                $('.qactionbar').show();
                $('.finalSvBtn').hide();
            } else if (swiper.activeIndex == 3) {
                $('#popB2').fadeIn();
                $('.eyeBtn').fadeOut();
                $('#popB1').fadeOut();

                $('.header_name').text("Quest questions");

                $('.qactionbar').show();
                $('.finalSvBtn').hide();
            } else if (swiper.activeIndex == 1) {
                $('#popB1').fadeOut();
                $('#popB2').fadeOut();
                $('.eyeBtn').fadeOut();


                $('.header_name').text("Quest settings");

                $('.qactionbar').show();
                $('.finalSvBtn').hide();
            } else if (swiper.activeIndex == 4) {

                $('#popB1').fadeOut();
                $('#popB2').fadeOut();
                $('.eyeBtn').fadeOut();

                 // $('.rew_type').val(3).change();
                $('.header_name').text("Result and reward");

                $('.count').val($('.quest_list_item').length);

                $('.qactionbar').show();
                $('.finalSvBtn').hide();
            } else if (swiper.activeIndex == 5) {
                //submit previous form
                var xm_id = localStorage.getItem("draft_exam_id");
                if (xm_id != 'undefined' && xm_id != null) {
                    console.log("saving");
                    submitReviewPage();
                    //showLoader();
                    //fetchAlldata();
                    //hideLoader();

                }
                //fetchAlldata();
                $('.header_name').text("Review");

                $('.qactionbar').hide();
                $('.finalSvBtn').show();
            }

            $("body").animate({
                scrollTop: 0
            });

        });

        $('.next').click(function () {

            saveExamDraft(swiper.activeIndex);
            swiper.slideNext();

        })

        $('.prev').click(function () {
            swiper.slidePrev();
        });

        $('.backicn').click(function () {
            swiper.slidePrev();
        });

        function readAnsImg(input, index) {
            if (input.files && input.files[0]) {
                console.log("within input");
                var reader = new FileReader();
                reader.onload = function (e) {
                    console.log("before pop open");
                    OpenCroperPop('ansImg_' + index, e.target.result);
                }
            } else {
                console.log("no image selected");
            }
            reader.readAsDataURL(input.files[0]);
            $('.ansImg_' + index).val('');
            return false;


            var form_data = new FormData();
            var d_exam_id = localStorage.getItem("draft_exam_id");

            form_data.append("exam_id", d_exam_id);
            form_data.append("wg_tmp_img", $('.ansImg_' + index).prop("files")[0]);
            showLoader();
            $.ajax({
                url: "saveWgTmpImage",
                type: "POST",
                data: form_data,
                enctype: 'multipart/form-data',
                contentType: false,
                cache: false,
                processData: false,
                success: function (rs) {
                    hideLoader();
                    var obj = $.parseJSON(rs);
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        console.log($('.ansImgArea_' + index).length);
                        reader.onload = function (e) {
                            //$('#blah').attr('src', e.target.result);
                            var html = '<div class="imgFld">' +
                                '<img src="' + e.target.result + '">' +
                                '</div>';

                            $('.ansImgArea_' + index).html(html);
                            $('.ansImgArea_' + index).show();
                            $('.ansTxt_' + index).hide();
                            $('.ansAud_' + index).hide();

                            //save image name
                            $('.quest_image_option_' + index).val(obj.filename);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }
            });

        }

        function readURL(input, type = '') {

            if (input.files && input.files[0]) {
                var hash = idGen();

                if (type == 'wg' || type == 'mc' || type == 'pr' || type == 'dd') {

                    var form_data = new FormData();
                    //localStorage.setItem("draft_exam_id",1);
                    var d_exam_id = localStorage.getItem("draft_exam_id");
                    var file = URL.createObjectURL(input.files[0]);

                    if (type == 'mc') {
                        $('.group_img').val('');
                        OpenCroperPop('mc_qst_img', file);
                        $('.cropDImage').attr("onclick", "cropImage('mc_qst_img','" + hash + "')");
                        return false;
                        //form_data.append("wg_tmp_img",$('.mcImg').prop("files")[0]);
                    } else if (type == 'wg') {
                        OpenCroperPop('wg_img', file);
                        $('.cropDImage').attr("onclick", "cropImage('wg_img','" + hash + "')");
                        return false;
                        //form_data.append("wg_tmp_img",$('.wgImg').prop("files")[0]);
                    } else if (type == 'pr') {
                        OpenCroperPop('pr_img', file);
                        $('.cropDImage').attr("onclick", "cropImage('pr_img','" + hash + "')");
                        return false;
                        // form_data.append("wg_tmp_img",$('.prImg').prop("files")[0]);
                    } else if (type == 'dd') {
                        form_data.append("wg_tmp_img", $('.ddImg').prop("files")[0]);
                    }

                    if (type == 'wg') {

                        var html = '<li class="wgimg wgonly" data="' + hash + '"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;wg&#039;,&#039;' + hash + '&#039;,' + hash + ')">X</span>' +
                            '<div class="wgImgCrop"> ' +
                            '<img src="' + file + '">' +
                            '</div>' +
                            '</li>';
                        //
                        var existing_real_image = parseInt(parseInt($('.wg_img_list').find('.wgimg').length) + 1);
                        $('.wg_img_list').find('.tmpImg').remove();
                        $('.wg_img_list').append(html);
                        var placeHolderImg = '';
                        for (var m = 0; m < (4 - existing_real_image); m++) {
                            placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                '<div class="wgImgCrop" onclick="clickWgQImage()">' +
                                '<img src=" {{ asset("images/image.svg")  }} ">' +
                                '</div>' +
                                '</li>';
                            $('.wg_img_list').append(placeHolderImg);
                        }
                        $('.wgImgBx').slideDown();
                        var quest_img = $('.quest_image').val();
                        quest_img = quest_img + ',' + hash;
                        $('.quest_image').val(quest_img);
                        // if image choose make blank other two
                        $('.quest_video').val('');
                        $('.quest_audio').val('');

                    } else if (type == 'mc') {

                        var html = '<li class="wgimg mcimg" data="' + hash + '"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;mc&#039;,&#039;' + hash + '&#039;,' + hash + ')">X</span>' +
                            '<div class="wgImgCrop"> ' +
                            '<img src="' + file + '">' +
                            '</div>' +
                            '</li>';
                        var existing_real_image = parseInt(parseInt($('.mc_img_list').find('.mcimg').length) + 1);
                        $('.mc_img_list').find('.tmpImg').remove();
                        $('.mc_img_list').append(html);
                        var placeHolderImg = '';
                        for (var m = 0; m < (4 - existing_real_image); m++) {
                            placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                '<div class="wgImgCrop" onclick="clickWgQImage(&#039;multiple_choice&#039;)">' +
                                '<img src=" {{ asset("images/image.svg")  }} ">' +
                                '</div>' +
                                '</li>';
                            $('.mc_img_list').append(placeHolderImg);
                        }

                        // $('.mc_img_list').append(html);
                        $('.mcImgBx').slideDown();

                        var quest_img = $('.quest_image').val();
                        quest_img = quest_img + ',' + hash;
                        $('.quest_image').val(quest_img);
                        // if image choose make blank other two
                        $('.quest_video').val('');
                        $('.mcvdoTag').html('');
                        $('.mcVdoBx').slideUp();
                        $('.quest_audio').val('');
                        $('.mcaudTag').html('');
                        $('.mcAudBx').slideUp();

                    } else if (type == 'pr') {
                        var html = '<li class="wgimg primg" data="' + hash + '"> <span class="wgIdlt" onclick="deleteWgTmpImg(&#039;pr&#039;,&#039;' + hash + '&#039;,' + hash + ')">X</span>' +
                            '<div class="wgImgCrop"> ' +
                            '<img src="' + file + '">' +
                            '</div>' +
                            '</li>';
                        // $('.pr_img_list').append(html);
                        $('.prImgBx').slideDown();
                        var quest_img = $('.quest_image').val();
                        quest_img = quest_img + ',' + hash;
                        $('.quest_image').val(quest_img);
                        // if image choose make blank other two
                        $('.quest_video').val('');
                        $('.prvdoTag').html('');
                        $('.prVdoBx').slideUp();
                        $('.quest_audio').val('');
                        $('.praudTag').html('');
                        $('.prAudBx').slideUp();
                    }
                    /*else if (type == 'dd') {
                        console.log("image=", obj.filename);
                        $('.quest_image').val(obj.filename);
                        $('.ddImageDisplay').html('<span class="wgIdlt" onclick="removeDDQsnImg()">X</span>' +
                            '<img src="api/uploads/' + obj.filename + '">');

                        var tmpImg = new Image();
                        tmpImg.src = 'api/uploads/' + obj.filename; //or  document.images[i].src;
                        $(tmpImg).one('load', function() {
                            $('#canvasArea').attr("height", tmpImg.height);
                            $('#canvasArea').attr("width", tmpImg.width);
                        });
                    }*/
                    //URL.revokeObjectURL(file);


                } else { //xm img
                    var file = URL.createObjectURL(input.files[0]);
                    $('#imgInp').val('');
                    OpenCroperPop('quest_icon', file);
                    $('.cropDImage').attr("onclick", "cropImage('quest_icon')");
                    //$('#blah').attr('src', e.target.result);
                    //URL.revokeObjectURL(file);
                }
            }
        }


        function deleteWgTmpImg(type = '', id, name) {
            console.log(id, 'kkk', name);
            swal.fire({
                title: "Are you sure?",
                text: "Are you willing to Delete?",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: 'No, Cancel it! <i class="fas fa-thumbs-down"></i>',
                cancelButtonAriaLabel: 'Thumbs down',
                confirmButtonColor: '#F232A4',
                confirmButtonText: 'Yes, delete it! <i class="fas fa-thumbs-up"></i>',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonColor: '#511285',
                showClass: {popup: 'animate__animated animate__fadeIn'},
                hideClass: {popup: 'animate__animated animate__fadeOut'},
                reverseButtons: true,
            }).then(function (res) {
                if (res.isConfirmed) {
                    questions.getItem(id)
                        .then(function (res) {
                            if (res == null) {
                                temp_question_media_edits.push(id.replace(/.+image/, '').replace('_tmp', ''));
                                return;
                            } else {
                                return questions.removeItem(id);
                            }
                        })
                        .then(function () {
                            var cur_val = $('.quest_image').val();
                            var split_img = cur_val.split(',');
                            split_img = jQuery.grep(split_img, function (value) {
                                return value != id;
                            });
                            var update_img = split_img.join();
                            $('.quest_image').val(update_img);

                            if (type == 'mc') {
                                $('.mcimg').each(function () {
                                    if ($(this).attr("data") == name) {
                                        $(this).remove();
                                    }
                                })

                                var existing_real_image = parseInt(parseInt($('.mc_img_list').find('.mcimg').length) + 1);
                                $('.mc_img_list').find('.tmpImg').remove();
                                //$('.mc_img_list').append(html);
                                var placeHolderImg = '';
                                for (var m = 0; m <= (4 - existing_real_image); m++) {
                                    placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                        '<div class="wgImgCrop" onclick="clickWgQImage(&#039;multiple_choice&#039;)">' +
                                        '<img src=" {{ asset("images/image.svg")  }} ">' +
                                        '</div>' +
                                        '</li>';
                                    $('.mc_img_list').append(placeHolderImg);
                                }
                            } else if (type == 'pr') {
                                $('.primg').each(function () {
                                    if ($(this).attr("data") == name) {
                                        $(this).remove();
                                    }
                                })

                                var existing_real_image = parseInt(parseInt($('.pr_img_list').find('.primg').length) + 1);
                                $('.pr_img_list').find('.tmpImg').remove();
                                //$('.wg_img_list').append(html);
                                var placeHolderImg = '';
                                for (var m = 0; m <= (4 - existing_real_image); m++) {
                                    placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                        '<div class="wgImgCrop" onclick="clickWgQImage()">' +
                                        '<img src=" {{ asset("images/image.svg")  }} ">' +
                                        '</div>' +
                                        '</li>';
                                    $('.pr_img_list').append(placeHolderImg);
                                }
                            } else {
                                $('.wgonly').each(function () {
                                    if ($(this).attr("data") == name) {
                                        console.log($(this).attr("data"));
                                        $(this).remove();
                                    }
                                })
                                var existing_real_image = parseInt(parseInt($('.wg_img_list').find('.wgonly').length) + 1);
                                $('.wg_img_list').find('.tmpImg').remove();
                                //$('.wg_img_list').append(html);
                                var placeHolderImg = '';
                                for (var m = 0; m <= (4 - existing_real_image); m++) {
                                    placeHolderImg = ' <li class="tmpImg"> <span class="wgIdlt">X</span>' +
                                        '<div class="wgImgCrop" onclick="clickWgQImage()">' +
                                        '<img src=" {{ asset("images/image.svg")  }} ">' +
                                        '</div>' +
                                        '</li>';
                                    $('.wg_img_list').append(placeHolderImg);
                                }
                            }
                        })

                }
            });
        }

        $('#showWgImg').change(function () {
            readURL(this, 'wg');
        })
        $('#showMcImg').change(function () {
            readURL(this, "mc");
        })
        $('#showPrImg').change(function () {
            readURL(this, "pr");
        })
        $("#imgInp").change(function () {
            readURL(this);
        });
        $('#showDdImg').change(function () {
            console.log("dd");
            readURL(this, 'dd');
        });
        $(".introImg").change(function () {
            //upload image intro draft
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.intro_image').val('');
                    OpenCroperPop('intro_image', e.target.result);
                }
            }
            reader.readAsDataURL(this.files[0]);
            // saveDataPopIntro('image');
        });

        function chooseImage() {
            $('#imgInp').click();
        }

        function getExtraPreqField(val) {
            if (val != 0) {
                $('.preqVal').slideDown();
                if (val == 1) {
                    $('.select-exam').show();
                    $('.input-g').hide();
                } else if (val == 2) {
                    $('.select-exam').hide();
                    $('.input-g').show();
                }
                //$('.swiper-container').css('height',"calc(100vh + 150px)");
                //$('.swiper-container').css("background","#F4F4F4");
            } else {
                $('.preqVal').slideUp();
                //$('.swiper-container').css('height',"calc(100vh)")
            }
            if (val == 1) {
                $('.preq_value').attr("onkeyup", "searchExamSuggestion(this.value)");
            } else {
                $('.preq_value').removeAttr("onkeyup");
            }
        }

        function toggleFieldsBox() {
            if ($('.login_checkBox').is(":checked")) {
                 $('.preq_type').slideDown();
                // $('.fieldsBox').slideDown();
            } else {
                $('.preq_type').slideUp();
                // $('.fieldsBox').slideUp();
            }
        }
        toggleFieldsBox();
        function showtmBx() {
            if ($('.timeLimit').is(":checked")) {
                $('.tmBx').slideDown();
                //$('.swiper-container').css('height',"calc(100vh + 150px)");
                //$('.swiper-container').css("background","#F4F4F4");
            } else {
                $('.tmBx').slideUp();
                //$('.swiper-container').css('height',"calc(100vh - 50px)")
            }
        }

        function clickWgQImage(type = '') {
            console.log(this);
            if (type == "multiple_choice") {
                $(".mcImg").click();
            } else if (type == 'project') {
                if ($('.pr_img_list li').length > 0 && $('.prImgBx').css('display') == 'none') {
                    var cls = '.pr';
                    $(cls + 'VdoBx').slideUp();
                    $(cls + 'ImgBx').slideDown();
                    $(cls + 'AudBx').slideUp();
                    return;
                }
                $('.prImg').click();
            } else if (type == 'dd') {
                console.log("clicking dd");
                $('.ddImg').click();
            } else {
                $('.wgImg').click();
            }

        }


        //show reward image

        function showRewardImage(src, target) {
            var fr = new FileReader();
            $('#showReward').show();
            // when image is loaded, set the src of the image where you want to display it
            fr.onload = function (e) {
                target.src = this.result;
            };
            src.addEventListener("change", function () {
                // fill fr with image data
                fr.readAsDataURL(src.files[0]);
            });
        }

        var src = document.getElementById("rew_image");
        var target = document.getElementById("showReward");
        $('#showReward').hide();
        showRewardImage(src, target);
    </script>

    <script>
        var paragraph_editor
        var project_editor
        $(function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            paragraph_editor = CKEDITOR.replace('editor');
            paragraph_editor.config.baseFloatZIndex = 99999999;
            project_editor = CKEDITOR.replace('project_title');
            project_editor.config.baseFloatZIndex = 99999999;
            $('#0').prop('disabled', true);

            $('#0_wrapper').popover({content: 'You must choose a question image first'});

            $('#newGameForm').validate({
                ignore: [],
                rules: {
                    puzzle_name: {
                        required: true,
                    },
                    thumb: {
                        accept: "image/*"
                    },
                    puzzle: {
                        required: true,
                        accept: "image/*"
                    },
                    keys: {
                        required: true
                    }
                },
                messages: {
                    puzzle_name: {
                        required: "Please specify a name for the game",
                    },
                    thumb: {
                        accept: "Only images are accepted"
                    },
                    puzzle: {
                        required: "Please specify a puzzle picture for the game",
                        accept: "Only images are accepted"
                    },
                    keys: {
                        required: "Please specify at least one area for the game"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                invalidHandler: function (event, validator) {
                    // 'this' refers to the form
                    var errors = validator.errorList;
                    //console.log(validator) //get errorList: validator.errorList
                    for (let i = 0; i < errors.length; i++) {
                        if (errors[i]['element'] == document.getElementById('puzzle_name') || errors[i]['element'] == document.getElementById('thumb')) {
                            document.getElementById('step_0').style.left != "0%" ? move(1, -1) : console.log('');
                            break;
                        } else {
                            if (errors[i]['element'] == document.getElementById('puzzle')) {
                                document.getElementById('step_1').style.left != "0%" ? move(0, 1) : console.log('');
                                break;
                            }
                        }
                    }
                    ;
                },
                submitHandler: function (form) {
                    document.getElementById("loadSpinner").style.visibility = 'visible';
                    form.submit();
                },
            });

        });
    </script>

    <script>

        var puzzle_canvas, originalWidth, originalHeight, stage;
        var puzzle_keys = {};
        var counter = 0;
        var layerback = new Konva.Layer({
            id: "background",
        });
        var layer = new Konva.Layer();

        var selectionRectangle = new Konva.Rect({
            fill: 'rgba(0,0,255,0.5)',
        });

        layerback.add(selectionRectangle);

        var canvasw = document.getElementById('canvas-wrapper');
        var active_selector;

        function resetDDGame() {
            //stage !== undefined && removeAllChoices();
            stage !== undefined && resetKonva();
            togglePieceSelectionArea();
            toggleUploadBtn();
            document.getElementById('puzzle_canvas').style.height = '0px';
            stage = undefined;
        }

        function removeAllChoices() {
            for (let i = 0; i < 4; i++) {
                deleteChoice(i);
            }
        }

        function resetKonva() {
            stage.remove();
            stage.removeChildren();
        }

        function cancelSelection() {
            if (canRestore) {
                create(restoreData[0], restoreData[1], restoreData[2], restoreData[3]);
            } else {
                if (stage.findOne('.selector' + active_selector) != undefined) {
                    deleteChoice(active_selector);
                }
            }
            minimizeChoice();
        }

        function hideAnswerCol(id) {
            document.getElementById("a_preview_col" + id).style.display = 'unset';
            document.getElementById("a_col" + id).style.display = 'none';
        }

        function showAnswerCol(id) {
            document.getElementById("a_preview_col" + id).style.display = 'none';
            document.getElementById("a_col" + id).style.display = 'unset';
            var selector = 'piece_' + id;
            document.getElementById(selector).getElementsByClassName('preview')[0].toBlob(function (blob) {
                //updateImg(blob, selector, 'preview');
            });

        }

        function deleteChoice(id) {
            document.getElementById('col_for_piece_' + id).classList.remove('piece-selected');
            showAnswerCol(id);
            var selector = 'piece' + id;
            if (stage.findOne('.trans' + id) != undefined) {
                stage.findOne('.trans' + id).remove();
                stage.findOne('.selector' + id).remove();
                stage.findOne('#trans_id' + id).remove();
                layer.batchDraw();
            }
            hidePiece(selector);
            delete puzzle_keys[selector];
            counter = getLeastUnaddedTransformer();
        }

        function showTransformers() {
            for (let i = 0; i < layer.find("Transformer").length; i++) {
                if (stage.findOne(".selector" + i) != undefined) {
                    var selector = stage.findOne(".selector" + i);
                    stage.findOne('.selector' + i).visible(true);
                    stage.findOne('.trans' + i).visible(true);
                    layer.batchDraw();
                }
            }
        }

        function hideTransformers(id) {
            for (let i = 0; i < layer.find("Transformer").length; i++) {
                if (i == id && stage.findOne('.selector' + i) != undefined) {
                    stage.findOne('.selector' + i).draggable(true);
                    stage.findOne('.trans' + i).resizeEnabled(true);
                    stage.findOne('.trans' + i).enabledAnchors(['top-left', 'top-center', 'top-right', 'middle-right', 'middle-left', 'bottom-left', 'bottom-center', 'bottom-right']);
                    layer.batchDraw();
                    continue;

                }
                if (stage.findOne(".selector" + i) != undefined) {
                    var selector = stage.findOne(".selector" + i);
                    stage.findOne('.selector' + i).visible(false);
                    stage.findOne('.trans' + i).visible(false);
                    layer.batchDraw();
                }
            }
        }

        var canRestore = false;
        var restoreData = [];
        var currentScrollPos = 0;

        function fullscreenChoice(id) {
            hideTransformers(id);
            currentScrollPos = $('#ddPop').scrollTop();
            $('#ddPop').scrollTop(0);
            document.body.style.overflow = 'hidden';
            $('#ddPop').css('overflow-y', 'hidden');
            canvasw.classList.add('canvas-wrapper');
            puzzle_canvas.classList.add('canvas-active');
            document.getElementsByClassName('control-btns-div')[0].style.display = 'block';
            active_selector = parseInt(id);
            fitStageIntoParentContainer();
            if (stage.findOne('.selector' + id) != undefined) {
                var rect = stage.findOne('.selector' + id)
                canRestore = true;
                restoreData = [rect.position().x, rect.position().y, rect.position().x + rect.width(), rect.position().y + rect.height()];
            } else {
                canRestore = false;
                restoreData = [];
            }
        }

        function minimizeChoice() {
            showTransformers();
            document.body.style.overflow = 'auto';
            $('#ddPop').css('overflow-y', 'auto');
            $('#ddPop').scrollTop(currentScrollPos);
            canvasw.classList.remove('canvas-wrapper');
            puzzle_canvas.classList.remove('canvas-active');
            document.getElementsByClassName('control-btns-div')[0].style.display = 'none';
            fitStageIntoParentContainer();
        }

        function create(x1, y1, x2, y2, cb = null, piece, current) {
            if (layer.find("Transformer").length < {{ intval($max_count) }}) {
                if (Math.abs(x2 - x1) >= 10 && Math.abs(y2 - y1) >= 10) {
                    addSelector(x1, y1, x2, y2);
                    counter = getLeastUnaddedTransformer();
                    console.log(x1);
                    cb != null ? cb(piece, current) : false;
                } else {
                    toastr.info('Choose wider area');
                }
            } else {
                toastr.error('Allowed Maximum of {{$max_count}} pieces is reached')
            }
        }

        function previewThumb(file) {
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                document.getElementById('thumbPreview').src = reader.result;
            }
        }

        function getRelativePointerPosition(node) {
            var transform = node.getAbsoluteTransform().copy();
            // to detect relative position we need to invert transform
            transform.invert();

            // get pointer (say mouse or touch) position
            var pos = node.getStage().getPointerPosition();

            // now we can find relative point
            return transform.point(pos);
        }

        function getLeastUnaddedTransformer() {
            for (let index = 0; index < {{ intval($max_count) }}; index++) {
                if (stage.findOne('.selector' + index) == undefined) {
                    return index;
                    break;
                }
            }
        }

        function showCnvs() {
            puzzle_canvas = document.getElementById("puzzle_canvas");
            originalWidth = puzzle_canvas.offsetWidth;
            originalHeight = puzzle_canvas.offsetHeight;

            stage = new Konva.Stage({
                container: 'puzzle_canvas',
                width: originalWidth,
                height: originalHeight,
            });

            stage.add(layerback);
            stage.add(layer);

            var x1, y1, x2, y2;
            stage.on('mousedown touchstart', (e) => {
                // do nothing if we mousedown on eny shape
                //if (e.target !== stage || e.target !== stage.findOne("#puzzleImg")) {
                //  return;
                //}
                x1 = getRelativePointerPosition(stage.findOne("#puzzleImg")).x;
                y1 = getRelativePointerPosition(stage.findOne("#puzzleImg")).y;
                x2 = getRelativePointerPosition(stage.findOne("#puzzleImg")).x;
                y2 = getRelativePointerPosition(stage.findOne("#puzzleImg")).y;

                selectionRectangle.visible(true);
                selectionRectangle.moveToTop();
                selectionRectangle.width(0);
                selectionRectangle.height(0);
                layerback.batchDraw();
            });

            stage.on('mousemove touchmove', () => {
                // no nothing if we didn't start selection
                if (!selectionRectangle.visible()) {
                    return;
                }
                x2 = getRelativePointerPosition(stage.findOne("#puzzleImg")).x;
                y2 = getRelativePointerPosition(stage.findOne("#puzzleImg")).y;

                selectionRectangle.setAttrs({
                    x: Math.min(x1, x2),
                    y: Math.min(y1, y2),
                    width: Math.abs(x2 - x1),
                    height: Math.abs(y2 - y1),
                });
                layerback.batchDraw();
            });

            stage.on('mouseup touchend', () => {
                initiate_selection(x1, y1, x2, y2);
            });

            fitStageIntoParentContainer();
            window.addEventListener('resize', function () {
                if ($(window).width() != w_width || $(window).height() != w_height) {
                    fitStageIntoParentContainer();
                }
            });
        }

        function initiate_selection(x1, y1, x2, y2) {
            // Do nothing if we didn't start selection
            if (!selectionRectangle.visible()) {
                return;
            }
            // update visibility in timeout, so we can check it in click event
            setTimeout(() => {
                selectionRectangle.visible(false);
                layerback.batchDraw();
                checkSelectionState();
                create(x1, y1, x2, y2);
            });
        }

        function start_puzzle_creation() {
            /*var btn = document.getElementById('0');
             btn.disabled = false;
            $('#0_wrapper').popover('disable');
            btn.click(); */
            var btn = $('.photoSelection button').first();
            if (btn.hasClass('btn-primary')) {
                toggleUploadBtn();
                togglePieceSelectionArea();
            }
            stage !== undefined && removeAllChoices();
            showCnvs();
        }

        function toggleUploadBtn() {
            var btn = $('.photoSelection button').first();
            btn.toggleClass('btn-primary').toggleClass('btn-danger');
            btn.hasClass('btn-primary') ? btn.html('<i class="fas fa-upload"></i> &nbsp; Upload') : btn.html('<i class="fab fa-instagram"></i> &nbsp; Change Photo');
        }

        function togglePieceSelectionArea() {
            var div = $('.pieceSelection');
            div.toggleClass('d-none');
            div.hasClass('d-none') ? div.slideUp() : div.slideDown();
        }

        var base64img;

        function addImg(blob) {

            //step(1);
            //document.getElementById('dynamicContainer').className = document.getElementById('dynamicContainer').className.replace('col-lg-9', 'col-lg-12');
            setTimeout(continue_add, 300);//setTimeout(continue_add, 700);

            function continue_add() {

                var img = blob;

                var reader = new FileReader();
                var puzzleImg = new Image();
                puzzleImg.crossOrigin = "Anonymous";
                puzzleImg.onload = function () {
                    base64img = puzzleImg.cloneNode();
                    URL.revokeObjectURL(this.src);
                    URL.revokeObjectURL(base64img.src);
                    ds.width = puzzleImg.naturalWidth;
                    ds.height = puzzleImg.naturalHeight;
                    var imgCalcHeight = puzzleImg.height * originalWidth / puzzleImg.width;
                    puzzleImg.width = originalWidth;
                    puzzleImg.height = imgCalcHeight;
                    puzzle_canvas.style.height = puzzleImg.height + "px";
                    originalHeight = puzzleImg.height;
                    fitStageIntoParentContainer(false);

                    //puzzle_keys['original_size'] = {
                    //  width: originalWidth,
                    //  height: originalHeight,
                    //}

                    var cnvsimg = new Konva.Image({
                        x: 0,
                        y: 0,
                        image: puzzleImg,
                        id: "puzzleImg",
                    });
                    layerback.add(cnvsimg);
                    layerback.batchDraw();
                    fitStageIntoParentContainer(false);
                    layerback.batchDraw();
                }

                puzzleImg.src = URL.createObjectURL(img);
            }
        }

        function move(current, direction) {
            var goto = parseInt(current) + parseInt(direction);
            document.getElementById("step_" + goto).style.height = '100%';
            var sign = direction * -1 / Math.abs(direction);
            var left = sign * 200;
            document.getElementById("step_" + current).style.left = left + '%';
            //console.log(document.getElementById("step_"+current).style.left);
            document.getElementById("step_" + current).style.height = '0px';
            document.getElementById("step_" + goto).style.left = '0%';
        }

        var ds = document.getElementById('drawingCnvs');
        var ds_ctx = ds.getContext('2d');

        function getKey(x11, y11, x12, y12, selectorNum) {
            ds_ctx.clearRect(0, 0, ds.width, ds.height);
            var scalen = puzzle_canvas.offsetWidth / parseInt(base64img.naturalWidth);
            x11 = x11 / scalen;
            x12 = x12 / scalen;
            y11 = y11 / scalen;
            y12 = y12 / scalen;
            ds_ctx.drawImage(base64img, Math.min(x11, x12), Math.min(y11, y12), Math.abs(x12 - x11), Math.abs(y12 - y11), 0, 0, ds.width, ds.height);
            x11 = x11 * scalen;
            x12 = x12 * scalen;
            y11 = y11 * scalen;
            y12 = y12 * scalen;
            ds.toBlob(function (blob) {
                updateJSON(selectorNum, Math.min(x11, x12), Math.min(y11, y12), Math.abs(x12 - x11), Math.abs(y12 - y11), blob);
            })
        }

        function updateJSON(selector, x, y, width, height, blob) {
            selector = "piece" + selector.toString();
            var current_key = {};
            if (selector in puzzle_keys) {
                current_key = puzzle_keys[selector];
            } else {
                puzzle_keys[selector] = {};
                current_key = puzzle_keys[selector];
            }
            current_key['X'] = x;
            current_key['Y'] = y;
            current_key['width'] = width;
            current_key['height'] = height;
            current_key['scale'] = scale;
            //console.log(puzzle_keys[selector]);
            updateImg(blob, selector);
        }

        function previewPiece(selector, dataURL, img = 'preview') {
            var selector2 = selector.indexOf('piece_') == -1 ? selector.replace('piece', 'piece_') : selector;
            var hide = document.getElementById(selector2).getElementsByClassName('placeholder')[0];
            var show = document.getElementById(selector2).getElementsByClassName(img)[0];
            hide.style.display = "none";
            show.style.display = "inline-block";
            var tmpobj = URL.createObjectURL(dataURL);
            if (img == 'preview') {
                show.width = base64img.naturalWidth;
                show.height = base64img.naturalHeight;
                var showctx = show.getContext('2d');
                var img = new Image();
                img.onload = function () {
                    showctx.drawImage(img, 0, 0);
                    URL.revokeObjectURL(tmpobj);
                }
                img.src = tmpobj;
            } else {
                show.onload = function () {
                    URL.revokeObjectURL(tmpobj);
                }
                show.src = tmpobj;
            }

            //show.width = stage.findOne(selector.replace("piece_", ".trans")).width();
            //var parent = document.getElementById(selector).getElementsByClassName('neutral-bg')[0];
            //parent.className = parent.className.replace('neutral-bg', 'no-bg');
        }

        function hidePiece(selector) {
            var show = document.getElementById(selector.replace('piece', 'piece_')).getElementsByClassName('placeholder')[0];
            var hide = document.getElementById(selector.replace('piece', 'piece_')).getElementsByClassName('preview')[0];
            hide.style.display = "none";
            show.style.display = "inline-block";
            console.log('kkjk');
            //var parent = document.getElementById(selector).getElementsByClassName('no-bg')[0];
            //parent.className = parent.className.replace('no-bg', 'neutral-bg');
        }

        function updateImg(blob, selector, img = 'preview') {
            create_json();
            previewPiece(selector.toString(), blob, img);
        }

        function updateImgfromFile(file, selector, img = 'a_preview') {
            var id = 'Puzzle' + getQuestionsListCount('.question_lists') + '_pieces_piece' + selector.replace('selector', '').replace('.', '');
            options_media[id] = 'image'; //options_media is a global variable defined in index.js above function updateQuestionIfExists
            updateImg(file, selector, img);
        }

        function updateTxt(txt, selector, img = 'a_preview') {
            puzzle_keys[selector]['text'] = txt;
            create_json();
            var scalen = puzzle_canvas.offsetWidth / parseInt(stage.findOne('#puzzleImg').image().naturalWidth);
            var width = puzzle_keys[selector]['width'] / scalen;
            var height = puzzle_keys[selector]['height'] / scalen;
            width = width < 200 ? 200 : width;
            height = height < 200 ? 200 : height;
            var caption = new Konva.Text({
                x: 0,
                y: 0,
                text: txt,
                fontSize: 28,
                fontFamily: 'Calibri',
                fill: '#555',
                width: width,
                height: height,
                padding: 10,
                align: 'center',
                verticalAlign: 'middle',
            });
            var cnvs = caption.toCanvas();
            cnvs.toBlob(function (blob) {
                updateImg(blob, selector, img);
            });
        }

        function rectCvr(checked, selector) {
            if (checked) {
                puzzle_keys[selector]['hide_origin'] = true;
                create_json();
                stage.findOne('.' + selector.replace("piece", "selector")).fill("white");
                stage.batchDraw();
            } else {
                puzzle_keys[selector]['hide_origin'] = false;
                create_json();
                stage.findOne('.' + selector.replace("piece", "selector")).fill("transparent");//stage.findOne("#"+selector.replace("piece_","trans_id")).moveToTop();
                stage.batchDraw();
            }
        }

        function create_json() {
            for (const key in puzzle_keys) {
                var inputClass = parseInt(key.replace('piece', '')) + 1;
                inputClass = ".target_" + inputClass;
                var dataInputClass = inputClass + '_imgdata';
                if (key == 'piece0') {
                    puzzle_keys[key]['original_size'] = {
                        width: originalWidth,
                        height: originalHeight,
                    }
                }
                $(dataInputClass).val(JSON.stringify(puzzle_keys[key]));
            }
            document.getElementById('keys').textContent = JSON.stringify(puzzle_keys);
        }

        function step(direction) {
            var relaxedeles = document.getElementsByClassName("relaxed");
            var hiddeneles;
            var newCls = "pushed";
            var assignCls = "pulled";

            if (direction == 1) {
                hiddeneles = document.getElementsByClassName("pushed");
                newCls = "pushed";
                assignCls = "pulled";
            } else {
                hiddeneles = document.getElementsByClassName("pulled");
                newCls = "pulled";
                assignCls = "pushed";
            }

            for (let i = 0; i < relaxedeles.length; i++) {
                relaxedeles[i].style.marginLeft = "0%";
                //relaxedeles[i].className = relaxedeles[i].className.replace('relaxed', assignCls);
                setTimeout(function () {
                    relaxedeles[i].style.marginLeft = "-200%";
                    relaxedeles[i].className = relaxedeles[i].className.replace('relaxed', assignCls);

                }, 320);
            }
            for (let i = 0; i < hiddeneles.length; i++) {
                hiddeneles[i].style.marginLeft = "200%";
                setTimeout(function () {
                    hiddeneles[i].style.marginLeft = "0%";
                    hiddeneles[i].className = hiddeneles[i].className.replace(newCls, 'relaxed');
                }, 310);
            }
        }

        function checkSelectionState() {
            if (canvasw.classList.contains('canvas-wrapper')) {
                if (stage.findOne('.trans' + active_selector) != undefined) {
                    stage.findOne('.trans' + active_selector).remove();
                    stage.findOne('.selector' + active_selector).remove();
                    stage.findOne('#trans_id' + active_selector).remove();
                }
                return active_selector;
            } else {
                return counter;
            }
        }

        function addSelector(x1, y1, x2, y2) {

            var current_transformer = checkSelectionState();

            var rect1 = new Konva.Rect({
                x: Math.min(x1, x2),
                y: Math.min(y1, y2),
                name: "selector" + current_transformer,
                width: Math.abs(x2 - x1),
                height: Math.abs(y2 - y1),
                draggable: true,
            });
            var tr = new Konva.Transformer({
                resizeEnabled: true,
                rotateEnabled: false,
                name: 'trans' + current_transformer,
            });

            var current = parseInt(rect1.name().replace("selector", ""));

            for (let i = 0; i < layer.find("Transformer").length; i++) {
                layer.find("Transformer")[i].resizeEnabled(false);
                layer.find("Rect")[i].draggable(false);
            }

            rect1.on('mouseover', function () {
                document.body.style.cursor = 'pointer';
            });

            rect1.on('mouseout', function () {
                document.body.style.cursor = 'default';
            });

            rect1.on('mousedown touchmove touchstart drag dragstart ', function (evt) {
                evt.cancelBubble = true;
            });

            rect1.on('click tap', function (evt) {
                evt.cancelBeubble = true;
                for (var i = 0; i < layer.find("Transformer").length; i++) {
                    var selector = stage.findOne(".selector" + i);
                    stage.findOne('.selector' + i).draggable(false);
                    stage.findOne('.trans' + i).resizeEnabled(false);
                }
                ;
                rect1.draggable(true);
                stage.findOne('.' + rect1.name().replace("selector", "trans")).resizeEnabled(true);
                tr.enabledAnchors(['top-left', 'top-center', 'top-right', 'middle-right', 'middle-left', 'bottom-left', 'bottom-center', 'bottom-right']);
                layer.draw();
            });

            rect1.on('dragend touchend', function (evt) {
                evt.cancelBubble = true;
                getKey(tr.position().x, tr.position().y, tr.width() + tr.position().x, tr.height() + tr.position().y, current);
            });

            rect1.on('transformend', function (evt) {
                evt.cancelBubble = true;
                getKey(tr.position().x, tr.position().y, tr.width() + tr.position().x, tr.height() + tr.position().y, current);
            });

            tr.anchorCornerRadius(25);
            tr.borderDash([6, 2]);
            tr.on('mousedown touchstart touchmove touchend drag dragstart dragend', function (evt) {
                evt.cancelBubble = true;
            });
            tr.on('click tap', function (evt) {
                evt.cancelBubble = true;
                tr.resizeEnabled(true);
                rect1.strokeWidth(0);
            });

            // add the shape to the layer
            layer.add(rect1);
            tr.nodes([rect1]);
            layer.add(tr);
            layer.batchDraw();
            var caption = new Konva.Text({
                x: tr.position().x / scale,
                y: tr.position().y / scale,
                text: current_transformer + 1,
                fontFamily: 'Calibri',
                fontSize: 24,
                fill: 'lime',
                fontStyle: 'bold',
                id: 'trans_id' + current_transformer,
                opacity: 1,
                width: tr.width() / scale,
                height: tr.height() / scale,
                listening: false,
                align: 'center',
                verticalAlign: 'middle',
            });
            layer.add(caption);
            tr.nodes([rect1, caption]);
            layer.add(tr);
            layer.draw();
            document.getElementById('col_for_piece_' + current_transformer).classList.add('piece-selected');
            getKey(tr.position().x, tr.position().y, tr.position().x + tr.width(), tr.position().y + tr.height(), current);
        }

        var scale;
        var w_width = $(window).width()
        var w_height = $(window).height();

        function fitStageIntoParentContainer(resizeImg = true) {
            document.getElementById('adjustSpinner').style.visibility = "visible";
            setTimeout(function () {
                var container = puzzle_canvas;
                // now we need to fit stage into parent
                var containerWidth = container.offsetWidth;
                // to do this we need to scale the stage
                scale = containerWidth / originalWidth;
                stage.width(originalWidth * scale);
                stage.height(originalHeight * scale);
                stage.scale({x: scale, y: scale});
                stage.batchDraw();
                puzzle_canvas.style.height = stage.height() + "px";
                document.getElementById('adjustSpinner').style.visibility = "hidden";
            }, 1000)
        }

        // get references to HTML elements
        const startBtn = document.getElementById("startBtn");
        const stopBtn = document.getElementById("stopBtn");
        const timer = document.getElementById("timer");
        const audioPlayer = document.getElementById("audioPlayer");
        const inputFile = document.getElementById("audio_file_upload");

        // initialize media recorder and audio chunks
        let mediaRecorder;
        let chunks = [];

        // event listener for start button
        startBtn.addEventListener("click", function () {
            // disable start button
            startBtn.disabled = true;

            // enable stop button
            stopBtn.disabled = false;

            // request access to user's microphone
            navigator.mediaDevices
                .getUserMedia({ audio: true })
                .then(function (stream) {
                    // create media recorder with audio stream
                    mediaRecorder = new MediaRecorder(stream);

                    // event listener for dataavailable event
                    mediaRecorder.addEventListener("dataavailable", function (e) {
                        chunks.push(e.data);
                    });

                    // start recording and timer
                    mediaRecorder.start();
                    let startTime = Date.now();
                    let timerInterval = setInterval(function () {
                        let elapsedTime = Date.now() - startTime;
                        let minutes = Math.floor(elapsedTime / 60000);
                        let seconds = Math.floor((elapsedTime % 60000) / 1000);
                        timer.textContent =
                            ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);
                    }, 1000);

                    // event listener for stop button
                    stopBtn.addEventListener("click", function () {
                        // disable stop button
                        stopBtn.disabled = true;

                        // stop recording and timer
                        mediaRecorder.stop();
                        clearInterval(timerInterval);
                        timer.textContent = "00:00";

                        // enable start button
                        startBtn.disabled = false;
                    });

                    // event listener for stop event (after recording)
                    mediaRecorder.addEventListener("stop", function () {
                        // create blob from audio chunks
                        let blob = new Blob(chunks, { type: "audio/wav" });

                        // create object URL from blob and set as audio player source
                        audioPlayer.src = URL.createObjectURL(blob);

                        // create file from blob and set as input file value
                        let file = new File([blob], "recording.wav");
                      recorded_audio = file

                        chunks = []; // clear audio chunks for next recording
                    });
                })
                .catch(function (err) {
                    console.log("Error: " + err);
                });
        });



    </script>
@endsection

