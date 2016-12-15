<a href="{{$url}}" class="@if(array_key_exists('class',$attributes)){{$attributes['class']}} @endif">
    <span>
        <img src="//www.gravatar.com/avatar/{{ md5($email) }}?d=retro" width="32" alt="{{ $email }}">
    </span>
</a>
