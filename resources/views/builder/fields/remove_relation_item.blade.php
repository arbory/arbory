<div class="remove-item-box">
    <button
            class="button only-icon danger remove-nested-item"
            title="@lang('leaf.fields.relation.remove')"
            type="button"
    ><i class="fa fa-trash-o"></i></button>
    <input
            type="hidden"
            class="destroy"
            value="{{$field->getValue()}}"
            name="{{$field->getInputName()}}"
            id="{{$field->getInputId()}}"
    />
</div>
