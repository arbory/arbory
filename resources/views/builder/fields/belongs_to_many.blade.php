<div class="field type-associated-set" data-name="{{$field->getName()}}">
    <div class="label-wrap">
        <label for="resource_permissions">{{$field->getLabel()}}</label>
    </div>
    <div class="value">
        @foreach( $options as $option )
            <div class="type-associated-set-item">
                <input
                        name="resource[{{$field->getName()}}_attributes][{{$option->getValue()}}]"
                        type="checkbox"
                        value="{{$option->getValue()}}"
                        id="resource_permissions_attributes_0_permission"
                        @if($option->isSelected())
                        checked="checked"
                        @endif
                />
                <label for="resource_permissions_attributes_0_permission">{{$option->getLabel()}}</label>
            </div>
        @endforeach
    </div>
</div>




