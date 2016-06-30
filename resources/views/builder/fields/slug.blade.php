@if( $field->isForForm() )
    <div class="field type-text" data-name="{{$field->getName()}}">
        <div class="label-wrap">
            <label for="{{$field->getInputId()}}">{{$field->getLabel()}}</label>
        </div>
        <div class="value">
            <input
                    value="{{$field->getValue()}}"
                    class="text"
                    type="text"
                    name="{{$field->getInputName()}}"
                    id="{{$field->getInputId()}}"
                    data-generator-url="{{$slug_generator_url}}"
            />
            <button class="button only-icon secondary generate" title="Suggest slug" type="button" autocomplete="off"><i class="fa fa-keyboard-o"></i></button>
        </div>
        <div class="link">
            <a href="{{$base_url}}/{{$uri}}">{{$base_url}}/<span>{{$uri}}</span></a>
        </div>
    </div>
@endif

