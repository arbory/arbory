@if ($paginator->total() === 0)
    <table class="table">
        <tbody>
        <tr>
            <th>
                <div class="nothing_found">@lang('leaf.resources.nothing_found')</div>
            </th>
        </tr>
        </tbody>
    </table>
@else
    <table class="table">
        <thead>
        <tr>
            @foreach ($field_set->getFields() as $field)
                <th>
                    @if( $field->isSortable())
                        <a href="{{ route('admin.model.index', [
                                    $controller->getSlug(),
                                    'search' => Input::get('search'),
                                    '_order_by' => $field->getName(),
                                    '_order' => Input::get('_order') === 'ASC' ? 'DESC' : 'ASC',
                                ]) }}">
                            {{ $field->getLabel() }}
                            @if (Input::get('_order_by') === $field->getName())
                                <i class="fa fa-sort-{{ Input::get('_order') === 'DESC' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    @else
                        {{ $field->getLabel() }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="tbody">
        @foreach ($results->getRows() as $item)
            <tr class="row" data-id="{{$item->getIdentifier()}}">
                @foreach ($item->getFields() as $field)
                    <td><a href="{{route( 'admin.model.edit', [
                        $controller->getSlug(),
                        $item->getIdentifier()
                    ])}}"><span>{!! $field->render() !!}</span></a></td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
