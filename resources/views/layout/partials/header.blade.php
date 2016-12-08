<header>
    <a class="home" href="{{route('admin.dashboard')}}">
        <img alt="Leaf" src="/leaf/images/logo.png"/>
    </a>
    <a class="button profile" href="#">
        <span class="name">{{ $user->first_name }} {{ $user->last_name }}</span>
    </a>
    <form class="sign-out" action="{{route('admin.logout')}}" accept-charset="UTF-8" method="post">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="post"/>
        <button class="button only-icon" type="submit" title="Sign out">
            <i class="fa fa-power-off fa-icon-header"></i>
        </button>
    </form>
</header>
