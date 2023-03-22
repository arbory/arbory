@extends('arbory::layout.main')

@section('content')
    <section>

        <header>
            <h1>{{ __('arbory::two-factor.title') }}</h1>
        </header>

        <div class="body">
            <p>
                {{ __('arbory::two-factor.recovery_codes.description') }}
            </p>

            <ul>
                @foreach ($recoveryCodes as $recoveryCode)
                    <li>{{ $recoveryCode['code'] }}</li>
                @endforeach
            </ul>

            <a href="{{ route('admin.users.edit', [$user->getUserId()]) }}" class="button">{{ __('arbory::two-factor.buttons.confirm') }}</a>
        </div>
    </section>
@stop
