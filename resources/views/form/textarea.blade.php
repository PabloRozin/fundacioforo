<div class="item {{ $errors->has($name) ? ' has-error' : '' }}">
	@if ($title)
		<label for="{{ $name }}">{{ $title }}</label>
	@endif
	<textarea 
		type="text"
		name="{{ $name }}"
		@if($only_view)
			disabled
		@endif
	>@if(isset($value)){{ $value }}@elseif(old($name)){{ old($name) }}@endif</textarea>
</div>