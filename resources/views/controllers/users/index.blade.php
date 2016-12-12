@extends('leaf::layout.main')

@section('content')
    <section>

        <header>
            <h1>@lang('leaf.resources.all_resources')</h1>
            <span class="extras totals only-text">
{{--                @lang('leaf.pagination.items_found',['total'=>$paginator->total()])--}}
            </span>
        </header>

        <div class="body">
            {{--@if ($paginator->total() === 0)--}}
                {{--<table class="table">--}}
                    {{--<tbody>--}}
                    {{--<tr>--}}
                        {{--<th>--}}
                            {{--<div class="nothing_found">@lang('leaf.resources.nothing_found')</div>--}}
                        {{--</th>--}}
                    {{--</tr>--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--@else--}}
                <table class="table">
                    <thead>
                    <tr>
                        {{--@foreach ($field_set->getFields() as $field)--}}
                            {{--<th>--}}
                                {{--@if( $field->isSortable())--}}
                                    {{--<a href="{{ route('admin.model.index', [--}}
                                    {{--$controller->getSlug(),--}}
                                    {{--'search' => Input::get('search'),--}}
                                    {{--'_order_by' => $field->getName(),--}}
                                    {{--'_order' => Input::get('_order') === 'ASC' ? 'DESC' : 'ASC',--}}
                                {{--]) }}">--}}
                                        {{--{{ $field->getLabel() }}--}}
                                        {{--@if (Input::get('_order_by') === $field->getName())--}}
                                            {{--<i class="fa fa-sort-{{ Input::get('_order') === 'DESC' ? 'up' : 'down' }}"></i>--}}
                                        {{--@endif--}}
                                    {{--</a>--}}
                                {{--@else--}}
                                    {{--{{ $field->getLabel() }}--}}
                                {{--@endif--}}
                            {{--</th>--}}
                        {{--@endforeach--}}
                        <th>&nbsp;</th>
                        <th>email</th>
                        <th>first name</th>
                        <th>last name</th>
                        <th>roles</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody class="tbody">
                    @foreach ($users as $user)
                        <tr class="row" data-id="{{$user->getKey()}}">
                            <td><img src="//www.gravatar.com/avatar/{{ md5($user->email) }}?d=retro" width="32" alt="{{ $user->email }}"></td>
                            <td><a href="{{ route('admin.users.edit', $user->id) }}"><span>{{ $user->email }}</span></a></td>
                            <td><a href="{{ route('admin.users.edit', $user->id) }}"><span>{{ $user->first_name }}</span></a></td>
                            <td><a href="{{ route('admin.users.edit', $user->id) }}"><span>{{ $user->last_name }}</span></a></td>
                            <td>
                                <span>
                                @if ($user->roles->count() > 0)
                                    {{ $user->roles->implode('name', ', ') }}
                                @else
                                    <em>No Assigned Role</em>
                                @endif
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.destroy', $user->id) }}" data-method="delete" data-token="{{ csrf_token() }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            {{--@endif--}}
        </div>

        <footer class="main">
            <div class="tools">
                <div class="primary">
                    <a class="button with-icon primary" title="@lang('leaf.resources.create_new')" href="{{ route('admin.users.create') }}">
                        <i class="fa fa-plus"></i>
                        @lang('leaf.resources.create_new')
                    </a>
                </div>
                {{--@include('leaf::partials.pagination')--}}
                <div class="secondary"></div>
            </div>
        </footer>


    </section>


@stop
