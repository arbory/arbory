<header>
    <div>
        <a class="home" href="{{ route('admin.dashboard.index') }}">
            <img alt="Arbory" src="{{ asset('/vendor/arbory/images/logo.svg') }}"/>
        </a>
    </div>
    <div class="menu">
        @if (config('arbory.search.enabled', false))
            <form id="global-search-form" class="global-search" action="{{ route('admin.search') }}" data-min-length="{{ config('arbory.search.min_length') }}">
                @csrf

                <div class="search-field">
                    <input type="text" name="term" class="text global-search-input"
                           placeholder="{{ trans('arbory::search.placeholder') }}">
                </div>

                <div class="results-list">
                    <span class="close">x</span>
                    <div class="records"></div>
                </div>
            </form>
        @endif
        <a class="button profile" href="{{ route('admin.users.update', ['user' => $user->id]) }}">
        <span class="name">
            @if($user->first_name)
                {{ $user->first_name }} {{ $user->last_name }}
            @else
                {{ $user->email }}
            @endif
        </span>
        </a>
        <form class="sign-out" action="{{ route('admin.logout') }}" accept-charset="UTF-8" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="post"/>
            <button class="button only-icon" type="submit" title="Sign out">
                <i class="mt-icon">power_settings_new</i>
            </button>
        </form>
    </div>
</header>
