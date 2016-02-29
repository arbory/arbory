@if( $field->isForForm() )
    <div class="field type-item" data-name="{{$field->getName()}}_id">
        <div class="label-wrap">
            <label for="resource_role_id">{{$field->getLabel()}}</label>
        </div>
        <div class="value">
            <select name="resource[{{$field->getName()}}_id]" id="resource_role_id">
                <option value=""></option>
                @foreach($items as $key => $value)
                    <option @if($key===$field->getValue()) selected="selected" @endif value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
@else
    <strong><a href="{{$url}}">{{$field->getValue()}}</a></strong>
@endif





