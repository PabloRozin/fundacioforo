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
							--><div class="hc-item-data hc-detail">
								<div class="t">Medicamentos</div>
								<ul class="p">
									@foreach ($prescription->medicines as $medicine)
										<li>- {{ $medicine->name }}</li>
									@endforeach
								</ul>
							</div><!--
						--></div>
						<ul class="hc-item-options">
							<li>
								<a href="{{ route('patients.prescriptions.show', ['patient_id' => $prescription->patient->id, 'prescription_id' => $prescription->id]) }}">Imprimir</a>
							</li>
							<li>
								<a href="{{ route('patients.prescriptions.edit', ['patient_id' => $prescription->patient->id, 'prescription_id' => $prescription->id]) }}">Editar</a>
							</li>
							@if (in_array(Auth::user()->permissions, ['professional']))
								<li>
									<a href="{{ route('patients.prescriptions.duplicate', ['patient_id' => $prescription->patient->id, 'prescription_id' => $prescription->id]) }}">Duplicar</a>
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
