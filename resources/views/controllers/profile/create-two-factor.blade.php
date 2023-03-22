@extends('arbory::layout.main')

@section('content')
    <section>

        <header>
            <h1>{{ __('arbory::two-factor.title') }}</h1>
        </header>

        <div class="body">
            <p>{{ __('arbory::two-factor.enable.description') }}</p>

            {!! $qrCode !!}

            <form method="POST"
                  action="{{ route('admin.profile.two-factor.activate') }}">
                @csrf

                <div class="field">
                    <div class="label-wrap">
                        <label for="2fa_code">{{ __('arbory::two-factor.code') }}</label>
                    </div>
                    <div class="value">
                        <input type="text" id="2fa_code" class="text" name="code">
                    </div>
                </div>

                <div class="field">
                    <button type="submit" class="button">{{ __('arbory::two-factor.buttons.enable') }}</button>
                </div>

            </form>
        </div>
    </section>
@stop
