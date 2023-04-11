@php
    //include_once 'includes/system_connection.php';
    //include_once 'includes/functions.php';
    //$scon = mysqli_connect("localhost","root","XpeoTi8GXV","quest_store") or die("store not connected");
    $guest_prefix = ''; //Auth::check() ? '' : 'guest.';
@endphp

@extends('dashboard.layouts.master')
@section('css')
    <style>
        .prgVbx img{
            max-width: 100%;
        }
        body{
            user-select:auto;
        }
    </style>
@endsection

@section('content')
    <header class="clrhdr">
        <div class="leftIcn">
            <div onclick="window.history.length > 1 ? window.history.back() : window.close()" class="backicn">BACK</div>
        </div>

        <div class="pgnme">
            {{ $exam->title ?? '' }}
        </div>

        <div class="rgtIcn">

        </div>
    </header>

    <section class="prvewPg">

        <ul class="prvLst" id="intro_items">
            @if ($exam)
                @foreach ($intro_items as $key => $item)
                    @php $item = $item['data'] ?? $item; @endphp
                    @if ( Str::endswith($key, 'title')    || (isset($item['type']) && $item['type'] == "title") )
                        <li>
                            <h6>{{ $item }}</h6>
                        </li>
                    @elseif( Str::endswith($key, 'audio') || (isset($item['type']) && $item['type'] == "audio") )
                        <li>
                            <div class="audVbx">
                                <audio controls>
                                    <source src="{{ Storage::url($item) }}" type="audio/wav">
                                </audio>
                            </div>
                        </li>
                    @elseif( Str::endswith($key, 'image') || (isset($item['type']) && $item['type'] == "image") )
                        <li>
                            <div class="imgVbx">
                                <img src="{{ Storage::url($item) }}" />
                            </div>
                        </li>
                    @elseif( Str::endswith($key, 'paragraph') || (isset($item['type']) && $item['type'] == "paragraph") )
                        <li>
                            <div class="prgVbx" style="text-align:center">
                                {!! $item !!}
                            </div>
                        </li>
                    @elseif( Str::endswith($key, 'video') || (isset($item['type']) && $item['type'] == "video") )
                        <li>
                            <div class="vdoVbx">
                                <iframe src="https://www.youtube.com/embed/{{ $item['video_id'] ?? $item }}?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='{{ $item['width'] ?? '100%' }}' height='{{ $item['height'] ?? '' }}' allowfullscreen></iframe>
                            </div>
                        </li>
                    @elseif( Str::endswith($key, 'table') || (isset($item['type']) && $item['type'] == "table") )
                        <li>
                            <div class="tblVbx">
                                {!! $item !!}
                            </div>
                        </li>
                    @elseif( Str::endswith($key, 'file')  || (isset($item['type']) && $item['type'] == "file") )
                        <li>
                            <div class="dwnS1">
                                <div class="dwnS2 text-center">
                                    @php
                                        //$item = preg_replace ('/.+\//i', '', $item);
                                    @endphp
                                    <a href="{{ route('storage.download', ['file' => $item]) }}" class="text-center" target="_blank"><span class="dwnS3 text-center">DOWNLOAD</span></a> <span class="dwnS4">{{ preg_replace ('/.+\//i', '', $item) }}</span>
                                </div>
                            </div>
                        </li>
                    @elseif( Str::endswith($key, 'order') || (isset($item['type']) && $item['type'] == "order") )
                        @php
                            $explode_url = explode('/',$item);
                            $get_product = mysqli_query($scon,"SELECT * FROM wp_posts WHERE post_name='".mysqli_real_escape_string($scon,$explode_url[4])."'");
                            $result_product = mysqli_fetch_assoc($get_product);

                            $get_meta = mysqli_query($scon,"SELECT * FROM wp_postmeta WHERE post_id='".mysqli_real_escape_string($scon,$result_product['ID'])."' AND meta_key='_product_image_gallery'");
                            $result_meta = mysqli_fetch_assoc($get_meta);

                            $explode_img = explode(',',$result_meta['meta_value']);
                            $get_img = mysqli_query($scon,"SELECT * FROM wp_postmeta WHERE post_id='".mysqli_real_escape_string($scon,$explode_img[0])."' AND meta_key='_wp_attached_file'");
                            $result_img = mysqli_fetch_assoc($get_img);
                        @endphp
                        <li>
                            <div class="storBxs">
                                <div class="storBx2">
                                    <div class="sBimg"><img height="90px" width="90px" src="http://store.questanya.com/wp-content/uploads/<?=$result_img['meta_value']?>"/></div>
                                    <div class="sBlgo"><img src="<?=$base_url?>images/storLgo.svg"/></div>
                                    <div class="sBLne"><?=$result_product['post_title']?></div>
                                    <div class="sBtnBx">
                                        <!-- <a href="<?=$result_product['guid']?>" target="_blank">
                                            <span class="ordBtn">ORDER NOW</span>
                                        </a> -->

                                        <a href="<?=$result_product['guid']?>" target="_blank" class="ordBtn">
                                            ORDER NOW
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>

        <!--<div class="tryTbx">
            Now go try the test
        </div>-->

        @if ($exam)
            <div class="tblPbtnBr">
                @if ($questions_sum > 0)
                    <a href="{{ route($guest_prefix . 'exams.attend', ['exam' => $exam->id]) }}"><div class="donBtn2 table_svbtn" style="width: 100%">Start Exam</div></a>
                @elseif ($exam->project_count > 0)
                    <a href="{{ route($guest_prefix . 'exams.project_submits.create', ['exam' => $exam->id]) }}"><div class="donBtn2 table_svbtn" style="width: 100%">Submit Project</div></a>
                @endif
            </div>
        @endif

    </section>
@endsection
@section('pkgs')
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            var width = $(window).width();
            var height = (width * 0.2812) + "px";
            console.log("width="+width);
            console.log("height="+height);
            $('iframe').css("height",height);
        });
    </script>
    @if (!$exam)
        <script>
            var questions;
            var offlineDBname = localStorage.getItem('offline_DB');
            $(document).ready(function(){
                questions = localforage.createInstance({
                    // name: offlineDBname
                });
                questions.getItem('Exam')
                .then(function(exam) {
                    console.log(exam);
                    $('.pgnme').text(exam['title']);
                })
                var intro_items;
                questions.getItem('Intro_sort')
                .then(function(array) {
                    intro_items = array;
                    intro_items.pop();
                    return questions.keys();
                })
                .then(function(obj) {
                    var keys = obj;

                    console.log(obj);
                    console.log(intro_items);
                    var promises = [];
                    for (var x = 0; x < intro_items.length; x++) {
                        var type = intro_items[x].replace('Intro_', '');
                        var chkr = intro_items[x]+'_data';
                        if ( $.inArray( chkr, keys ) != -1) {
                            console.log(true)
                            promises.push( preview_intro(chkr, type) );
                        } else {
                            promises.push(preview_intro(intro_items[x], type));
                        }

                    }
                    return Promise.all(promises);
                })
            });

            function preview_intro(key, type) {
                console.log(type);
                var p = questions.getItem(key)
                .then(function(item) {
                    var html;
                    var div = document.createElement('div');
                    if ( type.indexOf("title") != -1 ){
                        html = '<li><h6>'+ item['data'] +'</h6></li>';
                    }else if( type.indexOf("audio") != -1 ){
                        html = document.createElement('li');
                        var audio = document.createElement('audio');
                        var src = document.createElement('source');
                        div.className = 'audVbx';
                        audio.controls = true;
                        src.type = 'audio/wav';

                        src.onload = function () {
                            URL.revokeObjectURL(this.src);
                        }
                        src.src = URL.createObjectURL(item);

                        audio.appendChild(src); div.appendChild(audio); html.appendChild(div);
                    }else if( type.indexOf("image") != -1 ){
                        console.log(item['data']);
                        html = document.createElement('li');
                        var img = document.createElement('img');
                        div.className = 'imgVbx';
                        img.onload = function () {
                            URL.revokeObjectURL(this.src);
                        }
                        img.src = URL.createObjectURL(item);
                        div.appendChild(img);
                        html.appendChild(div);
                    }else if( type.indexOf("video") != -1 ){
                        var vid = item['data']['video_id'] || item['data'];
                        var width = item['data']['width'] || '100%';
                        var height = item['data']['height'] || '';
                        html = '<li>'+
                            '<div class="vdoVbx">'+
                                '<iframe src="https://www.youtube.com/embed/'+vid+'?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow=\'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\' width=\''+width+'\' widtheighth=\''+height+'\' allowfullscreen></iframe>'+
                            '</div>'+
                        '</li>';
                    }else if( type.indexOf("file") != -1 ){
                        console.log(item['data']);
                        html = '<li><div class="dwnS1"><div class="dwnS2 text-center">'+
                                    '<a href="'+ URL.createObjectURL(item) +'" download class="text-center"><span class="dwnS3">DOWNLOAD</span></a> <span class="dwnS4">'+ item +'</span>'+
                                '</div></div></li>';
                    }else if( type.indexOf("paragraph") != -1 ){
                        html = '<li><div class="prgVbx" style="text-align:center">'+
                                item['data']
                            +'</div> </li>';
                    }else if( type.indexOf("table") != -1 ){
                        html = '<li><div class="tblVbx">'+ item['data'] +'</div> </li>';
                    }else if( type.indexOf("order") != -1 ){
                        html = `<li>
                            <div class="storBxs">
                                <div class="storBx2">
                                    <div class="sBimg"><img height="90px" width="90px" src="http://store.questanya.com/wp-content/uploads/ NOTE: PHP HERE"/></div>
                                    <div class="sBlgo"><img src="<?=$base_url?>images/storLgo.svg"/></div>
                                    <div class="sBLne">NOTE: PHP HERE</div>
                                    <div class="sBtnBx">
                                        <!-- <a href="NOTE: PHP HERE" target="_blank">
                                            <span class="ordBtn">ORDER NOW</span>
                                        </a> -->

                                        <a href="NOTE: PHP HERE" target="_blank"  class="ordBtn">
                                                ORDER NOW
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>`;
                    }
                    return $('#intro_items').append(html);
                })
                return p;
            }
        </script>
    @endif
@endsection
