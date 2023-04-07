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
        <div id="result"></div>
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
<script src="https://rawcdn.githack.com/schmich/instascan-builds/master/instascan.min.js"></script>

<script>

    const preview = document.getElementById('preview');
    const result = document.getElementById('result');
    let scanner = null;

    function startScan() {
        scanner = new Instascan.Scanner({ video: preview, mirror: false });
        scanner.addListener('scan', function(content) {
            console.log('Scanned:', content);
            result.innerText = 'Scanned: ' + content;
            scanner.stop();
            if (isLink(content)) {
                window.open(content, '_blank');
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
