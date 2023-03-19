@extends('dashboard.layouts.master')
@section('content')
<header class="clrhdr">
    <div class="leftIcn">
        <div class="backicn" onclick="javascript: window.history.back();">BACK</div>
    </div>

    <div class="pgnme">
        Following
    </div>

    <div class="rgtIcn">
        
    </div>
</header>

<section class="flwoPge">
    
    <aside class="qsLst flwngList">
        <ul>
        @foreach ($groups as $group)
            <li class="rmvGrp_{{ $group->id }}">
                <div class="flowBx1">
                    
                    <div class="qsRow1 q4c">
                        <div class="flowBx5">
                        <a href='{{ route('groups.show', ['group' => $group->id]) }}'> <span style="right:130px" class="clqBx"></span> </a>
                        <img src="@if (isset($group->image)){{ Storage::url($group->image) }}@else{{ url('images/placeholder.jpeg') }}@endif" style="width: 100%">
                        </div>
                        <div class="flowBx2">
                            <div class="qsTxt2 q4a">
                                CODE: <b>{{ 'G'.$group->id }}</b> <span class="q4Pass">Creator: <b>{{ $group->owner->name }}</b></span>
                            </div>
                            <div class="flowBx3">
                                {{ $group->title }}
                            </div>

                        <div class="s7btm">
                        <aside class="flowBx4">
                            <b>{{ $group->exams_count }}</b> Quests <br/>
                            <b>{{ $group->followers_count }}</b> Followers <br/>
                        </aside>
                        
                        <aside class="setRnew q4e">
                            <span class="unflwBtn" onclick="unfollowGroupThis('{{ $group->id }}','{{ Auth::id() }}',this)">UNFOLLOW</span>
                            <!-- query was here to check whether the user following the group
                            if following:
                                <span class="unflwBtn" onclick="unfollowGroupThis({{ $group->id }},{{ Auth::id() }},{{ Auth::id() }},this)">UNFOLLOW</span>
                            //else
                                <span class="unflwBtn" style="background:#F232A4; color: #fff; border: 1px solid #F232A4">FOLLOW</span>
                            end -->
                            <!--<span class="btnIc q4Dot"><img src="{{ url('images/dot_pink.svg') }}">
                                <div class="dtMlst">
                                    <ul class="dmlist">
                                         <li>
                                            <span class="dtmi dt_info"></span>
                                            <span>Info</span>
                                        </li>
                                        <li>
                                            <span class="dtmi dt_cpy"></span>
                                            <span>Copy Info</span>
                                        </li>
                                        <li>
                                            <span class="dtmi dt_dlt"></span>
                                            <span>Delete</span>
                                        </li> 
                                    </ul>
                                </div>
                            </span>-->
                        </aside>
                        </div>
                    
                    </div>
                    </div>
                    
                </div>
            </li>
        @endforeach
        </ul>
    </aside>

    <aside class="qsLst qsLst1" style="display:none">
        <ul class="quest_list">

        </ul>
    </aside> 
</section>


<div class="rBtnsrch" id="popB1">SEARCH</div>

<div class="msksbr" onclick="revertHtml()"></div>
<aside class="srcBr">
    <div class="srcBr2">
        <input class="sbIn" type="text" placeholder="Enter Quest or Group ID" onkeyup="searchGroupDiscover(this.value)"/>
        <input type="button" class="sbBtn" value="SEARCH" onclick="searchGroupDiscover($('.srcBr .srcBr2 .sbIn').first().val())">
    </div>  
</aside>
 
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
</div>
@endsection

@section('scripts')
    <script>
        $('.dsktop_view').addClass('overflow-hidden');
        var follow_cmd_link = '{{ route('groups.follow') }}';
        var unfollow_cmd_link = '{{ route('groups.unfollow') }}';
        var groups_search_link = '{{ route('groups.index') }}';
        var group_details_link = '{{ route('groups.show', ['group' => '#group#']) }}';
    </script>
    <script type="text/javascript" src="{{ asset("js/group_funcs.js") }}"></script>
@endsection