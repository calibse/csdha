@if ($paginator->lastPage() > 1)
<div class="main-paginator">
	<p>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</p>
	<div class="controls">
	@unless ($paginator->onFirstPage())
        <p class="previous main-action">
            <a href="{{ $paginator->previousPageUrl() }}">
                <span class="icon"><x-phosphor-caret-left/></span>
                <span class="text">Previous Page</span>
            </a>
        </p>
	@endunless
	@if ($paginator->hasMorePages() && $paginator->currentPage() >= 1)
        <p class="next main-action">
            <a href="{{ $paginator->nextPageUrl() }}">
                <span class="icon"><x-phosphor-caret-right/></span>
                <span class="text">Next Page</span>
            </a>
        </p>
	@endif
	</div>
</div>
@endif
