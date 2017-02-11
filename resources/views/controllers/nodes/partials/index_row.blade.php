<ul data-level="{{$level}}">
    @foreach( $rows as $row )
        <li class="@if($row->hasChildRows()) has-children @endif collapsed"
            data-level="{{$level}}"
            data-id="{{$row->getIdentifier()}}"
        >
            <div class="only-icon toolbox-cell">
{{--                {{$row->getToolBox()}}--}}
                {{--{!! $row->getFieldByName('tools')->render() !!}--}}

                <div class="toolbox" data-url="{{ route( 'admin.model.dialog', [
                        'model' => $controller->getSlug(),
                        'dialog' => 'toolbox',
                        'id' => $row->getIdentifier(),
                    ] )}}">
                    <button class="button trigger only-icon" type="button" title="Tools"><i class="fa fa-ellipsis-v"></i></button>
                    <menu class="toolbox-items" type="toolbar"><i class="fa fa-caret-up"></i>
                        <ul></ul>
                    </menu>
                </div>
            </div>

            @if($row->hasChildRows())
                <div class="collapser-cell">
                    <button
                            class="button only-icon secondary collapser trigger"
                            title="Collapse"
                            type="button"
                    ><i class="fa fa-chevron-right"></i></button>
                </div>
            @endif

            <div class="node-cell active">
                @foreach( $row->getFields() as $field )
                    <a href="{{route( 'admin.model.edit', [
                        $controller->getSlug(),
                        $row->getIdentifier()
                    ])}}" class="trigger">
                        <span>
                            {{(string)$field}}
                        </span>
                    </a>
                @endforeach
            </div>

            @if( $row->hasChildRows() )
                @include('leaf::controllers.nodes.partials.index_row',['rows' => $row->getChildRows(),'level' => $level + 1])
            @endif

        </li>
    @endforeach
</ul>
