<div class="side">

    <div class="compacter">
        <button type="button">
            <i class="fa fa-angle-double-left toggle-angle-icon"></i>
        </button>
    </div>

    <nav>
        <ul class="block">
            @foreach( app('leaf.menu')->items() as $item )
                @if( !$item->hasChildren() )
                    <li>
                        <a class="trigger" href="{{$item->url()}}">
                            <i class="{{$item->icon()}}"></i>
                            <span class="name">{{$item->title()}}</span>
                        </a>
                    </li>
                @else
                    <li>
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
                            <li>
                                <a class="trigger" href="{{$child->url()}}">
                                    <span class="name">{{$child->title()}}</span>
                                    <i class="fa fa-caret-left"></i>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    <div class="clear"></div>

    <footer>
        @yield('menu.footer')
    </footer>
</div>
