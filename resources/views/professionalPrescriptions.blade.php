@extends('layouts.app')

@section('content')<div class="popup full_size">

	<div class="popup-content vertical_align">

		<div class="popup-cont">

			<div class="popup-close">
				<span class="lnr lnr-cross"></span>
			</div>

			<div class="popup-search popup-body">

				<div class="hc-title">Buscador de recetas</div>

				<form class="form" action="{{ route('professionals.prescriptions.index', ['profesional_id' => $professional->id]) }}" method="GET">

					<div class="item">
						<input type="date" name="date" placeholder="Fecha">
					</div>

					<div class="item">
						<input type="text" name="patient_firstname" placeholder="Nombre paciente">
					</div>

					<div class="item">
						<input type="text" name="patient_lastname" placeholder="Apellido paciente">
					</div>

					<div class="item">
						<button type="submit" class="btn">Buscar</button>
					</div>

				</form>

			</div>

		</div>

	</div>

</div>

<div class="hc-list">

	<div class="center">

		<div class="hc-title">Recetas hechas por el profesional {{ $professional->firstname }} {{ $professional->patient_lastname }}</div>

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ $back_url }}" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button-right hide-on-celphone">
				@include('partials.pagination', ['items' => $prescriptions, 'route' => route('professionals.prescriptions.index', ['professional_id' => $professional->id])])

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
							<div class="t">Paciente</div>
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
								<div class="t">Paciente</div>
								@if ($prescription->patient)
									<div class="p">
                                        <a href="{{ route('patients.edit', ['patient_id' => $prescription->patient_id]) }}">
											{{ $prescription->patient->patient_firstname }} {{ $prescription->patient->patient_lastname }}
										</a>
									</div>
								@endif
							</div><!--
						--></div>
						<ul class="hc-item-options">
							<li>
								<a href="{{ route('professionals.prescriptions.show', ['patient_id' => $professional->id, 'prescription_id' => $prescription->id]) }}">Ver</a>
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
