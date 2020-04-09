@extends('layouts.app')

@section('content')<div class="popup full_size">

	<div class="popup-content vertical_align">

		<div class="popup-cont">

			<div class="popup-close">
				<span class="lnr lnr-cross"></span>
			</div>

			<div class="popup-search popup-body">

				<div class="hc-title">Buscador de recetas</div>

				<form class="form" action="{{ route('patients.prescriptions.index', ['patient_id' => $patient->id]) }}" method="GET">

					<div class="item">
						<input type="date" name="date" placeholder="Fecha">
					</div>

					<div class="item">
						<input type="text" name="professional_firstname" placeholder="Nombre profesional">
					</div>

					<div class="item">
						<input type="text" name="professional_lastname" placeholder="Apellido profesional">
					</div>

					<div class="item">
						<button type="submit" class="btn">Buscar</button>
					</div>

				</form>

			</div>

			@if (in_array(Auth::user()->permissions, ['administrator', 'admin', 'professional']))
				<div class="popup-report popup-body">

					<div class="hc-title">Reporte de recetas</div>

					<form class="form" action="{{ route('patients.prescriptions.report', ['patient_id' => $patient->id]) }}" method="GET">

						<div class="item">
							<input class="focus" type="date" name="since" placeholder="Fecha desde" min="1979-12-31" max="{{ date('Y-m-d', time() + 180*24*60*60) }}" value="{{ date('Y-m-d') }}">
						</div>

						<div class="item">
							<input type="date" name="to" placeholder="Fecha hasta" min="1979-12-31" max="{{ date('Y-m-d', time() + 180*24*60*60) }}" value="{{ date('Y-m-d') }}">
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

		<div class="hc-title">Recetas del paciente {{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</div>

		<div class="hc-buttons">
			@if (in_array(Auth::user()->permissions, ['professional']) and Auth::user()->professional->profession == 'psiquiatra')
				<div class="hc-button">
					<a href="{{ route('patients.prescriptions.create', ['patient_id' => $patient->id]) }}" class="btn">Agregar</a>
				</div>
			@endif
			<div class="hc-button">
				<button class="btn btn-secondary act-report">Reporte</a>
			</div>
			<div class="hc-button">
				<a href="{{ $back_url }}" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button-right hide-on-celphone">
				@include('partials.pagination', ['items' => $prescriptions, 'route' => route('patients.prescriptions.index', ['patient_id' => $patient->id])])

				<div class="hc-button">
					<button class="btn act-search">Buscar</a>
				</div>
			</div>
		</div>

		<div class="hc-items">

			<div class="hc-item hc-item-title">
				<div class="hc-item-cont">
					<div class="hc-item-data-cont"><!--
						--><div class="hc-item-data admission-date">
							<div class="t">Fecha</div>
						</div><!--
						--><div class="hc-item-data admission-professional">
							<div class="t">Profesional</div>
						</div><!--
					--></div>
				</div>
			</div>

			@foreach($prescriptions as $key => $prescription)
				<div class="hc-item">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont"><!--
							--><div class="hc-item-data admission-date">
								<div class="t">Fecha</div>
								<div class="p">{{ date('d-m-Y', strtotime($prescription->date)) }}</div>
							</div><!--
							--><div class="hc-item-data admission-professional">
								<div class="t">Profesional</div>
								@if ($prescription->professional)
									<div class="p">
                                        <a href="{{ route('professionals.edit', ['professional_id' => $prescription->professional_id]) }}">
											{{ $prescription->professional->firstname }} {{ $prescription->professional->lastname }}
										</a>
									</div>
								@endif
							</div><!--
						--></div>
						<ul class="hc-item-options">
							<li>
								<a href="{{ route('patients.prescriptions.show', ['patient_id' => $patient->id, 'prescription_id' => $prescription->id]) }}">Ver</a>
							</li>
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
