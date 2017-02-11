@extends('leaf::layout.main')

@section('content.header')
    <header>
        @include('leaf::partials.breadcrumbs')
        <form class="search has-text-search" action="{{-- route('admin.model.index', [$controller->getSlug()])--}}">
            <div class="text-search">
                <div class="search-field" data-name="search">
                    <input name="search" type="search" class="text" autofocus="autofocus" value="{{Input::get('search')}}"/>
                    <button class="button only-icon" title="@lang('leaf.filter.search')" type="submit" autocomplete="off">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </header>
@stop

@section('content')
    <section>

        <header>
            <h1>@lang('leaf.resources.all_resources')</h1>
            <span class="extras totals only-text">@lang('leaf.pagination.items_found',['total'=>$page->total()])</span>
        </header>

        <div class="body">

            @if ($page->total() === 0)
                <table class="table">
                    <tbody>
                    <tr>
                        <th>
                            <div class="nothing_found">@lang('leaf.resources.nothing_found')</div>
                        </th>
                    </tr>
                    </tbody>
                </table>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        @foreach ($fields as $field)
                            <th>
                                @if( $field->isSortable())
                                    <a href="{{$routes->getUrl('index',[
                                        'search' => Input::get('search'),
                                        '_order_by' => $field->getName(),
                                        '_order' => Input::get('_order') === 'ASC' ? 'DESC' : 'ASC',
                                    ])}}">
                                        {{ $field->getLabel() }}
                                        @if (Input::get('_order_by') === $field->getName())
                                            <i class="fa fa-sort-{{ Input::get('_order') === 'DESC' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                @else
                                    {{ $field->getLabel() }}
                                @endif
                            </th>
                        @endforeach
                        {{--Toolbox--}}
                        {{--<th>&nbsp;</th>--}}
                    </tr>
                    </thead>
                    <tbody class="tbody">
                    @foreach ($page as $item)
                        <tr class="row" data-id="{{$item->getIdentifier()}}">
                            @foreach ($item->getFields() as $field)
                                <td>
                                    <a href="{{$routes->getUrl('edit',[$item->getIdentifier()])}}">
                                        <span>{!! $field !!}</span>
                                    </a>
                                </td>
                            @endforeach
                            {{--Toolbox--}}
                            <td>
                                <div class="toolbox" data-url="{{$routes->getUrl('dialog',['dialog'=>'toolbox', 'id' => $item->getIdentifier()])}}">
                                    <button class="button trigger only-icon" type="button" title="Tools"><i class="fa fa-ellipsis-v"></i></button>
                                    <menu class="toolbox-items" type="toolbar"><i class="fa fa-caret-up"></i>
                                        <ul></ul>
                                    </menu>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif



        </div>

        {{--@include('leaf::controllers.resource.partials.index_footer')--}}

    </section>
@stop
