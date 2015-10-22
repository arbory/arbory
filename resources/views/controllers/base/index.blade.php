@extends('leaf::admin.index')

@section('content')
    @yield('breadcrumbs')
    @yield('search')
    @yield('header')
    @yield('table')
    @yield('footer')

    @include('leaf::admin.partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs->all()])


    <form action="/admin/users" class="has-text-search search">
        <div class="text-search">
            <div class="wrapper">
                <input autofocus name="search" type="text" value="{{--{{$parameters->get('search')}}--}}">
                <button class="button only-icon" title="Search" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
            <div class="clear"></div>
        </div>
    </form>


    <h2 class="header">
        All title
        <span class="totals">{{$collection->total()}} Resources found</span>
    </h2>

    <table class="table"
           data-items_per_page="{{$collection->perPage()}}"
           data-loading="Loading"
           data-total="{{$collection->total()}}">
        <thead>
        <tr>
            @foreach( $table->columns() as $column )
                <th><span>{{$column->name()}}</span></th>
            @endforeach
        </tr>
        </thead>
        <tbody class="tbody">
        @foreach( $table->rows() as $row )
            <tr class="row" data-id="{{$row->id()}}">
                @foreach( $row->cells() as $cell)
                    <td>{!!$cell!!}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    <footer>
        <div class="tools">
            <div class="primary">
                <a class="button primary" href="/admin/users/new">
                    <i class="fa fa-plus"></i>
                    Create new resource
                </a>
            </div>

            <div class="secondary">
                <div class="extras">
                </div>
            </div>
        </div>
    </footer>
@stop
