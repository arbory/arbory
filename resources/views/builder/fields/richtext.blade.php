<div class="field type-richtext" data-name="{{$field->getName()}}">
    <div class="label-wrap">
        <label for="{{$field->getInputId()}}">{{$field->getLabel()}}</label>
    </div>
    <div class="value">
        <textarea rows="5" cols="50" class="richtext"
                  data-attachment-upload-url=""
                  name="{{$field->getInputName()}}"
                  id="{{$field->getInputId()}}"
        >{{$field->getValue()}}</textarea>
    </div>
</div>
