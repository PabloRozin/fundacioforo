<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	<label for="{{ $name }}">{{ $title }}</label>
	<select name="{{ $name }}"
		@if($only_view)
			disabled
		@endif
	>
		@foreach ($options as $option)
			<option value="{{ $option['id'] }}" 
				@if($option['id'] == $value or $option['id'] == old($name)) 
					selected
				@elseif ( ! isset($value) and isset($option['defalut']) and $option['defalut'])
					selected
				@endif 

			>
				{{ $option['value'] }}
			</option>
		@endforeach
	</select>
</div>