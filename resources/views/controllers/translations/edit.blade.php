@extends('arbory::layout.main')

@section('content.header')
    {!! $header !!}
@stop

@section('content')
    <section>

        <form method="post" action="{{$update_url}}">

            <input type="hidden" name="namespace" value="{{$namespace}}"/>
            <input type="hidden" name="group" value="{{$group}}"/>
            <input type="hidden" name="item" value="{{$item}}"/>

            {{ csrf_field() }}

            <header>
                <h1>{{trans('arbory.translations.edit')}} {{$namespace}}::{{$group}}.{{$item}}</h1>
            </header>

            <div class="body">
                @foreach($translations as $translation)
                    <div class="field type-text" data-name="text_{{$translation->locale}}">
                        <div class="label-wrap">
                            <label for="text_{{$translation->locale}}">{{$translation->locale}}</label>
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
                        <button class="button with-icon primary" title="{{trans('arbory.translations.save')}}" type="submit" data-type="ok">
                            <i class="fa fa-check"></i>{{trans('arbory.translations.save')}}
                        </button>
                    </div>
                    <div class="secondary">
                        <a class="button with-icon secondary"
                           title="{{trans('arbory.translations.back_to_index')}}"
                           href="{{$back_to_index_url}}"><i class="fa fa-caret-left"></i>{{trans('arbory.translations.back_to_index')}}
                        </a></div>
                </div>
            </footer>
        </form>
    </section>
@stop
