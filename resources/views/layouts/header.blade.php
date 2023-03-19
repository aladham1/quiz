@php
  $route = explode("/", \Request::path());
@endphp
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-6">
        <h5 class="m-0 text-dark text-capitalize">@if(\Request::route()->named('games.play')) {{\App\Game::find(str_replace("-", " ", $route[count($route) - 1]))->name}} @else {{ str_replace("-", " ", $route[count($route) - 1]) }} @endif</h5>
      </div>
      <!-- /.col -->
      <div class="col-6">
        <ol class="breadcrumb float-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          @foreach ($route as $breadcrumb)
            <li class="breadcrumb-item @if($loop->last)active @endif">@if(\Request::route()->named('games.play') && $loop->last)  {{ \App\Game::find(str_replace("-", " ", $breadcrumb))->name}} @else {{ $breadcrumb }} @endif</li>
          @endforeach
        </ol>
      </div>
      <!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->