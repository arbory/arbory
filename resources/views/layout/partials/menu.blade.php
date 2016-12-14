<aside>
    <div class="compacter">
        <button class="button only-icon" title="Collapse" type="button" autocomplete="off" data-title-expand="Expand" data-title-collapse="Collapse">
            <i class="fa fa-angle-double-left"></i>
        </button>
    </div>
    <nav>
        <ul class="block">
        @foreach( app('leaf.menu')->visibleItems() as $item )
            @if( !$item->hasChildren() )
                <li data-name="">
                    <a class="trigger" href="{{$item->getUrl()}}">
                        <abbr title="{{$item->getTitle()}}">{{$item->getAbbreviation()}}</abbr>
                        <span class="name">{{$item->getTitle()}}</span>
                    </a>
                </li>
            @else
                <li data-name="">
                    <span class="trigger">
                        <abbr title="{{$item->getTitle()}}">{{$item->getAbbreviation()}}</abbr>
                        <span class="name">{{$item->getTitle()}}</span>
                        <span class="collapser">
                            <button type="button">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </span>
                    </span>
                    <ul class="block">
                    @foreach( $item->children() as $child )
                        <li data-name="">
                            <a class="trigger" href="{{$child->getUrl()}}">
                                <span class="name">{{$child->getTitle()}}</span>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
        </ul>
    </nav>
</aside>
