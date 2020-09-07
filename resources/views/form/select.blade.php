<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	<label for="{{ $name }}">{{ $title }}</label>
	<select name="{{ $name }}"
		@if($only_view)
			disabled
		@endif
	>
		@foreach ($options as $option)
			<option value="{{ $option['id'] }}"
				@if (! isset($value) and isset($option['default']) and $option['default'])
					selected
				@elseif (isset($value) and $option['id'] == $value)
					selected
				@endif
			>
				{{ $option['value'] }}
			</option>
		@endforeach
	</select>
</div>
