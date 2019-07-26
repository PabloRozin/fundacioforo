<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	<div class="options">
		<button class="btn btn-secondary toggle-multiselect" data-target="multiselect_{{ $name }}" type="button">Todos/Ninguno</button>
	</div>
	<div class="row" ><!--
		@foreach ($options as $option)
			--><div class="option col col-1-4">
				<input type="checkbox" class="multiselect_{{ $name }}" name="multiselect_{{ $name }}[]" value="{{ $option->id }}" {{ (is_array($values) and in_array($option->id, $values)) ? 'checked' : '' }} > {{ $option->firstname }} {{ $option->lastname }}
			</div><!--
		@endforeach
	--></div>
</div>
