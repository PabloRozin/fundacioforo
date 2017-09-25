$(document).ready(function() {
	$('.image-upload-button').each(function() {
		id = $(this).attr('data-id');

		$.uploadPreview({
		    input_field: "."+id+"-upload",   // Default: .image-upload
		    preview_box: "."+id+"-preview",  // Default: .image-preview
		    label_field: "."+id+"-label",    // Default: .image-label
		    label_default: "",   // Default: Choose File
		    label_selected: "",  // Default: Change File
		    no_label: true                 // Default: false
		  });
	});
});