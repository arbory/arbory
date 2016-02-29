@extends('leaf::layout.main')

@section('content.header')
    <header>
        @include('leaf::partials.breadcrumbs')
        {{--<form class="search clear-inside has-text-search" action="">--}}
        {{--<div class="text-search"><input name="search" type="text" autofocus="autofocus"/>--}}
        {{--<button class="button only-icon" title="Search" type="submit"><i class="fa fa-search"></i></button>--}}
        {{--</div>--}}
        {{--@if ($model->getFilterBuilder()->getResult()->getFields())--}}
        {{--@include($model->getView('filter'))--}}
        {{--@endif--}}
        {{--</form>--}}
    </header>
@stop

@section('content')

    <section>
        <header>
            <h1>All resources</h1>
            <span class="extras totals only-text">@lang('leaf.pagination.items_found',['total'=>$paginator->total()])</span>
        </header>
        <div class="body">
            @if ($paginator->total() === 0)
            <table class="table">
                <tbody>
                <tr>
                    <th>
                        <div class="nothing_found">Nothing found</div>
                    </th>
                </tr>
                </tbody>
            </table>
            @else
            <table class="table">
                <thead>
                <tr>
                    @foreach ($scheme->getFields() as $field)
                        <th>
                            <a href="{{ route('admin.model.index', [$controller->getSlug(), '_order_by' => $field->getName(), '_order' => Input::get('_order') === 'ASC' ? 'DESC' : 'ASC']) }}">
                                {{ $field->getLabel() }}
                                @if (Input::has('_order_by') && Input::get('_order_by') === $field->getName())
                                    <i class="fa fa-sort-{{ Input::get('_order') === 'DESC' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody class="tbody">
                @foreach ($results->getRows() as $item)
                    <tr class="row" data-id="{{$item->getIdentifier()}}">
                        @foreach ($item->getFields() as $field)
                            <td>{!! $field->render() !!}</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <footer class="main">
            <div class="tools">
                <div class="primary">
                    <a class="button with-icon primary" title="@lang('leaf.resources.create_new')" href="{{ route('admin.model.create', $controller->getSlug()) }}">
                        <i class="fa fa-plus"></i>
                        @lang('leaf.resources.create_new')
                    </a>
                </div>
                <div class="pagination">
                    @if ($paginator->total() > 0)
                    @if( $paginator->currentPage() > 1 )
                        <a class="button only-icon secondary previous" title="@lang('leaf.pagination.previous_page')" rel="prev" href="{{$paginator->url($paginator->currentPage()-1)}}">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    @else
                        <button class="button only-icon secondary previous" title="@lang('leaf.pagination.previous_page')" type="button" autocomplete="off" disabled="disabled">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                    @endif

                    <select name="page">
                        @for( $i = 1; $i<= $paginator->lastPage(); $i++ )
                        <option
                            value="{{$i}}"
                            @if($i==$paginator->currentPage())
                            selected="selected"
                            @endif
                        >{{($i-1)*$paginator->perPage() + 1}} - @if($i==$paginator->lastPage()){{$paginator->total()}}@else{{($i)*$paginator->perPage()}}@endif</option>
                        @endfor
                    </select>

                    @if( $paginator->hasMorePages() )
                        <a class="button only-icon secondary next" title="@lang('leaf.pagination.next_page')" rel="next" href="{{$paginator->url($paginator->currentPage()+1)}}">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="button only-icon secondary next" title="@lang('leaf.pagination.next_page')" type="button" autocomplete="off" disabled="disabled">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    @endif
                    @endif
                </div>
                <div class="secondary"></div>
            </div>
        </footer>
    </section>
@stop
