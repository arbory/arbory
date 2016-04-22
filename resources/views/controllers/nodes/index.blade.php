@extends('leaf::layout.main',['body_class' => 'controller-releaf-content-nodes view-index'])

@section('content')
    <section>

        <header>
            <h1>@lang('leaf.resources.all_resources')</h1>
        </header>

        <div class="body">
            <div class="collection">
                @include('leaf::controllers.nodes.partials.index_row',['rows' => $rows, 'level' => 1])
            </div>
        </div>

        <footer class="main">
            <div class="tools">
                <div class="primary">
                    <a
                            class="button with-icon primary ajaxbox"
                            title="@lang('leaf.resources.create_new')"
                            href="{{route('admin.model.dialog',['model'=>$controller->getSlug(),'dialog'=>'content_types'])}}"
                    ><i class="fa fa-plus"></i>@lang('leaf.resources.create_new')</a>
                </div>
            </div>
        </footer>

    </section>
@stop
