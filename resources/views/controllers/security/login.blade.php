@extends('arbory::layout.public')

@section('content')
    <form class="login" action="{{route('admin.login.attempt')}}" accept-charset="UTF-8" method="post">
        {!!csrf_field()!!}
        <div class="field @if($errors->has('user.email'))has-error @endif">
            <label for="email">{{ trans('arbory::security.email') }}</label>
            <input autofocus="autofocus" id="email" class="text" type="email"
                   value="{{ $input->old('user.email') }}" name="user[email]">
        </div>
        <div class="field @if($errors->has('user.password'))has-error @endif">
            <label for="password">{{ trans('arbory::security.password') }}</label>
            <input id="password" class="text" type="password" name="user[password]">
        </div>
        <div class="field">
            <label>
                <input type="checkbox" name="remember" value="1" {{ $input->old('remember') ? 'checked' : '' }} />
                {{ trans('arbory::security.remember') }}
            </label>
        </div>
        <div class="field">
            <button class="button" type="submit">{{ trans('arbory::security.submit') }}</button>
        </div>
    </form>
@endsection