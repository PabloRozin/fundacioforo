@extends('layouts.app')

@section('content')

<div class="popup full_size">
	
	<div class="popup-content vertical_align">

		<div class="popup-cont">

			<div class="popup-close">
				<span class="lnr lnr-cross"></span>
			</div>
			
			<div class="popup-search popup-body">

				<div class="hc-title">Buscador de pacientes</div>

				<form class="form" action="{{ route('patients.index') }}" method="GET">
					
					<div class="item">
						<input class="focus" type="text" name="id" placeholder="ID">
					</div>
					
					<div class="item">
						<input type="text" name="patient_document_number" placeholder="Nº de documento">
					</div>
					
					<div class="item">
						<input type="text" name="patient_firstname" placeholder="Nombre">
					</div>
					
					<div class="item">
						<input type="text" name="patient_lastname" placeholder="Apellido">
					</div>
					
					<div class="item">
						<input type="text" name="patient_phone" placeholder="Teléfono">
					</div>
					
					<div class="item">
						<input type="email" name="patient_email" placeholder="Email">
					</div>
					
					<div class="item">
						<select name="estado">
							<option value="">Habilitado</option>
							<option value="1">Si</option>
							<option value="0">No</option>
						</select>
					</div>

					<div class="item">
						<button type="submit" class="btn">Buscar</button>
					</div>

				</form>

			</div>
			
			@if (in_array(Auth::user()->permissions, ['administrator', 'superadmin', 'professional']))
				<div class="popup-report popup-body">

					<div class="hc-title">Reporte general de pacientes</div>

					<form class="form" action="{{ route('patients.report') }}" method="GET">
						
						<div class="item">
							<input class="focus" type="date" name="since" placeholder="Fecha desde" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>
						
						<div class="item">
							<input type="date" name="to" placeholder="Fecha hasta" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>

						<div class="item">
							<button type="submit" class="btn">Buscar</button>
						</div>

					</form>

				</div>
				
				<div class="popup-report-one popup-body">

					<div class="hc-title">Reporte de paciente <span class="report-data-name"></span></div>

					<form class="form" action="{{ route('patients.report') }}" method="GET">
						
						<div class="item">
							<input class="focus" type="date" name="since" placeholder="Fecha desde" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>
						
						<div class="item">
							<input type="date" name="to" placeholder="Fecha hasta" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>

						<div class="item">
							<button type="submit" class="btn">Buscar</button>
						</div>

					</form>

				</div>
			@endif

		</div>

	</div>

</div>

<div class="hc-list">
	
	<div class="center">
		
		<div class="hc-title">Listado de pacientes</div>

		<div class="hc-buttons">
			@if (in_array(Auth::user()->permissions, ['superadmin', 'professional']))
				<div class="hc-button">
					<a href="{{ route('patients.create') }}" class="btn">Agregar</a>
				</div>
			@endif
			@if (in_array(Auth::user()->permissions, ['superadmin', 'professional', 'administrator']))
				<div class="hc-button">
					<button class="btn btn-secondary act-report">Reporte</a>
				</div>
			@endif
			@if (isset($back_url))
				<div class="hc-button">
					<a href="{{ $back_url }}" type="button" class="btn btn-secondary">Volver</a>
				</div>
			@endif
			<div class="hc-button-right">
				@include('partials.pagination', ['items' => $patients, 'route' => route('patients.index') ])

				<div class="hc-button">
					<button class="btn act-search">Buscar</a>
				</div>
			</div>
		</div>

		<div class="hc-items">

			<div class="hc-item hc-item-title">
				<div class="hc-item-cont">
					<div class="hc-item-data-cont {{ (in_array(Auth::user()->permissions, ['professional'])) ? 'patient' : '' }}"><!--
						--><div class="hc-item-data id">
							<div class="t">Id</div>
						</div><!--
						--><div class="hc-item-data name">
							<div class="t">Nombre y Apellido</div>
						</div><!--
						--><div class="hc-item-data cel hidden">
							<div class="t">Celular</div>
						</div><!--
						--><div class="hc-item-data phone hidden">
							<div class="t">Teléfono</div>
						</div><!--
						--><div class="hc-item-data email hidden">
							<div class="t">Email</div>
						</div><!--
					--></div>
				</div>
			</div>
			
			@if (isset($patientsHighlight))
				@foreach($patientsHighlight as $key => $patient)
					<div class="hc-item">
						<div class="hc-item-cont">
							<div class="hc-item-data-cont {{ (in_array(Auth::user()->permissions, ['professional'])) ? 'patient' : '' }}"><!--
								@if (in_array(Auth::user()->permissions, ['professional']))	
									@if ( ! $patient->professionals()->where('id', $professional->id)->first())
										--><a class="star" href="{{ route('patients.assignProfessional', ['patient_id' => $patient->id]) }}">
											<span class="lnr lnr-star-empty"></span>
										</a><!--
									@else
										--><a class="star active" href="{{ route('patients.unAssignProfessional', ['patient_id' => $patient->id]) }}">
											<span class="lnr lnr-star active"></span>
										</a><!--
									@endif
								@endif
								--><div class="hc-item-data id">
									<div class="t">Id</div>
									<div class="p">{{ $patient->id }}</div>
								</div><!--
								--><div class="hc-item-data name">
									<div class="t">Nombre y Apellido</div>
									<div class="p">{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</div>
								</div><!--
								--><div class="hc-item-data cel hidden">
									<div class="t">Celular</div>
									<div class="p">{{ $patient->patient_cellphone }}</div>
								</div><!--
								--><div class="hc-item-data phone hidden">
									<div class="t">Teléfono</div>
									<div class="p">{{ $patient->patient_phone }}</div>
								</div><!--
								--><div class="hc-item-data email hidden">
									<div class="t">Email</div>
									<div class="p">{{ $patient->patient_email_1 }}</div>
								</div><!--
							--></div>
							<ul class="hc-item-options">
								<li>
									<a href="{{ route('patients.hc', ['patient_id' => $patient['id']]) }}">HC</a>
								</li>
								<li>
									<a href="{{ route('patients.admissions.index', ['patient_id' => $patient['id']]) }}">Admisión</a>
								</li>
								<li>
									<a href="{{ route('patients.edit', ['id' => $patient['id']]) }}">Datos</a>
								</li>
								@if (in_array(Auth::user()->permissions, ['administrator', 'superadmin', 'professional']))
									<li>
										<span class="act-report-one" data-id="{{ $patient['id'] }}" data-name="{{ $patient['patient_firstname'] }} {{ $patient['patient_lastname'] }}">Reporte</span>
									</li>
								@endif
							</ul>
							<div class="hc-item-toggle">
								<span class="lnr lnr-chevron-down"></span>
							</div>
						</div>
					</div>
				@endforeach
			@endif

			@foreach($patients as $key => $patient)
				<div class="hc-item">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont {{ (in_array(Auth::user()->permissions, ['professional'])) ? 'patient' : '' }}"><!--
							@if (in_array(Auth::user()->permissions, ['professional']))	
								@if ( ! $patient->professionals()->where('id', $professional->id)->first())
									--><a class="star" href="{{ route('patients.assignProfessional', ['patient_id' => $patient->id]) }}">
										<span class="lnr lnr-star-empty"></span>
									</a><!--
								@else
									--><a class="star active" href="{{ route('patients.unAssignProfessional', ['patient_id' => $patient->id]) }}">
										<span class="lnr lnr-star active"></span>
									</a><!--
								@endif
							@endif
							--><div class="hc-item-data id">
								<div class="t">Id</div>
								<div class="p">{{ $patient->id }}</div>
							</div><!--
							--><div class="hc-item-data name">
								<div class="t">Nombre y Apellido</div>
								<div class="p">{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</div>
							</div><!--
							--><div class="hc-item-data cel hidden">
								<div class="t">Celular</div>
								<div class="p">{{ $patient->patient_cellphone }}</div>
							</div><!--
							--><div class="hc-item-data phone hidden">
								<div class="t">Teléfono</div>
								<div class="p">{{ $patient->patient_phone }}</div>
							</div><!--
							--><div class="hc-item-data email hidden">
								<div class="t">Email</div>
								<div class="p">{{ $patient->patient_email_1 }}</div>
							</div><!--
						--></div>
						<ul class="hc-item-options">
							<li>
								<a href="{{ route('patients.hc', ['patient_id' => $patient['id']]) }}">HC</a>
							</li>
							@if (in_array(Auth::user()->permissions, ['superadmin', 'professional']))
								<li>
									<a href="{{ route('patients.admissions.index', ['patient_id' => $patient['id']]) }}">Admisión</a>
								</li>
								<li>
									<a href="{{ route('patients.edit', ['id' => $patient['id']]) }}">Datos</a>
								</li>
							@else
								<li>
									<a href="{{ route('patients.show', ['id' => $patient['id']]) }}">Datos</a>
								</li>
							@endif
							@if (in_array(Auth::user()->permissions, ['administrator', 'superadmin', 'professional']))
								<li>
									<span class="act-report-one" data-id="{{ $patient['id'] }}" data-name="{{ $patient['patient_firstname'] }} {{ $patient['patient_lastname'] }}">Reporte</span>
								</li>
							@endif
						</ul>
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
