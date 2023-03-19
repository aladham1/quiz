@php
    $user_avatar = isset(Auth::user()->avatar) ? url(Storage::url(Auth::user()->avatar)) : url('images/user.svg');
@endphp
@extends('dashboard.layouts.master')
@section('content')
    <header class="clrhdr">
        <div class="leftIcn">
            <div class="backicn" onclick="javascript: window.history.back();">BACK</div>
        </div>

        <div class="pgnme">
         {{ $group->title }}
        </div>

        <div class="rgtIcn">
        </div>
    </header>

    <section class="pgTop">
        <div class="pfVew">
            <div class="pfbx1" style="background:url('@if ($group->image != null || $group->image != ''){{ url(Storage::url($group->image)) }}@else{{ url('images/placeholder.jpeg') }}@endif') no-repeat center center;background-size: cover;"></div>
            <div class="pfbx2">
                <div class="pfbx3">
                    <div class="pfbx4"><img src="{{ $user_avatar }}"></div>
                    <div class="pfbx5">{{ $owner->name }}</div>
                </div>
                <div class="pfbx6">{{ $group->title }}</div>
                <div class="pfbx82">
                    {{ substr($group->description,0,50) }}...<span class="rdmre" onclick="showPopGropInfo('{{ route('groups.showDesc', ['group' => $group->id]) }}')">Read More</a>
                </div>
                <div class="pfbx7">
                    <div class="pfbx92">
                        <span class="p9Shrp" onclick="shareData('{{ $group->title }}','{{ $group->title }} - {{ $owner->name }}','{{ route('groups.show', ['group' => $group->id]) }}')">Share</span>
                        <!-- query was here to check whether the user following the group
                            if following:
                                <span class="pfbx10 " onclick="unfollowGroup(this,{{ $group->id }})">UNFOLLOW</span>
                            else
                                <span class="pfbx10 flw" onclick="followGroup(this,{{ $group->id }})">FOLLOW</span>

                        -->
                    </div>
                </div>
            </div>

        </div>

        <div class="ptBox2">
    <ul class="ulicn2" style="display:inline-flex">
        <li class="actv" onclick="showTab(this,'quest')"><span class="icnm icni"></span><span class="icnTxt">Quests</span><span class="nmbr">{{ $group->exams_count }}</span></li>
        <li onclick="showTab(this,'follower')"><span class="icnflw icni"></span><span class="icnTxt">Followers</span> <span class="nmbr">{{ $group->followers_count }}</span></li>
        <li onclick="showTab(this,'news')"><span class="icnflw icni" style="background:url('<?=$base_url?>images/msg_icon.svg') no-repeat center center"></span><span class="icnTxt">News</span> <span class="nmbr">{{ $group->news_count }}</span></li>
    </ul>
        </div>
    </section>



    <section class="usrpg" style="height: calc(100% - 50px - 270px);">
    <input type="hidden" id="h_pageno" value="1">
    <section class="flwLsts" style="display:none; background: white">
        <ul class="follwer_list">
           @foreach ($followers as $follower)
                <li>
                    <!-- <a href="<?=$base_url?>user-profile/{{ $follower->id }}"> -->
                    <div class="pfbx3">
                        <div class="pfbx4"><img src="{{ isset($follower->avatar) ? url(Storage::url($follower->avatar)) : url('images/user.svg') }}"></div>
                        <div class="pfbx5" style="color:#000">{{ $follower->name }}</div>
                    </div>
                    <!-- </a> -->
                </li>
           @endforeach
        </ul>
        {{ $followers->links() }}
        <!-- <div class="hloader"></div> -->
    </section>
    
    <aside class="nwsLst nhLst" style="display:none">
            <ul>
                @foreach ($news as $item)
                    <li>
                        <div class="nhlBx" style="padding: 6px;">
                            <div class="nhRw1">
                            <a href="{{ $item->link }}">
                                    <div class="nhrImg2"><img src="{{ $user_avatar }}"/></div>
                                    <div class="nhrT1">{{ $item->title }}</div>
                                    <div class="nhrT2">
                                        <h4>{{ $item->created_at }} </h4>
                                    </div>
                                </a>
                                <div class="magic"></div>
                            </div>

                            <div class="qsRow1 q4c">
                            <a href="{{ $item->link }}"> <span class="clqBx"></span> </a>  
                                <div class="qs1Rbx">
                                    <div class="nhT2">
                                       {{ $item->body }}
                                    </div>
                                </div>
                                <div class="clBtn">
                                <aside class="setRnew q4d">
                                    <span class="btnIc" onclick="shareData('{{ $item->title }}','{{ $item->title }} - {{ $item->body }}','{{ $item->link }}')"><img src="<?=$base_url?>images/share_v.svg"></span>
                                </aside>
                                <div class="magic"></div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            {{ $news->links() }}
        </aside>

        <aside class="nhLst">
            <ul>
                @foreach ($exams as $exam)
                    <li>
                        <div class="nhlBx">
                        
                            <div class="nhRw1">
                                <!-- TODO: change route to teachers.show after addign security -->
                                <a href="{{ route('profile', ['id' => $exam->owner->id]) }}" style="color:#000">
                                    <div class="nhrImg2"><img src="{{ isset($exam->owner->avatar) ? url(Storage::url($exam->owner->avatar)) : url('images/user.svg') }}"/></div>
                                    <div class="nhrT1">By: {{ $exam->owner->name }}</div>
                                    <div class="nhrT2">
                                        <h4>{{ $exam->created_at }} </h4>
                                    </div>
                                </a>
                                <div class="magic"></div>
                            </div>
                            <div class="qsRow1 q4c">
                            <a href="{{ route('exams.show', ['exam' => $exam->id]) }}"> <span class="clqBx"></span> </a>  
                                <div class="nh1img">
                                    <img src="{{ isset($exam->icon) ? Storage::url($exam->icon, true) : url('images/placeholder.jpeg') }}">
                                </div>
                                <div class="qs1Rbx">
                                    <div class="nhT2">
                                        {{ $exam->title }}
                                    </div>
                                </div>
                                <div class="clBtn">
                                <aside class="nhT1">
                                    CODE: <b>{{ $exam->id + 1000 }}</b>
                                </aside>
                            
                                <aside class="setRnew q4d">
                                    <span class="btnIc" onclick="shareData('{{ $exam->title }}','{{ $exam->title }} - {{ $exam->owner->id }}','{{ route('exams.show', ['exam' => $exam->id]) }}')"><img src="{{ url('images/share_v.svg') }}"></span>
                                    <span class="btnIc q4Dot"><img src="{{ url('images/dot_vio.svg') }}">
                                        <div class="dtMlst">
                                            <ul class="dmlist">
                                                @if($exam->owner->id == Auth::user()->id)
                                                     <li>
                                                        <a href="{{ route('exams.edit', ['exam' => $exam->id]) }}" style="color:#511285">
                                                            <span class="dtmi dt_play" style="background: url({{ url('images/edtGrp.svg') }}) no-repeat center center; background-size: contain;"></span> 
                                                            <span>Edit</span>
                                                        </a>
                                                    </li>
                                                @endif
                                               <!-- <li>
                                                    <span class="dtmi dt_info"></span>
                                                    <span>Info</span>
                                                </li>
                                                <li>
                                                    <span class="dtmi dt_chat"></span>
                                                    <span>Chat on</span>
                                                </li>
                                                <li>
                                                    <span class="dtmi dt_expo"></span>
                                                    <span>Export</span>
                                                </li>
                                                <li>
                                                    <span class="dtmi dt_grup"></span>
                                                    <span>To group</span>
                                                </li>
                                                <li>
                                                    <span class="dtmi dt_dlt"></span>
                                                    <span>Delete</span>
                                                </li> -->
                                            </ul>
                                        </div>
                                    </span>
                                </aside>
                                <div class="magic"></div>
                                </div>
                            
                            </div>
                        
                        </div>
                    </li>
                @endforeach
            </ul>
            {{ $exams->links() }}
        </aside>
    </section>
    <div class="rmMask" style="display:none" onclick="closePop()"></div>
    <aside class="rmWhtPop" style="display:none">
        <div class="rmpTxt grpInfo" style="text-align:center">
            {{ $group->description }}
        </div>
        <div class="rmpBtnBx">
            <span class="rmpBtn1" onclick="closePop()">Done</span>
        </div>
    </aside>
@endsection


@section('scripts')
    <script type="text/javascript" src="{{ asset("js/group_funcs.js") }}"></script>
    <script src="{{ asset("js/inview.js") }}"></script>
@endsection