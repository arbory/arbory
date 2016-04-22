<ul data-level="{{$level}}">
    @foreach( $rows as $row )
        <li class="@if($row->hasChildRows()) has-children @endif collapsed"
            data-level="{{$level}}"
            data-id="{{$row->getIdentifier()}}"
        >
            <div class="only-icon toolbox-cell">
                {!! $row->getFieldByName('tools')->render() !!}
            </div>

            @if($row->hasChildRows())
                <div class="collapser-cell">
                    <button
                            class="button only-icon secondary collapser trigger"
                            title="Collapse"
                            type="button"
                    ><i class="fa fa-chevron-down"></i></button>
                </div>
            @endif

            <div class="node-cell active">
                {!! $row->getFieldByName('name')->render(['class' => 'trigger']) !!}
            </div>

            @if( $row->hasChildRows() )
                @include('leaf::controllers.nodes.partials.index_row',['rows' => $row->getChildRows(),'level' => $level + 1])
            @endif

        </li>
    @endforeach
</ul>
