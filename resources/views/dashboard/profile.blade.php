@extends('dashboard.layouts.master')

@section('css')

    <style>
        .pcommentBox img {
            width: 100%;
        }

        .rewPop {
            display: none;
        }

        .qsTxt5 img {
            width: 20px;
        }

    </style>
    @if (count($project_submits->pluck('pending')->filter()->toArray()) >= 1)
        <style>
            .Picn { /* previously named $style*/
                position: absolute;
                color: #f232a4;
                font-size: 12px;
                display: inline-block;
                width: 16px;
                line-height: 18px;
                background: #fff !important;
                text-align: center;
                border-radius: 50%;
                top: 10px;
                right: 50%;
                transform: translateX(35px);
                height: 16px;
            }

            .another_cls { /* previously named $style2*/
                position: absolute;
                color: #fff;
                font-size: 12px;
                display: inline-block;
                width: 16px;
                line-height: 18px;
                background: #f232a4 !important;
                text-align: center;
                border-radius: 50%;
                top: 10px;
                right: 50%;
                transform: translateX(35px);
                height: 16px;
            }
        </style>
    @else
        <style>
            .another_cls { /* previously named $style2*/
                display: none;
                height: 0px;
            }
        </style>
    @endif
@endsection

@section('additonal_classes_for_main_div')
    overflow-hidden
@endsection
@section('content')

    <header class="clrhdr">
        <div class="leftIcn">
            <div class="mnuicn">MENU</div>
        </div>

        <div class="pgnme">
            My Profile
        </div>

        <div class="rgtIcn">
            <!-- <div class="pprplnicn">QR</div> -->
        </div>
    </header>

    <section class="pgTop">
        <div class="ptBox1">
            <div class="ptb1">
                <?php
                ?>
                <div class="ptbImg"><img style="width: 100%" src="{{ $user_avatar }}"/></div>
                <div class="ptbTxt">{{ $user->name }}</div>
            </div>
            <div class="ptb2">
                <ul>
                    <li>
                        <div class="ptb2Icn" style="font-weight:bold; font-size:25px">{{ $user->stars }}</div>
                        <div class="ptb2Txt">Stars</div>
                    </li>
                    <li><a href="{{ route('groups.index') }}"
                           style="color:#000">
                            <div class="ptb2Icn"
                                 style="font-weight:bold; font-size:25px"> {{ $user->following_count }}</div>
                            <div class="ptb2Txt">Following</div>
                        </a>
                    </li>
                    <li style="vertical-align: middle">
                        <div class="qricn" style="vertical-align: middle; background-color: black; border-radius: 8px"
                        >QR
                        </div>
                    </li>
                </ul>
            </div>
            <div class="ptb3">
                <a href="{{ route('profile.edit') }}"><span class="profbtn">Edit my profile</span></a>
            </div>
        </div>
        <div class="ptBox2">
            <ul class="ulicn">
                @if(auth()->user()->type == 1)
                    <!-- TODO: try to figure out what clone is -->
                    <li class="actv" onclick="tabMove(this,'my_quest')">
                        <a href="#" style="color:#4181a7">
                            <span class="icnm icni"></span><span class="icnTxt">Lessons</span>
                        </a>
                    </li>
                    <li onclick="tabMove(this,'my_group')" class="group_li">
                        <a href="#group" style="color:#4181a7">
                            <span class="icng icni"></span><span class="icnTxt">My groups</span>
                        </a>
                    </li>
                @endif
                @if ($id == Auth::id())
                    <li onclick="tabMove(this,'history')"
                        class="history_li {{auth()->user()->type == 0 ? 'actv' : ''}}">
                        <a href="#history" style="color:#4181a7"><span
                                class="icnh icni"></span><span class="icnTxt">History</span>
                        </a>
                    </li>
                    @if(auth()->user()->type == 1)
                        <li onclick="tabMove(this,'projects')" class="project_li ">
                            <a href="#project" style="color:#4181a7">
                                <span class="icnp icni"></span><span class="icnTxt">Projects</span><span
                                    class="another_cls"
                                    class="icnp">P</span>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
    </section>


    <section class="usrpg">
        @if(auth()->user()->type == 1)
            <aside class="setN7 gpSetHS" style="display:none">
                <!-- <div class="setNbtn"><span class="btNblu" onclick="javascript:$('.gpSetHS').hide(); $('.xmSetHS').show();">SHOW QUESTS</span></div> -->
                <div class="fIcnBx">
                    <aside class="flTitl">
                        #Groups: {{ count($groups) }}
                    </aside>
                </div>
                <aside class="qsLst">
                    <input type="hidden" id="g_pageno" value="1">
                    <ul id="group_list">

                        @foreach ($groups as $group)
                            <li class="rmv_{{ $group->id }}">
                                <div class="qsWbx" style="padding: 0 10px 0 0;">

                                    <div class="qsRow1 q4c">
                                        <a href="{{ route('groups.show', ['group' => $group->id]) }}">
                                            <span class="clqBx" style="right: 120px;"></span>
                                        </a>
                                        <div class="qs1img" style="border-radius: 5px 0 0 5px;">
                                            <img
                                                src="@if ($group->image != null || $group->image != '') {{ url(Storage::url($group->image)) }} @else {{ $default_grp_img }} @endif">
                                        </div>
                                        <div class="qs1Rbx">
                                            {{--                                        <div class="plan_i" onclick="showGroupNotiPop({{ $group->id }})"--}}
                                            {{--                                             style="background: url({{ url('images/gbrdcst.svg') }}) no-repeat center center; margin:5px 0 6px 0">--}}
                                            {{--                                        </div>--}}
                                            <div class="qsTxt2 q4a">
                                                CODE: <b>{{ $group->code }}</b> <span class="q4Pass">Password:
                                            <b>{{ $group->password }}</b></span>
                                            </div>
                                            <div class="q4d">
                                                {{ $group->title }}
                                            </div>


                                            <div class="s7btm">
                                                <aside class="q4f">
                                                    <b><{{ $group->exams_count }}</b> Quest <br/>
                                                    <b>{{ $group->followers_count }}</b> Followers
                                                </aside>

                                                <aside class="setRnew q4e">
                                            <span class="lckIcns">
                                            @if($group->private==false)
                                                    <img src="{{ url('images/lock_gray.svg') }}"
                                                         onclick="makeGroupPrivacy(this,0,'{{ $group->id }}', '{{ route('groups.togglePrivacy', ['group' => $group->id]) }}')">
                                                @else
                                                    <img src="{{ url('images/lock_pink.svg') }}"
                                                         onclick="makeGroupPrivacy(this,1,'{{ $group->id }}', '{{ route('groups.togglePrivacy', ['group' => $group->id]) }}')"></span>
                                                    @endif
                                                    <span class="btnIc"
                                                          onclick="shareData('{{ $group->title }}','{{ $group->title }} - {{ $user->id }}','{{route('groups.show', $group->id) }}')"><img
                                                            src="{{ url('images/share_pink.svg') }}"></span>
                                                    <span class="btnIc q4Dot"><img
                                                            src="{{ url('images/dot_pink.svg') }}">
                                                <div class="dtMlst">
                                                    <ul class="dmlist">
                                                        <li>
                                                            <a href="{{ route('groups.edit', ['group' => $group->id]) }}">
                                                                <span class="dtmi dt_info"
                                                                      style="background: url(<?=$base_url?>/images/edtGrp.svg) no-repeat center center; background-size: contain;"
                                                                      onclick="showPopGropInfo('{{ $group->id }}')"></span>
                                                                <span style="color:#4181a7">Edit</span>
                                                            </a>
                                                        </li>
                                                        <li onclick="showPopGropInfo('{{ $group->id }}')">
                                                            <span class="dtmi dt_info"></span>
                                                            <span>Info</span>
                                                        </li>
                                                        <li>
                                                            <span class="dtmi dt_cpy"
                                                                  onclick="copyGroupInfo('{{ $group->title }}','{{ $group->id }}')"></span>
                                                            <span
                                                                onclick="copyGroupInfo('{{ $group->title }}','{{ $group->id }}')">Copy
                                                                Info</span>
                                                        </li>
                                                        <li onclick="deletGroup('{{ route('groups.destroy', ['group' => $group->id]) }}', '{{ $group->id }}')">
                                                            <!-- <a href="<?= $base_url ?>delete_group/{{ $group->id }}" onclick="return confirm('Are you sure?')"> -->
                                                            <span class="dtmi dt_dlt"></span>
                                                            <span style="color:#4181a7">Delete</span>
                                                            <!-- </a> -->
                                                        </li>
                                                    </ul>
                                                </div>
                                            </span>
                                                </aside>
                                            </div>

                                        </div>


                                    </div>

                                </div>
                            </li>
                        @endforeach


                    </ul>
                    <div class="gloader"></div>
                </aside>
            </aside>

            <aside class="setN4 xmSetHS" style="{{auth()->user()->type == 0 ? 'display:none' : ''}}">
                <!-- <div class="setNbtn"><span class="btNpink" onclick="javascript:$('.gpSetHS').show(); $('.xmSetHS').hide();">SHOW GROUPS</span></div> -->
                <div class="fIcnBx">
                    <aside class="flTitl">
                        #Quests: {{ count($exams) }}
                    </aside>
                </div>
                <aside class="qsLst">
                    <input type="hidden" id="q_pageno" value="1">
                    <ul id="quest_list">
                        @foreach ($exams as $exam)
                            <li>
                                <div class="qsWbx" style="padding: 0 10px 0 0;">

                                    <div class="qsRow1 q4c">
                                        {{--                                <a href="{{ route('exams.edit', ['exam' => $exam->id]) }}"> <span class="clqBx"></span></a>--}}
                                        <a href="{{ route('exams.intro', ['exam' => $exam->id]) }}"> <span
                                                class="clqBx"></span></a>
                                        <div class="qs1img" style="border-radius: 5px 0 0 5px;">
                                            <img
                                                src="@if ($exam->icon != null || $exam->icon != ''){{ Storage::url($exam->icon, true) }}@else{{ $default_grp_img }}@endif">
                                        </div>
                                        <div class="qs1Rbx">
                                            <div class="qsTxt2 q4a" style="color:#000">

                                                CODE: <b>{{ $exam->id + 1000 }}</b>
                                                <span
                                                    class="badge badge-pill badge-danger">{{ $exam->draft ? 'DRAFT' : '' }}</span>
                                            </div>
                                            <div class="qsTxt3 q4b" style="margin-bottom: 5px">
                                                {{ $exam->title }}
                                            </div>
                                            <div class="exam-pass">
                                                @if($exam->have_preq_exam)
                                                    <img src="{{asset('images/lock.png')}}" width="20" alt="">
                                                    <span
                                                        style="font-size: 12px">Exam number: {{$exam->have_preq_exam}}</span>
                                                @endif
                                            </div>

                                        </div>
                                        <!-- </a> -->
                                        <aside class="setRnew q4d">
                                    <span class="qricn"
                                          style="vertical-align: middle;background-color: #4181a7;border-radius: 8px"
                                          data-original-title="" title="">QR</span>
                                            <span class="btnIc q4Dot"><img
                                                    src="{{ url('images/share_v.svg') }}">
                                          <div class="dtMlst" style="margin-top: 30px">
                                            <ul class="dmlist">
                                                <li onclick="qrCodeData('{!!  base64_encode(QrCode::format('png')->size(200)
                    ->generate(route('exams.intro', ['exam' => $exam->id]))); !!}', '{{$exam->title}}')">
                                                    QR code
                                                </li>
                                                <li onclick="shareData('{{ $exam->title }}','{{ $exam->title }} - {{ $user->name }}',
                                        '{{ route('exams.intro', ['exam' => $exam->id]) }}')">
                                                   Copy Link!
                                                </li>

                                            </ul>
                                        </div>

                                    </span>
                                            <span class="btnIc q4Dot"><img src="{{ url('images/dot_vio.svg') }}">
                                        <div class="dtMlst">
                                            <ul class="dmlist">
                                                <li>
                                                    <a href="{{ route('exams.edit', ['exam' => $exam->id]) }}">
                                                      <span class="dtmi dt_play"
                                                            style="background: url({{ url('images/edtGrp.svg') }}) no-repeat center center; background-size: contain;"></span>
                                                        <span style="color: #4181a7">Edit</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('exams.show', ['exam' => $exam->id]) }}">
                                                        <span class="dtmi dt_info"></span>
                                                        <span>Info</span>
                                                    </a>
                                                </li>
{{--                                                 <li>--}}
                                                {{--                                                    @if($exam->chat==false)--}}
                                                {{--                                                <a href="<?=$base_url?>enable-chat-exam/{{ $exam->id }}">--}}
                                                {{--                                                    <span class="dtmi dt_chat"></span>--}}
                                                {{--                                                    <span style="color: #4181a7">Chat on</span>--}}
                                                {{--                                                </a>--}}
                                                {{--                                                @else--}}
                                                {{--                                                <a href="<?=$base_url?>disable-chat-exam/{{ $exam->id }}">--}}
                                                {{--                                                            <span class="dtmi dt_chat"></span>--}}
                                                {{--                                                            <span style="color: #4181a7">Chat off</span>--}}
                                                {{--                                                        </a>--}}

                                                {{--                                                </li>--}}
                                                {{--                                                @endif--}}
                                                {{--                                                <li onclick="exportExam({{ $exam->id }})">--}}

                                                {{--                                                    <span class="dtmi dt_expo"></span>--}}
                                                {{--                                                    <span>Export</span>--}}

                                                {{--                                                </li>--}}
                                                <li onclick="publishExamToGroup({{ $exam->id }})">
                                                    <span class="dtmi dt_grup"></span>
                                                    <span>To group</span>
                                                </li>
                                                <li>
                                                  <form action="{{ route('exams.destroy', ['exam' => $exam->id]) }}"
                                                        method="POST">
                                                               @csrf
                                                      @method('DELETE')
                                                    <button onclick="return confirm('Are you sure?')" type="submit"
                                                            style="background: none; border: 0">
                                                        <span class="dtmi dt_dlt"></span>
                                                        <span style="color: #4181a7">Delete</span>
                                                    </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </span>
                                        </aside>
                                    </div>

                                </div>
                            </li>
                        @endforeach

                    </ul>
                    <div class="qloader"></div>
                </aside>
            </aside>
        @endif
        @if ($id == Auth::id())
            <aside class="setN3 hstryTab" style="{{auth()->user()->type == 1 ? 'display:none' : ''}}">
                <!-- history -->
                <div class="fltrBr">

                    <div class="fIcnBx">
                        <aside class="dropSet">
                            <span class="fltricn"></span>
                            <div class="fList">
                                <ul class="chkList">
                                    <li class="set1">
                                        <input type="checkbox" id="set1" name="filter" checked
                                               onchange="filterHistory('all')">
                                        <label for="set1"><span>All</span></label>
                                    </li>
                                    <li class="set2">
                                        <input type="checkbox" id="set2" name="filter" checked
                                               onchange="filterHistory('coupons')">
                                        <label for="set2"><span>Coupons</span></label>
                                    </li>
                                    <li class="set3">
                                        <input type="checkbox" id="set3" name="filter" checked
                                               onchange="filterHistory('cenrtificate')">
                                        <label for="set3"><span>Certificate</span></label>
                                    </li>
                                    <li class="set4">
                                        <input type="checkbox" id="set4" name="filter" checked
                                               onchange="filterHistory('others')">
                                        <label for="set4"><span>Others</span></label>
                                    </li>
                                    <li class="set5">
                                        <input type="checkbox" id="set5" name="filter" checked
                                               onchange="filterHistory('fail')">
                                        <label for="set5"><span>Fail</span></label>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                    <!--fIcnBx-->

                    <aside class="qsLst">
                        <input type="hidden" id="h_pageno" value="1">
                        <ul id="history_list">
                            <!--check only project scene-->
                            @php
                                $history_latest = new \Illuminate\Support\Collection;
                                $history_latest = $history_latest->concat($user_submitted_projects)->concat($solved_exams);
                                foreach ($exams as $exam){
                                    $exam->project_submits->map(
                                        function($item, $k) {
                                            return $item->marked_by_me = true;
                                        }
                                    );
                                    $history_latest = $history_latest->concat($exam->project_submits);
                                }
                                $history_latest = $history_latest->sortByDesc( function($hi) {
                                    if ($hi->getTable() == 'exams') {
                                        return $hi->analysis->created_at;
                                    }
                                    return $hi->created_at;
                                });
                                //dd($history_latest);
                            @endphp
                            @foreach ($history_latest as $hItm)
                                @if ($hItm->getTable() == 'exams')
                                    @php
                                        $exam= $hItm;
                                        $percentage = $exam->analysis->percentage;
                                        $xm_status='fail';
                                        if($percentage>=$exam->pass_percentage){
                                            $xm_status='pass';
                                        }
                                        $sts = '';
                                        $has_coupon=0;
                                        foreach($exam->coupons as $coupon) {
                                            $has_coupon = $coupon->coupon_data;
                                            $coupon_icon = $exam->coupons['coupon_type'];
                                            if($xm_status=='pass'){
                                                if($has_coupon){
                                                    $sts='coupon';
                                                }else{
                                                    $sts = $coupon_icon;
                                                }
                                            }else if($xm_status=='fail'){
                                                $sts=0;
                                            }
                                            break;
                                        }
                                    @endphp
                                    <li status="{{ $sts }}" class="hstBx">
                                        <div class="qsWbx">
                                            <div class="qsRow1">
                                                <span class="clqBx" style="right: 210px"
                                                      onclick="showHistryReward('{{ route('exams.showReward', ['exam' => $exam->id]) }}')"></span>
                                                <div class="qs1img">
                                                    <img
                                                        src="@if ($exam->icon != null || $exam->icon != '') {{ Storage::url($exam->icon, true) }} @else {{ $default_grp_img }} @endif">
                                                </div>
                                                <div class="qs1Rbx">
                                                    <div class="qsTxt2">
                                                        CODE: <b>{{ $exam->id + 1000 }}</b>
                                                        <div class="dteBx">{{ $exam->analysis->created_at }}</div>
                                                    </div>
                                                    <div class="qsTxt3">
                                                        {{ $exam->title }}
                                                    </div>
                                                    <div class="qsTxt4">
                                                        <div class="qsTxt5">
                                                            @if($percentage>=90 && $percentage<=100)
                                                                <img src="{{ url('images/star.svg') }}"/> <img
                                                                    src="{{ url('images/star.svg') }}"/> <img
                                                                    src="{{ url('images/star.svg') }}"/>
                                                            @elseif($percentage>=75 && $percentage<=89)
                                                                <img src="{{ url('images/star.svg') }}"/> <img
                                                                    src="{{ url('images/star.svg') }}"/> <img
                                                                    src="{{ url('images/star_b.svg') }}"/>
                                                            @elseif($percentage>=60 && $percentage<=74)
                                                                <img src="{{ url('images/star.svg') }}"/> <img
                                                                    src="{{ url('images/star_b.svg') }}"/> <img
                                                                    src="{{ url('images/star_b.svg') }}"/>
                                                            @else
                                                                <img src="{{ url('images/star_b.svg') }}"/> <img
                                                                    src="{{ url('images/star_b.svg') }}"/> <img
                                                                    src="{{ url('images/star_b.svg') }}"/>
                                                            @endif
                                                            <!-- <img src="{{ url('images/star.svg') }}"> <img src="{{ url('images/star.svg') }}"> <img src="{{ url('images/star_b.svg') }}"> -->
                                                        </div>
                                                        <div class="qsTxt6">
                                                            {{ $percentage }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="qsRow2">
                                                <div class="qs2Lbx">
                                                    <div class="qsTxt1">
                                                        By: <b>{{ $exam->owner->name}}</b>
                                                    </div>
                                                </div>
                                                <div class="qs2Rbx">
                                                    @if($percentage>=$exam->pass_percentage /*check for project submit*/ )
                                                        @if(count($exam->project_submits) > 0)
                                                            @foreach ($exam->project_submits as $ps)
                                                                @if($ps->pending)
                                                                    <span class="pasT" style="background: #B0B0B0">Pending</span>
                                                                @elseif($ps->remark==1)
                                                                    @if($ps->remark_notes!='')
                                                                        <span class="cmmnt_i"
                                                                              onclick="openComment('{{ route('exams.project_submits.showComment', ['projectSubmit' => $ps->id ]) }}')">comment</span>
                                                                    @endif
                                                                    <span class="pass_i"
                                                                          onclick="showHistryReward('{{ route('exams.showReward', ['exam' => $exam->id]) }}')">PASS</span>
                                                                @elseif($ps->remark==0)
                                                                    @if($ps->remark_notes!='')
                                                                        <span class="cmmnt_i"
                                                                              onclick="openComment('{{ route('exams.project_submits.showComment', ['projectSubmit' => $ps->id ]) }}')">comment</span>
                                                                    @endif
                                                                    <a href="{{ route('exams.project_submits.create', ['exam' => $exam->id]) }}"><span
                                                                            class="pasT" style="background: #66b8d9">Re-Submit</span></a>
                                                                @endif
                                                                @break
                                                            @endforeach
                                                        @else
                                                            @if($exam->projects_count > 0 /*check if exam has project*/)
                                                                <a href="{{ route('exams.project_submits.create', ['exam' => $exam->id]) }}"><span
                                                                        class="pasT" style="background: #66b8d9">Submit project</span></a>
                                                            @else
                                                                <span class="pass_i"
                                                                      onclick="showHistryReward('{{ route('exams.showReward', ['exam' => $exam->id]) }}')">PASS</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="failT">FAIL</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @elseif (isset($hItm->marked_by_me))
                                    @php
                                        $project = $hItm;
                                        $exam = $project->exam;
                                    @endphp
                                    @if ($project->remark != null && $project->pending == 0 )
                                        <li status="{{ $project->remark }}">
                                            <div class="qsWbx">
                                                <div class="prjB1">
                                                    <a style="color:#5939C6"
                                                       href="{{ route('exams.project_submits.show', ['exam' => $exam->id, 'project_submit' => $project->id]) }}">
                                                        {{ $exam->title }}
                                                    </a>
                                                </div>
                                                <div class="prjB2">
                                                    <a style="color:#000000"
                                                       href="{{ route('exams.project_submits.show', ['exam' => $exam->id, 'project_submit' => $project->id]) }}">
                                                        {{ $project->student->name }}
                                                    </a>
                                                </div>
                                                <div class="prjB3">
                                                    @if($project->remark==1)
                                                        Marked as: &nbsp;<span class="pass_i">Success</span>
                                                    @elseif($project->remark==0)
                                                        Marked as: &nbsp;<span class="failT">Fail</span>
                                                    @endif
                                                    @if($project->remark_notes!='')
                                                        <span class="cmmnt_i"
                                                              onclick="openComment('{{ route('exams.project_submits.showComment', ['projectSubmit' => $project->id ]) }}')">comment</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                @elseif (!isset($hItm->marked_by_me))
                                    @php
                                        $project = $hItm;
                                        $parent_exam = $project->exam;
                                    @endphp
                                    @if($parent_exam)
                                        <li status="{{ $project->remark }}" class="hstBx">
                                            <div class="qsWbx">
                                                <div class="qsRow1">
                                                    <!-- <span class="clqBx" ></span> -->
                                                    <div class="qs1img">
                                                        <img
                                                            src="@if ($parent_exam && ($parent_exam->icon != null || $parent_exam->icon != '')){{ Storage::url($parent_exam->icon, true) }}@else{{ $default_grp_img }}@endif">
                                                    </div>
                                                    <div class="qs1Rbx">
                                                        <div class="qsTxt2">
                                                            CODE: <b>{{ $parent_exam->id + 1000 }}</b>
                                                            <div class="dteBx">{{ $project->created_at }}</div>
                                                        </div>
                                                        <div class="qsTxt3">
                                                            {{ $parent_exam->title }}
                                                        </div>
                                                        <div class="qsTxt4">
                                                            <div class="qsTxt5">

                                                                <!-- <img src="{{ url('images/star.svg') }}"> <img src="{{ url('images/star.svg') }}"> <img src="{{ url('images/star_b.svg') }}"> -->
                                                            </div>
                                                            <div class="qsTxt6">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="qsRow2">
                                                    <div class="qs2Lbx">
                                                        <div class="qsTxt1">
                                                            By: <b>{{ $parent_exam->owner->name}}</b>
                                                        </div>
                                                    </div>
                                                    <div class="qs2Rbx">

                                                        @if($project->remark_== 2 || $project->remark == null)
                                                            <span class="pasT"
                                                                  style="background: #B0B0B0">Pending</span>
                                                        @elseif($project->remark==1)
                                                            @if($project->remark_notes != '' || $project->remark_notes != null )
                                                                <span class="cmmnt_i"
                                                                      onclick="openComment('{{ route('exams.project_submits.showComment', ['projectSubmit' => $project->id ]) }}')">comment</span>
                                                            @endif
                                                            <span class="pass_i"
                                                                  onclick="showHistryReward('{{ route('exams.showReward', ['exam' => $parent_exam->id]) }}')">PASS</span>
                                                        @elseif($project->remark==0)
                                                            @if($project->remark_notes != '' || $project->remark_notes != null )
                                                                <span class="cmmnt_i"
                                                                      onclick="openComment('{{ route('exams.project_submits.showComment', ['projectSubmit' => $project->id ]) }}')">comment</span>
                                                            @endif
                                                            <a href="{{ route('exams.project_submits.create', ['exam' => $parent_exam->id]) }}"><span
                                                                    class="pasT"
                                                                    style="background: #66b8d9">Re-Submit</span></a>
                                                        @endif


                                                        <!-- <span class="pass_i" >PASS</span> -->

                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endif

                            @endforeach
                        </ul>
                        <div class="hloader"></div>
                    </aside>

                </div>
            </aside>
            @if(auth()->user()->type == 1)
                <aside class="setN6 projectTab" style="display:none">
                    <!-- Projects -->

                    <aside class="qsLst">
                        <ul>
                            @foreach ($exams as $exam)
                                @foreach ($exam->project_submits as $project)
                                    @if($project->remark_== null && $project->pending == 1)

                                        <li>
                                            <div class="qsWbx">
                                                <div class="prjB1">
                                                    <a style="color:#5939C6"
                                                       href="{{ route('exams.project_submits.show', ['exam' => $exam->id, 'project_submit' => $project->id]) }}">
                                                        {{ $exam->title }}
                                                    </a>
                                                </div>
                                                <div class="prjB2">
                                                    <a style="color:#000000"
                                                       href="{{ route('exams.project_submits.show', ['exam' => $exam->id, 'project_submit' => $project->id]) }}">
                                                        {{ $project->student->name }}
                                                    </a>
                                                </div>
                                                <div class="prjB3">
                                                    <div class="prjFl"><a href="#"
                                                                          onclick="markSubmission(0, '{{ route('exams.project_submits.update', ['exam' => $exam->id, 'project_submit' => $project->id]) }}')"
                                                                          style="color:#ffff">FAIL</a></div>
                                                    <div class="prjPs"><a href="#"
                                                                          onclick="markSubmission(1, '{{ route('exams.project_submits.update', ['exam' => $exam->id, 'project_submit' => $project->id]) }}')"
                                                                          style="color:#ffff">PASS</a></div>
                                                </div>
                                            </div>

                                        </li>

                                    @endif
                                @endforeach
                            @endforeach


                            <!--//cehck if user has any clone project-->
                        </ul>
                    </aside>

                </aside>
            @endif
        @endif
    </section>


    <footer class="ftrmnu">
        {{--        <div class="fmnuclm">--}}
        {{--            <a href='{{ route('discover') }}'>--}}
        {{--                <div class="icnSrch">SEARCH</div>--}}
        {{--            </a>--}}
        {{--        </div>--}}
        <div class="fmnuclm" style="{{auth()->user()->type == 0 ? 'width:100%' : ''}}">
            <a href="{{ route('home') }}">
                <div class="icnHom"
                     style="background: url(<?=$base_url?>/images/home_icon.svg) no-repeat center center #4181a7">HOME
                </div>
            </a>
        </div>
        @if(auth()->user()->type == 1)
            <div class="fmnuclm">
                <a href="{{ route('profile') }}">
                    <div class="icnUsr"
                         style="background: url(<?=$base_url?>/images/user_icon.svg) no-repeat center center #66b8d9">
                        PROFILE
                        <span class="icnp" class="Picn">P</span></div>
                </a>
            </div>
        @endif
    </footer>

    <!--
    <div id="mask" class="mask" style="display: none;"></div>
    <div class="popBttom" id="popP1">
        <div class="popSet1">
            <div class="mxWd2">
            <ul class="newBtnLst">
                <li>
                    <a href="#" class="nGrpBtn">
                        <span class="nGrpIcon"></span>
                        <span class="nGrpTxt">New group</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nQstBtn">
                        <span class="nQstIcon"></span>
                        <span class="nQstTxt">New quest</span>
                    </a>
                </li>
            </ul>
            </div>
        </div>
    </div> -->



    <div class="popBttom" id="togroup" style="">
        <!-- STEP 1 -->
        <div class="popSet1">
            <div class="gidlist">

                <div class="gidCrp">
                    <ul id="grp_list">
                        @foreach ($groups as $group)
                            <li class="gDtlOpn grp_list" data="{{ $group->id .'-'. $group->title }}"
                                onclick="showGroupDetails('{{ route('groups.show', ['group' =>$group->id])  }}')">
                                <!-- open class for "togdtl" -->
                                <span> {{ $group->id .'-'. $group->title }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="srcInbx">
                    <input class="infld gidin" type="text" onkeyup="searchGroup(this.value)"
                           placeholder="Search for a group by name or ID"/>
                </div>

            </div>
        </div>
    </div>



    <div class="popBttom" id="togdtl" style="">
        <!-- STEP 2 -->
        <div class="popSet1">
            <div class="gDtlBx">

                <aside class="lin1">
                    <div class="l1Bx1"><img class="owner_img" height="35px" width="35px"
                                            src="{{ url('images/prf.png') }}"/></div>
                    <div class="l1Bx2 owner_name">Fahad alawam</div>
                    <div class="l1Bx3 create_date">25 Apr, 2020</div>
                </aside>

                <aside class="lin2">
                    <div class="l2Bx1"><img class="grp_img" height="70px" width="70px"
                                            src="{{ url('images/math.jpg') }}"/></div>
                    <div class="l2Bx2 group_name">Math 101 - ID</div>
                    <div class="l2Bx3">
                        <b class="total_quest">00</b> Quest<br/>
                        <b class="total_follower">00</b> Followers
                    </div>
                </aside>

                <aside class="lin3">
                    <div class="gpinBx gpsswrdArea">
                        <input class="infld gidin publish_password" type="text" placeholder="Group password"/>
                    </div>
                    <div class="gpacBx">
                        <div class="gpcan pcancel" onclick="$('.mask').click()">Cancel</div>
                        <div class="gpad" onclick="pubslihedToGroup('{{ route('groups.addExam') }}')">ADD</div>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    <div class="rmMask" style="display:none" onclick="closePop()"></div>
    <aside class="rmWhtPop infoPop" style="display:none">
        <div class="rmpTxt grpInfo" style="text-align:center">

        </div>
        <div class="rmpBtnBx">
            <span class="rmpBtn1" onclick="closePop()">Done</span>
        </div>
    </aside>

    <div class="roundBtn" onclick="openGEPref()">PLUS BUTTON</div>


    <div id="mask" class="mask" style="display: none;"></div>
    <div class="popBttom" id="popP1">
        <div class="popSet1">
            <div class="mxWd2">
                <ul class="newBtnLst">
                    <li>
                        <a href="{{ route('groups.create') }}" class="nGrpBtn">
                            <span class="nGrpIcon"></span>
                            <span class="nGrpTxt">New group</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('exams.create') }}" class="nQstBtn">
                            <span class="nQstIcon"></span>
                            <span class="nQstTxt">New quest</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @include('dashboard.layouts.sidebar')

    <div class="mask" style="display:none" onclick="closePop()"></div>

    <!-- Group Notification popup -->
    <div class="txtFldPop" id="titlPop" style="display: none;">
        <div class="tfpIn">
            <div class="tfpBx1">
                <div class="inSet">
                    <h6><span>Title</span></h6>
                    <input type="text" class="infld notification_title">
                    <!-- <span class="notTxt ttl_mxTxt">* Max length 40</span> -->
                </div>

                <div class="inSet">
                    <h6><span>Send message to followers</span></h6>
                    <textarea type="text" class="infld notification"></textarea>
                    <!-- <span class="notTxt ttl_mxTxt">* Max length 40</span> -->
                </div>
                <div class="inSet">
                    <input type="checkbox" id="nwsBx" name="nwsBx"><span><label for="nwsBx">Add to News</label></span>
                </div>
            </div>
            <div class="tfpBx2">
                <div id="" class="canBtn pcancel" onclick="closePop()">Cancel</div>
                <div class="donBtn noti_svbtn" onclick="sendNotificationGroup()">Done</div>
            </div>
        </div>
    </div>


    <!--pop-->
    <div class="rmMask" style="display:none" onclick="closePop()"></div>
    <aside class="rmWhtPop pcommentBox" style="display:none">
        <div class="rmpTxt pcomment" style="text-align:center"></div>
        <div class="rmpBtnBx">
            <span class="rmpBtn1" onclick="closePop()">Done</span>
        </div>
    </aside>

    <div class="loader" style="display:none"></div>


    <div class="rewMsk" style="display:none"></div>
    <div class="rewPop">
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
                    <?php
                    //$get_my = mysqli_query($conn,"SELECT * FROM onc_insistutions WHERE id='".mysqli_real_escape_string($conn,\Auth::user()->id)."'");//$_SESSION['user_login']
                    //$result_my = mysqli_fetch_assoc($get_my);
                    //
                    //$get_hst_exam = mysqli_query($conn,"SELECT * FROM onc_exams WHERE id='".mysqli_real_escape_string($conn,\Auth::user()->id)."'");//$_SESSION['hst_xm_id']
                    //$result_exam = mysqli_fetch_assoc($get_hst_exam);
                    //
                    //$get_owner = mysqli_query($conn,"SELECT * FROM onc_insistutions WHERE id='".mysqli_real_escape_string($conn,$result_exam['teacher'])."'");
                    //$result_xm_owner = mysqli_fetch_assoc($get_owner);
                    //
                    //$get = mysqli_query($conn,"SELECT * FROM onc_reward_history WHERE hist_id='".mysqli_real_escape_string($conn,\Auth::user()->id)."'");//$_SESSION['linces_id']
                    //$result_hstry = mysqli_fetch_assoc($get);

                    // echo "SELECT * FROM onc_reward_history WHERE hist_id='".mysqli_real_escape_string($conn,$_SESSION['linces_id'])."'";
                    // echo $result_hstry['reaward_type'];
                    $result_hstry['re_type'] = null;
                    if ($result_hstry['re_type'] == 0){
                        echo 'Bluetooth Coupon not supported for web';
                    }else if ($result_hstry['re_type'] == 1){
                        echo $result_hstry['reward'];
                    }else if ($result_hstry['re_type'] == 2){
                        echo '<img src="' . $base_url . 'api/uploads/' . $result_hstry['reward'] . '" />';
                    }else if ($result_hstry['re_type'] == 3){
                        echo convertYoutube($result_hstry['reward']);
                    }else if ($result_hstry['re_type'] == 4){
                    if ($result_exam['cert_lang'] == 'en' || $result_exam['cert_lang'] == 'english'){
                        ?>
                    <div class="certificate_en" onclick="print_cert(<?=\Auth::user()->id/*$_SESSION['linces_id']*/?>)">
                        <!--certificate start-->

                        <!--English-->
                        <div class="srtfVew " id="printFrist"
                             onclick="print_cert(<?=\Auth::user()->id/*$_SESSION['linces_id']*/?>)"
                             style="background: #fff; padding: 15px;">
                            <div class="crt1" style="border: 1px solid #4181a7; padding: 15px;">
                                <div class="crt2" style="text-align: center; position: relative;">

                                    <div class="spnrLgo sponsor_img" style="float: left; width: 100%">
                                            <?php if ($result_exam['cert_sponsor_img']  && $result_exam['cert_sponsor_img'] != 'undefined'){ ?>
                                        <img src="<?=$base_url?>api/uploads/<?=$result_exam['cert_sponsor_img']?>">

                                        <?php } ?>
                                    </div>

                                    <div class="crLne1 " style="margin: 0 auto 20px; width: 25%; float: right">
{{--                                        <img src="{{ url('images/logo5.svg') }}" style="max-width: 100%;">--}}
                                    </div>

                                    <div class="crLne2">CERTIFICATE</div>
                                    <div class="crLne3" style="color: #6422A1; font-size: 18px; margin: 0 0 20px 0;">OF
                                        ACHIEVEMENT
                                    </div>
                                    <div class="crLne4" style="color: #707070; font-size: 16px;">
                                        <div class="crLne41" style="padding: 0 0 12px 0;">This is to certify that</div>
                                        <div class="crLne41" style="padding: 0 0 12px 0;">
                                            <div class="crLne42" style="    display: inline-block; font-style: italic;">
                                            <span class="cert_student_name"
                                                  style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?= $result_my['name'] ?></span>
                                            </div>
                                        </div>
                                        <div class="crLne41" style="padding: 0 0 12px 0;">
                                            has passed an electronic exam on Questanya platform, titled with
                                        </div>
                                        <div class="crLne41" style="padding: 0 0 12px 0;">
                                            <div class="crLne42"
                                                 style="display: inline-block; font-style: italic;"><span
                                                    class="cert_exam_name"
                                                    style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?= $result_exam['examname'] ?></span>
                                            </div>
                                        </div>
                                        <div class="crLne41" style="padding: 0 0 12px 0;">
                                            on
                                            <div class="crLne42"
                                                 style="display: inline-block; font-style: italic;"><span
                                                    class="cert_xm_date"
                                                    style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?= date('d-m-Y') ?></span>
                                            </div>
                                            @
                                            <div class="crLne42" style="display: inline-block; font-style: italic;">
                                            <span class="cert_xm_time"
                                                  style="font-weight: bold; padding: 0 2px; border-bottom: 1px solid rgba(112, 112, 112, 0.6);"><?= date('h:i:s A') ?></span>
                                            </div>
                                        </div>
                                        <div class="crLne41" style="padding: 0 0 12px 0;">
                                            Wishing them further success and excellence
                                        </div>
                                    </div>

                                    <div class="crLne60" style="text-align: left;">
                                        <div class="crLne6"
                                             style="font-size: 12px; text-align: center; min-width: 120px; display: inline-block;">
                                            <div class="crLne7"
                                                 style="border-bottom: 1px solid rgba(112, 112, 112, 0.6);">
                                            <span class="cert_xm_maker"
                                                  style="font-weight: bold; padding: 0 2px; font-style: italic; color: #707070"><?= $result_xm_owner['name'] ?></span>
                                            </div>
                                            <div class="crLne8" style="color:#707070">Exam Maker</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!--certificate end-->
                    </div>
                    <?php }else{
                        ?>
                    <div class="certificate_ab" onclick="print_cert(<?=\Auth::user()->id/*$_SESSION['linces_id']*/?>)">
                        <div class="srtfVew">
                            <div class="crt1">
                                <div class="crt2">
                                    <div class="spnrLgo sponsor_img" style="float: left;  width: 100%">
                                            <?php if ($result_exam['cert_sponsor_img']  && $result_exam['cert_sponsor_img'] != 'undefined'){ ?>
                                        <img src="<?=$base_url?>api/uploads/<?=$result_exam['cert_sponsor_img']?>">
                                        <?php } ?>
                                    </div>

                                    <div class="crLne1 " style="margin: 0 auto 20px; width: 25%; float: right">
{{--                                        <img src="{{ url('images/logo5.svg') }}" style="max-width: 100%;">--}}
                                    </div>

                                    <div class="crLne2">شهــــــــــــادة</div>
                                    <div class="crLne3">إنجـــــــاز</div>
                                    <div class="crLne4">
                                        <div class="crLne41">هذه الشهادة تؤكد اجتياز</div>
                                        <div class="crLne41">
                                            <div class="crLne42"><span class="cert_student_name"
                                                                       style="font-weight: bold;"><?= $result_my['name'] ?></span>
                                            </div>
                                        </div>
                                        <div class="crLne41">
                                            للاختبار الإلكتروني على منصة كويستانيا والذي بعنوان
                                        </div>
                                        <div class="crLne41">
                                            <div class="crLne42"><span class="cert_exam_name"
                                                                       style="font-weight: bold;"><?= \Auth::user()->id//$_SESSION['exam_name']     ?></span>
                                            </div>
                                        </div>
                                        <div class="crLne41">
                                            <div class="crLne42"><span class="cert_xm_date"
                                                                       style="font-weight: bold;"><?= date('d-m-Y') ?></span>
                                            </div>
                                            @
                                            <div
                                                class="crLne42"><span class="cert_xm_time"
                                                                      style="font-weight: bold;"><?= date('h:i:s A') ?></span>
                                            </div>
                                            في يوم
                                        </div>
                                        <div class="crLne41">
                                            متمنين لهم دوام النجاح والتميز
                                        </div>
                                    </div>

                                    <div class="crLne60">
                                        <div class="crLne6">
                                            <div class="crLne7"><span class="cert_xm_maker"
                                                                      style="font-weight: bold; color:#707070"><?= $result_xm_owner['name'] ?></span>
                                            </div>
                                            <div class="crLne8" style="color:#707070">صانع الاختبار
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <?php }
                    } ?>


                </div>
            </div>
            <div class="rpBx4" onclick="hideReBox()">
                <img class="re_img_3" src="{{ url('images/rew_bttn1.svg') }}">
                <!-- [1] rew_bttn1.svg || [2] rew_bttn2.svg -->
            </div>
        </div>
    </div>


    <div class="modal" id="qr_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center" style="color: #66b8d9">Share the Qr code</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body text-center" id="qr_modal_modal_body">
                    <h3 class="exam_title" style="margin-bottom: 20px"></h3>
                    <img src="" alt="">
                </div>
                <button class="btn btn-primary profbtn" onclick="convertToImage()" type="button">Download</button>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('scripts')
    <script>
        var quser_id = {{ auth()->id() }};
    </script>
    <script type="text/javascript" src="{{ asset("js/group_funcs.js") }}"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

    <script src="js/inview.js"></script>
    <script>
        function closePop() {
            $('.rmWhtPop').fadeOut();
            $('.infoPop').fadeOut();
            $('.rmMask').fadeOut();
            $('.closePop').fadeOut();
        }

        function convertToImage() {
            // get the div element you want to download as an image
            var container = document.getElementById("qr_modal_modal_body");
            html2canvas(container, {allowTaint: true}).then(function (canvas) {

                var link = document.createElement("a");
                document.body.appendChild(link);
                link.download = "html_image.jpg";
                link.href = canvas.toDataURL();
                link.target = '_blank';
                link.click();
            });

        }

        function showPopGropInfo(id) {
            $.post(base_url + "gretGroupInfo", {
                id: id
            }, function (rs) {
                console.log("group info ", rs);
                $('.rmMask').fadeIn();
                $('.grpInfo').html(rs);
                $('.infoPop').fadeIn();
            })

        }

        function exportExam(xmid) {
            swal.fire({
                title: 'How you want to export exam?',
                confirmButtonColor: '#7cd1f9',
                buttons: [
                    'Export Answers',
                    'Export Exam'

                ],
                dangerMode: true,
            }).then(function (isConfirm) {
                if (isConfirm) {
                    console.log('true');
                    window.open(
                        'http://ec2-3-133-228-180.us-east-2.compute.amazonaws.com/exportExam/index.php?exam_id=' +
                        xmid + '&solution=0');
                } else {
                    window.open(
                        'http://ec2-3-133-228-180.us-east-2.compute.amazonaws.com/exportExam/index.php?exam_id=' +
                        xmid + '&solution=1');
                }
            })
        }

        function makeGroupPrivacy(obj, val, gid, url) {
            axios.post(url)
                .then(function (rs) {
                    if (val == 0) {
                        $(obj).attr("src", "{{ url('images/lock_pink.svg') }}");
                        $(obj).attr("onclick", "makeGroupPrivacy(this,1," + gid + ", '" + url + "')");
                    } else {
                        $(obj).attr("src", "{{ url('images/lock_gray.svg') }}");
                        $(obj).attr("onclick", "makeGroupPrivacy(this,0," + gid + ", '" + url + "')");
                    }
                })
                .catch(function (err) {
                    console.log(err);
                });
        }
    </script>
    <script>
        $(function () {

            var blb = "{!!  'data:image/png;base64,' . base64_encode(QrCode::format('png')->mergeString(isset($user->avatar) ?
                    Storage::disk('public')->get($user->avatar) :
                    asset('users/default.png') )->size(200)->generate(Request::url())); !!}";

            var img = new Image();
            img.onload = function () {

            }
            img.src = blb;
            var div = document.createElement('div');
            div.className = 'visible-print text-center';
            div.appendChild(img);
            $('.qricn').popover({
                placement: "bottom",
                title: 'Profile QR code',
                content: div,
                html: true,
                sanitize: false,
            });
        })

        $('.hloader').on('inview', function (event, isInView) {
            if (isInView) {

                var nextPage = parseInt($('#h_pageno').val()) + 1;

                $.ajax({
                    type: 'POST',
                    url: 'getHistory',
                    data: {
                        pageno: nextPage
                    },
                    success: function (data) {
                        console.log("data received");
                        if (data != '') {
                            $('#history_list').append(data);
                            $('#h_pageno').val(nextPage);
                        } else {
                            $('.hloader').hide();
                        }
                    }
                });
            }
        });

        //for quest

        // $('.qloader').on('inview', function (event, isInView) {
        //     if (isInView) {
        //         var nextPage = parseInt($('#q_pageno').val()) + 1;
        //
        //         $.ajax({
        //             type: 'POST',
        //             url: 'getMyQuest',
        //             data: {
        //                 pageno: nextPage
        //             },
        //             success: function (data) {
        //                 console.log("data received");
        //                 if (data != '') {
        //                     $('#quest_list').append(data);
        //                     $('#q_pageno').val(nextPage);
        //                 } else {
        //                     $('.hloader').hide();
        //                 }
        //             }
        //         });
        //
        //     }
        // })

        //for group

        $('.gloader').on('inview', function (event, isInView) {
            if (isInView) {
                var nextPage = parseInt($('#q_pageno').val()) + 1;

                $.ajax({
                    type: 'POST',
                    url: 'getMyGroup',
                    data: {
                        pageno: nextPage
                    },
                    success: function (data) {
                        console.log("data received");
                        if (data != '') {
                            $('#group_list').append(data);
                            $('#g_pageno').val(nextPage);
                        } else {
                            $('.gloader').hide();
                        }
                    }
                });

            }
        })


        $(window).on("navigate", function (event, data) {
            var direction = data.state.direction;
            if (direction == 'back') {
                if ($('.popBttom').is(":visible") == true) {
                    $('.popBttom').removeClass("open");
                }
            }

        });

        $(document).ready(function () {
            var url = window.location.href;
            console.log("url", url);
            var split = url.split('#');
            if (split.length > 1 && split[1] != '') {

                $('.usrpg').scroll(function () {
                    var position = $('.usrpg').scrollTop();
                    localStorage.setItem("position", position);
                });


                $('.' + split[1] + "_li").click();
                var position = localStorage.getItem("position");
                if (position != null) {
                    $('.usrpg').scrollTop(position);
                }
            }


        })

        function print_cert(licens_id) {
            var win = window.open('https://quiz.bursacenneti.com/certificates/certificate_web.php?license=' + licens_id, '_blank');
        }

        function hideReBox() {
            $(".rewPop").fadeOut();
            $(".rewMsk").fadeOut();
        }

        function showGroupNotiPop(id) {
            $('.txtFldPop').fadeIn();
            $('.rmMask').fadeIn();

            $('.noti_svbtn').attr("onclick", "sendNotificationGroup('{{ route('groups.notify') }}', " + id + ")");
        }

    </script>
@endsection
