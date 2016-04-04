<div class="section content-fields">
    @foreach($relation_fields as $relation_field)
        {!! $relation_field->render() !!}
    @endforeach
</div>
