@php
    $route = explode("/", \Request::path());
@endphp
    <!-- Navbar -->
<nav class="main-header navbar navbar-fixed navbar-expand text-center font-weight-bold navbar-indigo navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <div class="ml-auto">
        <h3 class=" font-weight-bold mb-0 text-white text-capitalize"> @if(\Request::route()->named('games.play'))
                {{\App\Game::find(str_replace("-", " ", $route[count($route) - 1]))->name}}
            @else
                {{ str_replace("-", " ", $route[count($route) - 1]) }}
            @endif </h3>
    </div>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- SEARCH FORM
         <form class="form-inline ml-3 mr-2">
           <div class="input-group input-group-sm">
             <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
             <div class="input-group-append">
               <button class="btn btn-navbar" type="submit" style="box-shadow: none !important;">
                 <i class="fas fa-search"></i>
               </button>
             </div>
           </div>
         </form> -->
        <!-- Messages Dropdown Menu -->

        <!--<li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
              class="fas fa-th-large"></i></a>
        </li>-->

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img src="{{ \Storage::url(\Auth::user()->avatar) }}" style="height:100%" class="img-circle"
                     alt="User Image">
            </a>
            <div class="dropdown-menu dropdown-menu dropdown-menu-left">
                <div href="#" class="dropdown-item text-center">
                    <div class="image">
                        <img src="{{ \Storage::url(\Auth::user()->avatar) }}" width="60" class="img-circle"
                             alt="User Image">
                        <p class="mb-0">{{\Auth::user()->name}}</p>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item text-center">
                    <button class="btn btn-danger text-white"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            style="box-shadow: none !important; font-size: 14px !important;"><i
                            class="fas fa-power-off"></i> Log out
                    </button>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

