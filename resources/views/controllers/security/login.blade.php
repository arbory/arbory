@extends('arbory::layout.public')

@section('content')
    <form class="login" action="{{route('admin.login.attempt')}}" accept-charset="UTF-8" method="post">
        {!!csrf_field()!!}
        <div class="field @if($errors->has('user.email'))has-error @endif">
            <input autofocus="autofocus" id="email" class="text" type="email"
                   value="{{ $input->old('user.email') }}" name="user[email]" placeholder="{{ trans('arbory::security.email') }}">
        </div>
        <div class="field @if($errors->has('user.password'))has-error @endif">
            <input id="password" class="text" type="password" name="user[password]" placeholder="{{ trans('arbory::security.password') }}">
        </div>
        <div class="field type-checkbox">
            <div class="value">
                <label>
                    <input type="checkbox" name="remember" value="1" {{ $input->old('remember') ? 'checked' : '' }} />
                    <span></span>
                    {{ trans('arbory::security.remember') }}
                </label>
            </div>
        </div>
        <div class="field">
            <button class="button" type="submit">{{ trans('arbory::security.submit') }}</button>
        </div>


        <div class="field info">
            <p><b>{{ trans('arbory::security.lost_password') }}</b></p>
            <p>{{ trans('arbory::security.contact_admin') }}</p>
        </div>
    </form>
@endsection