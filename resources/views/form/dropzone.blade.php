 <div class="dropzone" 
 	data-target="dropzone-items-{{ $name }}" 
 	data-action="{{ route('file.store') }}" 
 	data-token="{{ csrf_token() }}" 
 	data-name="{{ $name }}"
 	data-path="{{ $options['path'] }}"
 >
	@if ($title)
		<label for="{{ $name }}">{{ $title }}</label>
	@endif
</div>
<div class="dropzone-items-{{ $name }}"></div>