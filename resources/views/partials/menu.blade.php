<aside>
    <div class="compacter">
        <button type="button"><i class="fa fa-angle-double-left"></i></button>
    </div>
    <nav>
        <ul class="block">
            @foreach( app('leaf.menu')->items() as $item )
                @if( !$item->hasChildren() )
                    <li data-name="">
                        <a class="trigger" href="{{$item->url()}}">
                            <i class="{{$item->icon()}}"></i>
                            <span class="name">{{$item->title()}}</span>
                        </a>
                    </li>
                @else
                    <li data-name="">
                        <span class="trigger">
                            <i class="{{$item->icon()}}"></i>
                            <span class="name">{{$item->title()}}</span>
                            <span class="collapser">
                                <button type="button">
                                    <i class="fa fa-chevron-up"></i>
                                </button>
                            </span>
                        </span>
                        <ul class="block">
                            @foreach( $item->children() as $child )
                                <li data-name="">
                                    <a class="trigger" href="{{$child->url()}}">
                                        <i class="fa fa-caret-left"></i>
                                        <span class="name">{{$child->title()}}</span>
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
