@extends('arbory::layout.public')

@section('content')
    <form class="login" accept-charset="UTF-8" method="post">
        @csrf

        <div class="field @if($errors->has('2fa_code'))has-error @endif">
            <input id="2fa_code"
                   class="text"
                   type="text"
                   name="2fa_code"
                   placeholder="{{ trans('arbory::two-factor.code') }}">
        </div>

        <div class="field">
            <button class="button" type="submit">{{ trans('arbory::security.submit') }}</button>
        </div>
    </form>
@endsection
