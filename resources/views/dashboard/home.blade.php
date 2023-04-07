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
