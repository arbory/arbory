@extends('arbory::dialogs.base',['class' => 'mass-empty confirm'])

@section('dialog')
<header>
    <h1>@lang('arbory::dialog.bulk.empty')</h1>
</header>

<div class="body">
    <i class="fa fa-ban"></i>
    <div class="question">@lang('arbory::dialog.bulk.question')</div>
</div>

<footer>
    <div class="tools">
        <div class="primary">
            <a class="button with-icon primary" title="@lang('arbory::dialog.bulk.ok')" data-type="cancel">
                <i class="fa fa-check"></i>@lang('arbory::dialog.bulk.ok')
            </a>
        </div>
    </div>
</footer>
@stop