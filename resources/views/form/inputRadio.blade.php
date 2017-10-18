<div class="item radio {{ $errors->has($name) ? ' has-error' : '' }}">
	@if ( ! empty($title))
		<label class="title" for="{{ $name }}">{{ $title }}</label>
	@endif
	<div class="radio_options">
		@foreach ($options as $option)
			<label class="radio_option">
				<input type="radio" class="radio-input-{{ $name }}-{{ $option['id'] }}"
					name="{{ $name }}"
					@if($option['id'] == $value or $option['id'] == old($name)) checked @endif
					value="{{ $option['id'] }}"
					@if($only_view) disabled @endif
				> {{ $option['value'] }}
				@if (isset($option['with_text']) and $option['with_text'])
					<div class="radio-text" data-value="{{ $option['id'] }}" data-target=".radio-input-{{ $name }}-{{ $option['id'] }}" data-name="{{ $option['with_text'] }}"></div>
				@endif
			</label>
		@endforeach
	</div>
</div>