<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	@if ($title)
		<label for="{{ $name }}">{{ $title }}</label>
	@endif
	<div class="row"><!--
		@foreach ($options as $option)
			--><div class="option col col-1-4">
				<input type="checkbox" name="multiselect_{{ $name }}[]" value="{{ $option->id }}" {{ (is_array($values) and in_array($option->id, $values)) ? 'checked' : '' }} > {{ $option->firstname }} {{ $option->lastname }}
			</div><!--
		@endforeach
	--></div>
</div>
