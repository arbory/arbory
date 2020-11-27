@extends('arbory::dialogs.dialog_confirmation', [
    'form_target' => $form_target,
    'form_action' => 'post'
])

@section('dialog.header')
    <h1>@lang('Confirm disable')</h1>
@stop

@section('dialog.body')
    <i class="mt-icon">delete_outline</i>
    <div class="question">@lang('Do you want to disable the following object?')</div>
    <div class="description">{{$object_name}}</div>
@stop

@section('dialog.tools.primary')
    <a class="button with-icon secondary" title="No" data-type="cancel" href="{{$list_url}}">
        <i class="mt-icon">cancel</i>@lang('No')
    </a>
    <button class="button with-icon danger" title="Yes" type="submit">
        <i class="mt-icon">check</i>@lang('Yes')
    </button>
@stop
