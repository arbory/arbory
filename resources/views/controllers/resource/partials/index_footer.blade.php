<footer class="main">
    <div class="tools">
        <div class="primary">
            <a class="button with-icon primary" title="@lang('leaf.resources.create_new')" href="{{ route('admin.model.create', $controller->getSlug()) }}">
                <i class="fa fa-plus"></i>
                @lang('leaf.resources.create_new')
            </a>
        </div>
        @include('leaf::partials.pagination')
        <div class="secondary"></div>
    </div>
</footer>
