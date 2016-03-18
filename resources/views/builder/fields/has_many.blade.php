<section class="nested" data-name="{{$field->getName()}}" data-releaf-template="{{$template}}">
    <header>
        <h1 class="subheader nested-title">{{$field->getLabel()}}</h1>
    </header>
    <div class="body list">
        @foreach($relations as $index => $relation)
            @include('leaf::builder.fields.has_many_fieldset',[
                'name'=>$field->getName(),
                'index' => $index,
                'fields' => $relation->getFields()
            ])
        @endforeach
    </div>
    @if($field->canAddRelationItem())
        <footer>
            <button
                    class="button with-icon primary add-nested-item"
                    title="@lang('leaf.fields.has_many.add_item')"
                    type="button"
            ><i class="fa fa-plus"></i>@lang('leaf.fields.has_many.add_item')</button>
        </footer>
    @endif
</section>
