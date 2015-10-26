@extends('leaf::admin.layout.main')

@section('content')
    <header>
        @include('leaf::admin.partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs->all()])
        {{--TODO: Move search form to partial--}}
        <form class="search clear-inside has-text-search" action="">
            <div class="text-search"><input name="search" type="text" autofocus="autofocus" />
                <button class="button only-icon" title="Search" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </header>
    <section>
        <header>
            <h1>All resources</h1>
            <span class="extras totals">{{$collection->total()}} resources found</span>
        </header>
        <div class="body">
            <table class="table">
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
        </div>
        <footer class="main">
            <div class="tools">
                <div class="primary">
                    <a class="button with-icon primary" title="Create new resource" href="/admin/news/new">
                        <i class="fa fa-plus"></i>
                        Create new resource
                    </a>
                </div>
                <div class="pagination">
                    <span class="previous_page button secondary disabled">
                        <i class="fa fa-chevron-left"></i>
                    </span>

                    <div class="pages">
                        <select id="page_select" name="page">
                            <option value="1" selected="selected">1 - 40</option>
                            <option value="2">41 - 80</option>
                            <option value="3">81 - 81</option>
                        </select>
                    </div>
                    <a class="next_page button secondary" rel="next" href="/admin/news?page=2">
                        <span>
                            <i class="fa fa-chevron-right"></i>
                        </span>
                    </a>
                </div>
                <div class="secondary"></div>
            </div>
        </footer>
    </section>
@stop
