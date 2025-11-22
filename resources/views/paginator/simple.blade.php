@if ($paginator->lastPage() > 1)
<div class="main-paginator">
	<p>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</p>
	<div class="controls">
	@unless ($paginator->onFirstPage())
        <p class="previous main-action">
            <a href="{{ $paginator->previousPageUrl() }}">
		<img class="icon" src="{{ asset('icon/light/caret-left.png') }}">
                <span class="text">Previous Page</span>
            </a>
        </p>
	@endunless
	@if ($paginator->hasMorePages() && $paginator->currentPage() >= 1)
        <p class="next main-action">
            <a href="{{ $paginator->nextPageUrl() }}">
		<img class="icon" src="{{ asset('icon/light/caret-right.png') }}">
                <span class="text">Next Page</span>
            </a>
        </p>
	@endif
	</div>
</div>
@endif
