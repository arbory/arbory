@extends('leaf::layout.main')

@section('content.header')
    @include('leaf::controllers.resource.partials.index_header')
@stop

@section('content')
    <section>

        <header>
            <h1>@lang('leaf.resources.all_resources')</h1>
            <span class="extras totals only-text">@lang('leaf.pagination.items_found',['total'=>$paginator->total()])</span>
        </header>

        <div class="body">
            @include('leaf::controllers.resource.partials.index_content_table')
        </div>

        @include('leaf::controllers.resource.partials.index_footer')

    </section>
@stop
