@if( $field->isForForm() )
    <div class="field type-text" data-name="{{$field->getName()}}">
        <div class="label-wrap">
            <label for="{{$field->getInputId()}}">{{$field->getLabel()}}</label>
        </div>
        <div class="value">
            <select name="{{$field->getInputName()}}"
                    id="{{$field->getInputId()}}"
                    class="@if(array_key_exists('class',$attributes)){{$attributes['class']}} @endif">
                @foreach($options as $option)
                    <option
                            value="{{$option->getValue()}}"
                            @if($option->isSelected())selected @endif
                            class="@if(array_key_exists('class', $option->getAttributes())){{$option->getAttributes()['class']}} @endif">
                        {{$option->getLabel()}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@else
    <a href="{{$url}}"
       class="@if(array_key_exists('class',$attributes)){{$attributes['class']}} @endif"><span>{{$current_option->getLabel()}}</span></a>
@endif
