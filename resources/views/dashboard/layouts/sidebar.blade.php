
<div class="mnuCls mnuMsk"></div>
<section class="sidMnu" style="">
    <div class="sidMnu2">
        <div class="mnuCls mclsIcn"></div>

        <div class="pfbx4sm"><img src="{{ url(Storage::url(Auth::user()->avatar)) ?? url('images/user.svg') }}"></div>
        <div class="pfbx5sm">{{ Auth::user()->name }}</div><br>
        <div class="pfbx5sm" style="font-weight:bold">{{ Auth::user()->id + 1000 }}</div>
        <div class="pfbx5sm" style="font-weight:bold; display: block; ">
            <a href="{{route('profile')}}" style="color: #1f5373">History And Profile</a>
        </div>
        {{ menu('user') }}
        <div class="sdmLgout">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="lgoBtn">Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</section>
