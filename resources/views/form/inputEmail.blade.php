<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	@if ($title)
		<label for="{{ $name }}">{{ $title }}</label>
	@endif
	<input 
		autocomplete="off"
		type="email"
		name="{{ $name }}"
		@if(isset($value)) 
			value="{{ $value }}"
		@elseif(old($name))
			value="{{ old($name) }}" 
		@endif
		@if($only_view or $not_updatable)
			disabled
		@endif
	>
</div>