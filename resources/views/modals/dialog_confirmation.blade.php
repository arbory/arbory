@extends('leaf::modals.dialog',['class' => 'confirm-destroy confirm'])

@section('dialog')
    <form class="edit_resource" id="edit_resource" action="{{$form_target}}" accept-charset="UTF-8" method="post">
        <input type="hidden" name="_method" value="delete"/>
        {{csrf_field()}}
        <header>
            @yield('dialog.header')
        </header>
        <div class="body">
            @yield('dialog.body')
        </div>
        <footer>
            <div class="tools">
                <div class="primary">
                    @yield('dialog.tools.primary')
                </div>
                <div class="secondary">
                    @yield('dialog.tools.secondary')
                </div>
            </div>
        </footer>
    </form>
@stop
