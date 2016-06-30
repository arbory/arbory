@extends('leaf::layout.main', ['body_class' => 'controller-releaf-content-nodes view-edit'])

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
                    <h1>{{$title}}</h1>
                    {{-- TODO: toolbox --}}
                </header>
                <div class="body">
                    <div class="section node-fields">
                        @foreach( array_except( $result->getFields(), 'content' ) as $field)
                            {!! $field->render() !!}
                        @endforeach
                    </div>
                    <div class="section content-fields">
                        @foreach( array_only( $result->getFields(), 'content' ) as $field)
                            {!! $field->render() !!}
                        @endforeach
                    </div>
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
