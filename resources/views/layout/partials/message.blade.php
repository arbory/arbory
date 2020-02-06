@if (session('success') || $errors->count())
    <div class="notifications {{ $class ?? '' }}">
        @foreach($errors->all() as $error)
            <div class="notification" data-type="error">{{ $error }}</div>
        @endforeach

        @if (session('success'))
            <div class="notification" data-type="success">{{ session('success') }}</div>
        @endif
    </div>
@endif