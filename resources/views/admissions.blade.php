@extends('layouts.app')

@section('content')

<div class="hc-list">
	
	<div class="center">
		
		<div class="hc-title">Admisiones del paciente {{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</div>

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ route('patients.admissions.create', ['patient_id' => $patient->id]) }}" class="btn">Agregar</a>
			</div>
			<div class="hc-button">
				<a href="{{ route('patients.index') }}" class="btn btn-secondary">Volver</a>
			</div>

			<div class="hc-button-right">
				@include('partials.pagination', ['items' => $admissions, 'route' => route('patients.admissions.index', ['patient_id' => $patient->id])])
			</div>
		</div>

		<div class="hc-items">

			<div class="hc-item hc-item-title">
				<div class="hc-item-cont">
					<div class="hc-item-data-cont"><!--
						--><div class="hc-item-data admission-date">
							<div class="t">Fecha</div>
						</div><!--
					--></div>
				</div>
			</div>
			
			@foreach($admissions as $key => $admission)
				<div class="hc-item">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont"><!--
							--><div class="hc-item-data admission-date">
								<div class="t">Fecha</div>
								<div class="p">{{ $admission->created_at }}</div>
							</div><!--
						--></div>
						<ul class="hc-item-options">
							<li>
								<a href="{{ route('patients.admissions.show', ['patient_id' => $patient->id, 'admission_id' => $admission->id]) }}">Ver</a>
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
