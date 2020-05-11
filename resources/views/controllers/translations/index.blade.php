@extends('arbory::layout.main')

@section('content.header')
    {!! $header !!}
@stop

@section('content')
    <section>

        <header>
            <h1>@lang('arbory::translations.all_translations')</h1>
        </header>

        <div class="body">
            <table class="table">
                <thead>
                <tr>
                    <th>Group</th>
                    <th>Key</th>
                    @foreach($languages as $language)
                        <th>Text {{$language->locale}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($translations as $translation)
                    <tr>
                        <td>
                            <a href="{{$translation->edit_url}}">{!! $highlight($translation->namespace) !!}::{!! $highlight($translation->group) !!}</a>
                        </td>
                        <td><a href="{{$translation->edit_url}}">{!! $highlight($translation->item) !!}</a></td>
                        @foreach($languages as $language)
                            <td>
                                <a href="{{$translation->edit_url}}">{!! $highlight($translation->{$language->locale . '_text'}) !!}</a>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@stop
