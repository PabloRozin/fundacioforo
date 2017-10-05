@extends('layouts.app')

@section('content')

<div class="hc-form">
	
	<div class="center">

		<!--
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		-->

		@if (isset($only_view) and $only_view)
			<div class="form">
		@else
			<form class="form" action="{{ $form_url }}" method="POST" enctype="multipart/form-data" autocomplete="false">
		@endif

			@if ($form_method == 'PUT')
				<input type="hidden" name="_method" value="put" >
			@endif

			{{ csrf_field() }}

			<div class="hc-form-title">{{ $title }}</div>

			@foreach ($items as $group_name => $group_items)
				
				<div class="hc-form-subtitle">{{ $group_name }}</div>

				@foreach ($group_items as $subgroup_name => $subgroup_items)
				
					@if ( ! empty($subgroup_name) and $subgroup_name != 'table')
						<div class="hc-form-subtitle-2">{{ $subgroup_name }}</div>
					@else
						<div style="height: 1px; margin-top: -31px;"></div>
					@endif

					<div class="row {{ ($subgroup_name == 'table') ? 'table' : ''}}"><!--

						@foreach ($subgroup_items as $item_name => $item)
							
							@if ( ! in_array(Auth::user()->permissions, ['professional']) or ! isset($item['user_data']))

								@if ( ! isset($item['not_show_to']) or  ! in_array(Auth::user()->permissions, $item['not_show_to']))
									
									--><div class="col {{ $item['css_class'] }}">
										@include('form.'.$item['type'], [
											'title' => (isset($item['title'])) ? $item['title'] : null,
											'name' => $item_name, 
											'options' => (isset($item['options'])) ? $item['options'] : null, 
											'value' => (isset($item['value'])) ? $item['value'] : null,
											'min' => (isset($item['min'])) ? $item['min'] : '1900-01-01',
											'max' => (isset($item['max'])) ? $item['max'] : date('Y-m-d'),
											'only_view' => (isset($only_view) and $only_view) ? true : false,
											'config' => (isset($item['config'])) ? $item['config'] : []
										])
									</div><!--

								@endif
							
							@endif

						@endforeach

					--></div>

				@endforeach

			@endforeach

			<div class="hc-form-buttons">
				@if ( ! isset($only_view) or ! $only_view)
					<div class="hc-form-button">
						<button type="submit" class="btn">Guardar</button>
					</div>
				@endif
				<div class="hc-form-button">
					<a href="{{ $back_url }}" type="submit" class="btn btn-secondary">Volver</a>
				</div>
			</div>

		@if (isset($only_view) and $only_view)
			</div>
		@else
			</form>
		@endif

	</div>

</div>

@endsection