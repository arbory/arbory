<section class="nested" data-name="{{$field->getName()}}" data-releaf-template="{{$template}}">

    @if($field_title)
        <header>
            <h1 class="subheader nested-title">{{$field_title}}</h1>
        </header>
    @endif

    <div class="body list">
        @foreach($relations as $index => $relation)
            @include('leaf::builder.fields.translatable_fieldset',[
                'name'=>$field->getName(),
                'index' => $index,
                'fields' => $relation->getFields()
            ])
        @endforeach
    </div>
</section>
