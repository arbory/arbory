@foreach( $items as $item )
    <li>
        <a class="button ajaxbox" title="{{$item->getTitle()}}" href="{{$item->getUrl()}}">{{$item->getTitle()}}</a>
    </li>
@endforeach
