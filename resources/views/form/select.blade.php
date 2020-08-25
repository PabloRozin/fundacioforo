<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	<label for="{{ $name }}">{{ $title }}</label>
	<select name="{{ $name }}"
		@if($only_view)
			disabled
		@endif
	>
		@foreach ($options as $option)
			<option value="{{ $option['id'] }}"
				@if (isset($option['default']) and $option['default'])
					selected
				@elseif (! is_null($value) and ($option['id'] == $value or $option['id'] == old($name)))
					selected
				@endif
			>
				{{ $option['value'] }}
			</option>
		@endforeach
	</select>
</div>
