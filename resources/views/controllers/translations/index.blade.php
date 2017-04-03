@extends('leaf::layout.main')

@section('content.header')
    <header>
        {{--@include('leaf::partials.breadcrumbs')--}}
    </header>
@stop

@section('content')
    <section>

        <header>
            <h1>@lang('leaf::translations.all_translations')</h1>
        </header>

        <div class="body">

            <form>

                <input type="text" name="search" value="{{ $search }}"/>

                <button class="button" type="submit">Search!</button>

            </form>

            <table class="table">

                <thead>
                <tr>

                    <th>Group</th>
                    <th>Key</th>
                    @foreach($languages as $language)
                        <th>Text {{$language->locale}}</th>
                        {{--<th>Locked {{$language->locale}}</th>--}}
                        {{--<th>Unstable {{$language->locale}}</th>--}}
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($translations as $translation)
                    <tr>
                        <td>
                            <a href="{{$translation->edit_url}}">{!! $hhh($translation->namespace) !!} {!! $hhh($translation->group) !!}</a>
                        </td>
                        <td><a href="{{$translation->edit_url}}">{!! $hhh($translation->item) !!}</a></td>

                        @foreach($languages as $language)
                            <td>
                                <a href="{{$translation->edit_url}}">{!! $hhh($translation->{$language->locale . '_text'}) !!}</a>
                            </td>
                            {{--<td>{{ $translation->{$language->locale . '_locked'} }}</td>--}}
                            {{--<td>{{ $translation->{$language->locale . '_unstable'} }}</td>--}}
                        @endforeach

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <footer class="main">
            <div class="tools">
                {{--@include('leaf::partials.pagination')--}}
            </div>
        </footer>

    </section>
@stop
