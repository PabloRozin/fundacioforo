@if ($items->lastPage() > 1)
	<div class="hc-pagination">
		<!--
		<div class="hc-pagination-text">Página</div>
		<div class="hc-pagination-cont">
			<div class="icon lnr lnr-chevron-down"></div>
			<select name="pa" class="page-filter">
				@for($p = 1; $p <= $items->lastPage(); $p++)
					<option 
						value="{{ $route . '?page=' . $p }}"
						{{ ($items->currentPage() == $p) ? 'selected' : ''}}
					>
						{{ $p }}
					</option>
				@endfor
			</select>
		</div>
		<div class="hc-pagination-text">de {{ $items->lastPage() }}</div>
		-->
		<div class="hc-button">
			@if ($items->currentPage() > 1)
				<a href="{{ $route . '?page=' . ($items->currentPage() - 1) }}" class="btn btn-secondary lnr lnr-chevron-left"></a>
			@endif
		</div>
		<div class="hc-button">
			@if ($items->currentPage() + 1 <= $items->lastPage())
				<a href="{{ $route . '?page=' . ($items->currentPage() + 1) }}" class="btn btn-secondary lnr lnr-chevron-right"></a>
			@endif
		</div>
	</div>
@endif