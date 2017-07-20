@extends('leaf::dialogs.dialog_confirmation', [
    'form_target' => $form_target,
    'form_action' => 'post'
])

@section('dialog.header')
    <h1>@lang('Confirm restoration')</h1>
@stop

@section('dialog.body')
    <i class="fa fa-trash-o"></i>
    <div class="question">@lang('Do you want to restore the following object?')</div>
    <div class="description">{{$object_name}}</div>
@stop

@section('dialog.tools.primary')
    <a class="button with-icon secondary" title="No" data-type="cancel" href="{{$list_url}}">
        <i class="fa fa-ban"></i>@lang('No')
    </a>
    <button class="button with-icon danger" title="Yes" type="submit">
        <i class="fa fa-check"></i>@lang('Yes')
    </button>
@stop
