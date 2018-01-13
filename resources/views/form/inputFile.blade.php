 <div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	@if ($title)
		<label for="{{ $name }}">{{ $title }}</label>
	@endif
	@if(isset($value))
		<img src="{{ $value }}" alt="">
	@endif
	<br>
	<input 
		type="file"
		name="{{ $name }}"
	>
</div>