<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	@if ($title)
		<label for="{{ $name }}">{{ $title }}</label>
	@endif
	<input 
		autocomplete="false"
		type="password"
		name="{{ $name }}"
	>
</div>