@extends('arbory::layout.main')

@section('content')
    <section>

        <header>
            <h1>{{ __('arbory::two-factor.title') }}</h1>
        </header>

        <div class="body">
            <p><strong>{{ __('arbory::two-factor.update_settings') }}</strong></p>

            <p>{{ __('arbory::two-factor.description') }}</p>

            @if ($user->hasTwoFactorEnabled())
                <form method="POST"
                      action="{{ route('admin.profile.two-factor.disable') }}">
                    @csrf

                    <button type="submit" class="button">{{ __('arbory::two-factor.buttons.disable') }}</button>
                </form>
            @else
                <a class="button"
                   href="{{ route('admin.profile.two-factor.enable') }}">{{ __('arbory::two-factor.buttons.enable') }}</a>
            @endif
        </div>
    </section>
@stop
