<div class="field type-richtext" data-name="{{$field->getName()}}">
    <div class="label-wrap">
        <label for="resource_{{$field->getName()}}">{{$field->getLabel()}}</label>
    </div>
    <div class="value">
        <textarea rows="5" cols="50" class="richtext"
                  data-attachment-upload-url=""
                  name="resource[{{$field->getName()}}]"
                  id="{{$field->getName()}}">{{$field->getValue()}}</textarea>
    </div>
</div>
