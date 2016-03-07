@if ( $paginator->total() > 0)
    <div class="pagination">
        @if( $paginator->currentPage() > 1 )
            <a class="button only-icon secondary previous" title="@lang('leaf.pagination.previous_page')" rel="prev"
               href="{{$paginator->url($paginator->currentPage()-1)}}">
                <i class="fa fa-chevron-left"></i>
            </a>
        @else
            <button class="button only-icon secondary previous" title="@lang('leaf.pagination.previous_page')"
                    type="button" disabled="disabled">
                <i class="fa fa-chevron-left"></i>
            </button>
        @endif

        <select name="page">
            @for( $i = 1; $i<= $paginator->lastPage(); $i++ )
                <option
                        value="{{$i}}"
                        @if($i==$paginator->currentPage())
                        selected="selected"
                        @endif
                >{{($i-1)*$paginator->perPage() + 1}}
                    - @if($i==$paginator->lastPage()){{$paginator->total()}}@else{{($i)*$paginator->perPage()}}@endif</option>
            @endfor
        </select>

        @if( $paginator->hasMorePages() )
            <a class="button only-icon secondary next" title="@lang('leaf.pagination.next_page')" rel="next"
               href="{{$paginator->url($paginator->currentPage()+1)}}">
                <i class="fa fa-chevron-right"></i>
            </a>
        @else
            <button class="button only-icon secondary next" title="@lang('leaf.pagination.next_page')" type="button"
                    disabled="disabled">
                <i class="fa fa-chevron-right"></i>
            </button>
        @endif
    </div>
@endif
