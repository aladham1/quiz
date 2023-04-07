@extends('dashboard.layouts.master')

@section('css')
<style>
    .qsTxt5 img {
         width: 20px;
    }
    .grpTxt6 img{
        width: 20px;
    }
</style>
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
        <video id="preview"></video>
        <div onclick="startScan()" class="qricn">QR</div>
    </div>
</header>


<section class="pageBody">

    <aside class="nhLst">
        <ul>


        </ul>
    </aside>

</section>

@include('dashboard.layouts.sidebar')

<footer class="ftrmnu">
    <div class="fmnuclm">
        <a href='{{ route('discover') }}'>
            <div class="icnSrch">SEARCH</div>
        </a>
    </div>
    <div class="fmnuclm">
        <a href="{{ route('home') }}">
            <div class="icnHom">SEARCH</div>
        </a>
     </div>
     <div class="fmnuclm">
         <a href="{{ route('profile') }}">
            <div class="icnUsr">SEARCH <span class="icnp" style="">P</span></div>
        </a>
     </div>
</footer>

<script>
    const scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function (content) {
        alert('Scanned: ' + content);
    });
    function startScan() {
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (error) {
            console.error(error);
        });
    }
</script>

@endsection
