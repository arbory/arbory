@extends('arbory::dialogs.base',['class' => 'content-type constructor-dialog'])

@section('dialog')
    <header>
        <h1>@lang('arbory::constructor.dialog.title')</h1>
    </header>
    <div class="body">
        <div class="description">@lang('arbory::dialog.nodes_content_type.select_content_type')</div>
        @if($types)
        <div class="content-types">
            <ul>
                @foreach($types as $type => $block)
                <li>
                    <a class="js-select-block" href="javascript:;" data-name="{{ $block->name() }}" data-field="{{ $field }}">{{ $block->title() }} ({{ $block->name() }})</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <footer>
        <div class="tools">
            <div class="primary">
                <a
                        class="button with-icon secondary"
                        title="@lang('arbory::dialog.nodes_content_type.cancel')"
                        data-type="cancel"
                        href="/admin/nodes"
                ><i class="fa fa-ban"></i>@lang('arbory::dialog.nodes_content_type.cancel')</a>
            </div>
            <div class="secondary">
            </div>
        </div>
    </footer>
@stop

