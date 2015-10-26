@if( $breadcrumbs )
<nav>
    <ul class="block breadcrumbs">
        @foreach( $breadcrumbs as $item )
        <li>
            <a href="{{$item['link']}}">{{$item['title']}}</a>
            @if ($item != end($breadcrumbs))
                <i class="fa fa-small fa-chevron-right"></i>
            @endif
        </li>
        @endforeach
    </ul>
</nav>
@endif
