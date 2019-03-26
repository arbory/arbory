@extends('arbory::dialogs.base',['class' => 'mass-empty confirm'])

@section('dialog')
<header>
    <h1>@lang('Empty selection')</h1>
</header>

<div class="body">
    <i class="fa fa-ban"></i>
    <div class="question">@lang('Please mark at least one line!')</div>
</div>

<footer>
    <div class="tools">
        <div class="primary">
            <a class="button with-icon primary" title="@lang('Ok')" data-type="cancel">
                <i class="fa fa-check"></i>@lang('Ok')
            </a>
        </div>
    </div>
</footer>
@stop