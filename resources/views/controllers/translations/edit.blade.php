@extends('leaf::layout.main')

@section('content.header')
    <header>
        @include('leaf::partials.breadcrumbs')
    </header>
@stop

@section('content')
    <section>

        <form method="post" action="{{route('admin.translations.update')}}">

            <input type="hidden" name="namespace" value="{{$namespace}}"/>
            <input type="hidden" name="group" value="{{$group}}"/>
            <input type="hidden" name="item" value="{{$item}}"/>
            <input type="hidden" name="page" value="{{$page}}"/>

            {{ csrf_field() }}

            <header>
                <h1>{{trans('leaf.translations.edit')}} {{$namespace}}::{{$group}}.{{$item}}</h1>
            </header>

            <div class="body">
                @foreach($translations as $translation)
                    <div class="field type-text" data-name="text_{{$translation->locale}}">
                        <div class="label-wrap">
                            <label for="text_{{$translation->locale}}">Kek {{$translation->locale}}</label>
                        </div>
                        <div class="value">

                            <input type="text" id="text_{{$translation->locale}}"
                                   class="text"
                                   name="text_{{$translation->locale}}"
                                   value="{{$input->old('text_' . $translation->locale) ?: $translation->text}}">

                            @if($errors->has('text_' . $translation->locale))
                                <div class="error-box">
                                    <div class="error">{{$errors->get('text_' . $translation->locale)[0]}}</div>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

            <footer class="main">
                <div class="tools">
                    <div class="primary">
                        <button class="button with-icon primary" title="Saglabāt" type="submit" data-type="ok">
                            <i class="fa fa-check"></i>{{trans('leaf.translations.save')}}
                        </button>
                    </div>
                    <div class="secondary">
                        <a class="button with-icon secondary"
                           title="Atpakaļ uz sarakstu"
                           href="{{route('admin.translations.index')}}?page={{$page}}"><i class="fa fa-caret-left"></i>{{trans('leaf.translations.back_to_index')}}
                        </a></div>
                </div>
            </footer>
        </form>
    </section>
@stop
