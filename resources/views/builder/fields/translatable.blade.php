<div class="field type-text i18n" data-name="{{$name}}"> {{-- TODO: Field type --}}
    @foreach($fields as $fieldLocale => $localizedField)
        <div class="localization @if($fieldLocale===$locale) active @endif " data-locale="{{$fieldLocale}}">
            {!! $localizedField->render() !!}
        </div>
    @endforeach
    <div class="localization-switch">
        <button name="button" type="button" title="Pārslēgt valodu" class="trigger">{{-- TODO: Translate title --}}
            <span class="label">{{$locale}}</span>
            <i class="fa fa-chevron-down"></i>
        </button>
        <menu class="localization-menu-items" type="toolbar">
            <ul>
                @foreach( $locales as $locale )
                    <li>
                        <button type="button" data-locale="{{$locale}}">{{$locale}}</button>
                    </li>
                @endforeach
            </ul>
        </menu>
    </div>
</div>
