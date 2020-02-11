@extends('arbory::dialogs.base',['class' => 'js-save-filter-dialog'])

@section('dialog')
    <form class="edit_resource" id="edit_resource"
          action="{{ $action }}" accept-charset="UTF-8" method="post">
        <input type="hidden" id="filter" name="filter" />
        @csrf
        <header>
            <h1>{{ trans('arbory::dialog.save_filter.title') }}</h1>
        </header>
        <div class="body">
            <div class="field type-text" data-name="name">
                <div class="label-wrap">
                    <label for="name">
                        <span class="required">*</span>
                        {{ trans('arbory::dialog.save_filter.filter_name') }}
                    </label>
                </div>
                <div class="value">
                    <input type="text" id="name" class="text" name="name">
                </div>
            </div>
        </div>
        <footer>
            <div class="tools">
                <div class="primary">
                    <button class="button with-icon" title="Yes" type="submit" name="save">
                        <i class="fa fa-check"></i>{{ trans('arbory::dialog.save_filter.save') }}
                    </button>
                </div>
            </div>
        </footer>
    </form>
@stop


