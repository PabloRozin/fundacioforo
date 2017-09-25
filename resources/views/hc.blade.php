@extends('layouts.app')

@section('content')

<div class="hc-list">
	
	<div class="center">
		
		<div class="hc-title">Historia Clínica de "{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}"</div>

		<div class="hc-buttons">
			@if (in_array(Auth::user()->permissions, ['professional']))
				<div class="hc-button">
					<a href="{{ route('patients.hc.create', ['patient_id' => $patient->id]) }}" class="btn">Agregar</a>
				</div>
			@endif
			<div class="hc-button">
				<a href="{{ route('patients.index') }}" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button">
				<a href="{{ route('patients.hc', ['patient_id' => $patient->id]) }}?pdf=true" target="_blank" class="btn btn-secondary">PDF</a>
			</div>

			<div class="hc-button-right">
				@include('partials.pagination', ['items' => $hc_dates, 'route' => route('patients.hc', ['patient_id' => $patient->id])])
			</div>
		</div>

		<div class="hc-items">

			<div class="hc-item hc-item-title">
				<div class="hc-item-cont">
					<div class="hc-item-data-cont hc"><!--
						--><div class="hc-item-data hc-date">
							<div class="t">Fecha de consulta</div>
						</div><!--
						--><div class="hc-item-data hc-tipo">
							<div class="t">Tipo de consulta</div>
						</div><!--
						--><div class="hc-item-data hc-professional">
							<div class="t">Profesional</div>
						</div><!--
						@if (in_array(Auth::user()->permissions, ['superadmin', 'professional']))
							--><div class="hc-item-data hc-detail hidden">
								<div class="t">Evolución</div>
							</div><!--
						@endif
					--></div>
				</div>
			</div>

			@foreach($hc_dates as $key => $hc_date)
				<div class="hc-item hc">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont hc"><!--
							--><div class="hc-item-data hc-date">
								<div class="t">Fecha consulta</div>
								<div class="p">{{ date('d-m-Y', strtotime($hc_date->created_at)) }}</div>
							</div><!--
							--><div class="hc-item-data hc-tipo">
								<div class="t">Tipo de consulta</div>
								<div class="p">
									@if ($hc_date->type != 'otros')
										{{ $hc_date->type }}
									@else
										{{ $hc_date->type_info }}
									@endif
								</div>
							</div><!--
							--><div class="hc-item-data hc-professional">
								<div class="t">Profesional</div>
								<div class="p">{{ $hc_date->professional->firstname }} {{ $hc_date->professional->lastname }} ({{ $hc_date->professional->registration_number }})</div>
							</div><!--
							@if (in_array(Auth::user()->permissions, ['superadmin', 'professional']))
								--><div class="hc-item-data hc-detail hidden">
									<div class="t">Evolución</div>
									<div class="p">{{ nl2br($hc_date->detail) }}</div>
									@if( ! empty($hc_date['files']))
										@foreach(explode(',', $hc_date['files']) as $file)
											<div class="hc-item-data hc-file hidden" style="padding:0 10px 0 0">
												<div class="p">
													<a target="_blank" href="{{ asset($file) }}">
														Ver Archivo
													</a>
												</div>
											</div>
										@endforeach
									@endif
								</div><!--
							@endif
						--></div>
						<div class="hc-item-toggle">
							<span class="lnr lnr-chevron-down"></span>
						</div>
					</div>
				</div>
			@endforeach

		</div>

	</div>

</div>

@endsection
