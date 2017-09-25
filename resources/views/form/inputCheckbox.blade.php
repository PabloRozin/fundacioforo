<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	<div class="radio_options">
		<label class="radio_option">
			<input type="checkbox" 
				name="{{ $name }}"
				value="1"
				@if(old($name) == '1') checked @endif
				@if($only_view) disabled @endif
			> 
			@if ( ! empty($title))
				{{ $title }}
			@endif
		</label>
	</div>
</div>