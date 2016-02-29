@if( $field->isForForm() )
    <div class="field type-text" data-name="{{$field->getName()}}">
        <div class="label-wrap">
            <label for="resource_{{$field->getName()}}">{{$field->getLabel()}}</label>
        </div>
        <div class="value">
            <input value="{{$field->getValue()}}" class="text" type="text" name="resource[{{$field->getName()}}]" id="resource_{{$field->getName()}}">
        </div>
    </div>
@else
    <strong><a href="{{$url}}">{{$field->getValue()}}</a></strong>
@endif
