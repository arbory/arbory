<fieldset class="item type-association" data-name="{{$name}}" data-index="{{$index}}">
    @foreach($fields as $relationField)
        {!! $relationField->render() !!}
    @endforeach
</fieldset>
