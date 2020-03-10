<header>
    <a class="home" href="{{route('admin.dashboard.index')}}">
        <img alt="Arbory" src="{{ Admin::assets()->getStaticUrl('images/logo.svg') }}"/>
    </a>
    <a class="button profile" href="{{route('admin.users.update', ['user' => $user->id])}}">
        <span class="name">
            @if($user->first_name)
                {{ $user->first_name }} {{ $user->last_name }}
            @else
                {{ $user->email }}
            @endif
        </span>
    </a>
    <form class="sign-out" action="{{route('admin.logout')}}" accept-charset="UTF-8" method="post">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="post"/>
        <button class="button only-icon" type="submit" title="Sign out">
            <i class="fa fa-power-off fa-icon-header"></i>
        </button>
    </form>
</header>
