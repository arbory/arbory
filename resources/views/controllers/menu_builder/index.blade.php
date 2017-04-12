@extends('leaf::layout.main')

@section('content.header')
    <header>
        {{--@include('leaf::partials.breadcrumbs')--}}
    </header>
@stop

@section('content')
    <section>
        <form action="" novalidate="novalidate">
            <div class="body">
                <div class="field">
                    div.label
                </div>
            </div>
        </form>
    </section>

    <section>
        <header>
            <h1>@lang('leaf::translations.menu_items')</h1>
        </header>
    </section>
@endsection