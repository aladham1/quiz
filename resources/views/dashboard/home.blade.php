@extends('dashboard.layouts.master')

@section('css')
<style>
    .qsTxt5 img {
         width: 20px;
    }
    .grpTxt6 img{
        width: 20px;
    }
    #preview {

    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">

@endsection

@section('content')
<header class="clrhdr">
    <div class="leftIcn">
        <div class="mnuicn">MENU</div>
    </div>

    <div class="pgnme">
        Home page
    </div>

    <div class="rgtIcn">
        <div onclick="startScan()" class="qricn">QR</div>
        <div id="result"></div>
    </div>
</header>


<section class="pageBody">
    <video id="preview"></video>

    <aside class="qsLst rcmPLst ml-3">
        @php
            $groupCategories = App\Models\GroupCategory::all();
            $groupCategories->load(['groups' => function($q) {
            $q->where('private', 0)->withCount(['followers', 'exams',])->with(['owner:id,name']);
            }]);
            //$groupCategories = App\Models\GroupCategory::where('private', 0)->withCount(['followers','exams',])->with( ['owner:id,name'])->get();
            $user_subs = App\User::find(Auth()->id())->load('following')->following->pluck('id')->toArray();
        @endphp
        @foreach($groupCategories as $category)
            <h4 class="my-2">{{ $category->category }}</h4>
            <ul>
                @foreach($category->groups as $group)
                    <li class="rmvGrp_{{ $group->id }}">
                        <div class="rcmPbx1">

                            <div class="qsRow1 q4c">
                                <div class="rcmPbx2">
                                    <a
                                        href='{{ route('groups.show', ['group' => $group->id]) }}'>
                                        <span style="right:130px" class="clqBx"></span> </a>
                                    <img src="@if (isset($group->image)){{ Storage::url($group->image) }}@else{{ url('images/placeholder.jpeg') }}@endif"
                                         style="width: 100%">
                                </div>
                                <div class="rcmPbx5">
                                    <div class="rcmPbx3">
                                        {{ $group->owner->name }}<span class="rcmPbx4"> CODE:
                                            <b>{{ $group->code }}</b></span>
                                    </div>
                                    <div class="rcmPbx8">
                                        {{ $group->title }}
                                    </div>

                                    <div class="s7btm">
                                        <aside class="rcmPbx6">
                                            <b>{{ $group->exams_count }}</b> Quests <br />
                                            <b>{{ $group->followers_count }}</b> Followers <br />
                                        </aside>

                                        <aside class="setRnew q4e">
                                            @if(in_array($group->id, $user_subs))
                                                <span class="rcmPbx7"
                                                      onclick="unfollowGroupThis('{{ $group->id }}','{{ Auth::id() }}',this)">UNFOLLOW</span>
                                            @else
                                                <span class="rcmPbx7"
                                                      style="background:#F232A4; color: #fff; border: 1px solid #F232A4"
                                                      onclick="followGroupThis('{{ $group->id }}','{{ Auth::id() }}',this)">FOLLOW</span>
                                            @endif
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
        @endforeach

    </aside>

    <aside class="qsLst qsLst1" style="display:none">
        <ul class="quest_list">

        </ul>
    </aside>

</section>

@include('dashboard.layouts.sidebar')

<footer class="ftrmnu">
{{--    <div class="fmnuclm">--}}
{{--        <a href='{{ route('discover') }}'>--}}
{{--            <div class="icnSrch">SEARCH</div>--}}
{{--        </a>--}}
{{--    </div>--}}
    <div class="fmnuclm">
        <a href="{{ route('home') }}">
            <div class="icnHom">Home</div>
        </a>
     </div>
     <div class="fmnuclm">
         <a href="{{ route('profile') }}">
            <div class="icnUsr">SEARCH <span class="icnp" style="">P</span></div>
        </a>
     </div>
</footer>
<div class="rBtnsrch" style="bottom: 55px;" id="popB1">SEARCH</div>

<div class="msksbr" onclick="revertHtml()"></div>
<aside class="srcBr">
    <div class="srcBr2">
        <input class="sbIn" type="text" placeholder="Enter Quest or Group ID"
               onkeyup="searchGroupDiscover(this.value)" />
        <input type="button" class="sbBtn" value="SEARCH"
               onclick="searchGroupDiscover($('.srcBr .srcBr2 .sbIn').first().val())">
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

<script src="https://rawcdn.githack.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $('.dsktop_view').addClass('overflow-hidden');
    var follow_cmd_link = '{{ route('groups.follow') }}';
    var unfollow_cmd_link = '{{ route('groups.unfollow') }}';
    var groups_search_link = '{{ route('groups.index') }}';
    var group_details_link =
        '{{ route('groups.show', ['group' => '#group#']) }}';

</script>
<script type="text/javascript" src="{{ asset("js/group_funcs.js") }}"></script>
<script>
    const preview = document.getElementById('preview');
    const result = document.getElementById('result');
    let scanner = null;

    function startScan() {
        scanner = new Instascan.Scanner({ video: preview, mirror: false });
        scanner.addListener('scan', function(content) {
            console.log('Scanned:', content);
            scanner.stop();
            if (isLink(content)) {
                Swal.fire({
                    title: "Scanned Link",
                    text: content,
                    icon: "success",
                    showCancelButton: true,
                    confirmButtonText: "Open Link",
                    cancelButtonText: "Cancel",
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.open(content, '_blank');
                    }
                });
            } else {
                Swal.fire({
                    title: "Scanned Result",
                    text: content,
                    icon: "success",
                });
            }
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                const backCamera = cameras.find(function(camera) { return camera.name.indexOf('back') !== -1 });
                if (backCamera) {
                    scanner.start(backCamera);
                } else {
                    scanner.start(cameras[0]);
                }
            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
    }

    function isLink(content) {
        return /^https?:\/\//i.test(content);
    }
</script>
@endsection
