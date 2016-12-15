@if( $field->isForForm() )
    <div class="field type-text" data-name="{{$field->getName()}}">
        <div class="label-wrap">
            <label for="{{$field->getInputId()}}">{{$field->getLabel()}}</label>
        </div>
        <div class="value">
            <input value="{{$field->getValue()}}" class="text" type="text" name="{{$field->getInputName()}}" id="{{$field->getInputId()}}">
        </div>
    </div>
@else
    <a href="{{$url}}" class="@if(array_key_exists('class',$attributes)){{$attributes['class']}} @endif"><span>{{$field->getValue()}}</span></a>
@endif
