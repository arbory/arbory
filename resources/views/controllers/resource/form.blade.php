@extends('leaf::layout.main')

@section('content.header')
    <header>
        @include('leaf::partials.breadcrumbs')
    </header>
@stop

@section('content')
    <section>
        @if(!empty($id))
        <form
            id="edit-resource"
            class="edit-resource"
            {{--data-remote="true"--}}
            {{--data-remote-validation="true"--}}
            {{--data-type="json"--}}
            novalidate="novalidate"
            enctype="multipart/form-data"
            action="{{route( 'admin.model.update', [ $slug, $id ] )}}"
            accept-charset="UTF-8"
            method="post"
            >
            <input name="_method" type="hidden" value="PUT">
        @else
        <form
            id="create-resource"
            class="create-resource"
            {{--data-remote="true"--}}
            {{--data-remote-validation="true"--}}
            {{--data-type="json"--}}
            novalidate="novalidate"
            enctype="multipart/form-data"
            action="{{route( 'admin.model.store', [ $slug ] )}}"
            accept-charset="UTF-8"
            method="post"
            >
        @endif
            {{csrf_field()}}
            <input type="hidden" name="index_url" id="index_url" value="{{route( 'admin.model.index', [ $slug ] )}}">

            <header>
                <h1>Big Changes – Big Opportunities (English summary)</h1>
                @if(!empty($id))
                <div class="extras toolbox-wrap">
                    <div class="toolbox initialized"
                         data-url="{{route('admin.model.action',[$slug,$id,'toolbox'])}}">
                        <button class="button trigger only-icon" type="button" title="Tools">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <menu class="toolbox-items" type="toolbar">
                            <i class="fa fa-caret-up"></i>
                            <ul>
                            </ul>
                        </menu>
                    </div>
                </div>
                @endif
            </header>
            <div class="body">
                @foreach( $result->getFields() as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
            <footer class="main">
                <div class="tools">
                    <div class="primary">
                        <button class="button with-icon primary" title="Save" type="submit" data-type="ok" data-disable="true">
                            <i class="fa fa-check"></i>Save
                        </button>
                    </div>
                    <div class="secondary">
                        <a class="button with-icon secondary" title="Back to list" href="{{route( 'admin.model.index', [ $slug ] )}}">
                            <i class="fa fa-caret-left"></i>
                            Back to list
                        </a>
                    </div>
                </div>
            </footer>
        </form>
    </section>
@stop
