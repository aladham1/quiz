@php //joining user subscribed groups, their exams and the exams owner in one query

$user_subs = \App\User::find(auth()->id())->load('following');
//dd($user_subs->following->pluck('id')->toArray());
$latest_exams = DB::table('exams')
        ->join('groupables', function($join) use($user_subs) {
            $join->on('exams.id', '=', 'groupables.groupable_id')
            ->where('groupables.groupable_type', '=', 'App\Models\Exam')
            ->whereIn('groupables.group_id', $user_subs->following->pluck('id')->toArray());
        })
        ->join('users', 'exams.user_id', '=', 'users.id')
        ->select('exams.*', 'groupables.groupable_type', 'groupables.groupable_id', 'groupables.group_id', 'users.name as owner_name')->latest()->get();
        //dd($latest_exams);
//dd($dd);
/*$dd = DB::table('groupables')
        ->where('groupables.groupable_id', '=', auth()->id())->where('groupables.groupable_type', '=', '\App\User')
        ->join('groups', '')
//->join('exams', function($join) {
  //$join->on('groupables.groupable_id', '=', 'exams.id')->where('groupables.groupable_type', '=', 'App\Models\Exam');
  //->join('users', 'exams.user_id', '=', 'users.id');
//});*/
//dd($subscriptions_latest_exams);
@endphp
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

    <aside class="nhLst">
        <ul>
            @dd($latest_exams)
            @forelse ($latest_exams as $exam)
                <li>
                    <div class="nhlBx">

                        <div class="nhRw1">
                            <a href="{{ route('groups.show', ['group' => $exam->group_id]) }}" style="color: #000"> <span class="clqBx"></span></a>
                            <div class="nhrImg">
                                @php
                                    $group_img = $user_subs->following->where('id', $exam->group_id)->first()->image;
                                    $group_img = isset($group_img) ? Storage::url($group_img) : 'images/placeholder.jpeg';
                                @endphp
                                <img height="100%" width="100%" src="{{ $group_img }}" />
                            </div>
                            <div class="nhrT1">
                            </div>
                            <div class="nhrT2">
                                <h4>{{ \Carbon\Carbon::parse($exam->created_at)->format('d F, Y') }}</h4>

                                <h5>By: {{ $exam->owner_name }}</h5>
                            </div>
                            <div class="magic"></div>
                        </div>
                        <div class="qsRow1 q4c">
                            <a href="{{ route('exams.intro', ['exam' => $exam->id]) }}"> <span class="clqBx"></span> </a>
                            <div class="nh1img">
                                <img src="{{ isset($exam->icon) ? Storage::url($exam->icon) : 'images/placeholder.jpeg' }}"/>
                            </div>
                            <div class="qs1Rbx">

                                <div class="nhT2">
                                    <a href="{{ route('exams.intro', ['exam' => $exam->id]) }}" style="color: #000">
                                        {{ $exam->title }}
                                    </a>
                                </div>
                                <div class="exam-pass">
                                    @if($exam->have_preq_exam)
                                        <img src="{{asset('images/lock.png')}}" width="20" alt="">
                                        <span
                                            style="font-size: 12px">Exam number: {{$exam->have_preq_exam}}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="clBtn">
                                <aside class="nhT1">
                                    CODE: <b>{{ $exam->id + 1000 }}</b>
                                </aside>

                                <aside class="setRnew q4d">
                                    <span class="btnIc" onclick="shareData('{{ $exam->title }}','{{ $exam->title }} - {{ $exam->owner_name }}','{{ route('exams.intro', ['exam' => $exam->id]) }}')"><img src="images/share_v.svg"></span>
                                    <!--<span class="btnIc q4Dot"><img src="images/dot_vio.svg">
                                        <div class="dtMlst">
                                            <ul class="dmlist">
                                                <li>
                                                    <span class="dtmi dt_play"></span>
                                                    <span>Play</span>
                                                </li>
                                                <li>
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
                                                </li>
                                            </ul>
                                        </div>
                                    </span>-->
                                </aside>
                                <div class="magic"></div>
                            </div>

                        </div>
                    </div>
                </li>
            @empty

            @endforelse

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
<a href="{{route('discover')}}" class="rBtnsrch" style="   bottom: 55px;" >SEARCH</a>

<script src="https://rawcdn.githack.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


@endsection
@section('scripts')

    <script src="https://rawcdn.githack.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
