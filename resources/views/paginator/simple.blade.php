@if ($paginator->lastPage() > 1)
<nav class="paginator">
	<p>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</p>
	<div class="controls">
	@unless ($paginator->onFirstPage())
		<div class="previous">
			<div class="main-actions">
				<a href="{{ $paginator->previousPageUrl() }}">
					<span class="icon"><x-phosphor-caret-left/></span> 
					<span class="text">Previous Page</span>
				</a>
			</div>
		</div>
	@endunless
	@if ($paginator->hasMorePages() && $paginator->currentPage() >= 1)
		<div class="next">
			<div class="main-actions">
				<a href="{{ $paginator->nextPageUrl() }}">
					<span class="icon"><x-phosphor-caret-right/></span>
					<span class="text">Next Page</span>
				</a>
			</div>
		</div>
	@endif
	</div>
</nav>
@endif
