<header>
    @include('leaf::partials.breadcrumbs')
    <form class="search has-text-search" action="{{ route('admin.model.index', [$controller->getSlug()])}}">
        <div class="text-search">
            <div class="search-field" data-name="search">
                <input name="search" type="search" class="text" autofocus="autofocus" value="{{Input::get('search')}}"/>
                <button class="button only-icon" title="@lang('leaf.filter.search')" type="submit" autocomplete="off">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</header>
