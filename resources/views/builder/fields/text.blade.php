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
    <strong><a href="{{$url}}">{{$field->getValue()}}</a></strong>
@endif
