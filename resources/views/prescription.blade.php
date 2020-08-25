@extends('layouts.app')

@section('sectionClass') prescription-section @endsection

@section('content')

<div class="hc-list">

	<div class="center">

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ $back_url }}" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button">
				<button type="submit" class="btn btn-secondary send-to-print">PDF</button>
			</div>
		</div>

		<div class="prescription-container">
			
			<div class="prescription">

				<div class="professional-information">
					<div class="name">Dr. {{ $professional->firstname }} {{ $professional->lastname }}</div>
					<div class="number">M.N. {{ $professional->registration_number }}</div>
				</div>

				<div class="patient-information">
					<div class="item">
						<span class="title">Paciente:</span>
						<span class="text">{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</span>
					</div>
					<div class="item">
						<span class="title">Documento ({{ $patient->patient_document_type }}):</span>
						<span class="text">{{ $patient->patient_document_number }}</span>
					</div>
					<div class="item">
						<span class="title">Obra social ({{ $patient->patient_medical_coverage }}{{ (isset($patient->patient_medical_coverage_plan) and ! empty($patient->patient_medical_coverage_plan)) ? ' ' . $patient->patient_medical_coverage_plan : '' }}):</span>
						<span class="text">{{ $patient->patient_medical_coverage_number }}</span>
					</div>
				</div>

				<div class="text">
					<strong>Rp/</strong>
				</div>

				<div class="medicines">
					@foreach ($prescription->medicines as $medicine)
						<div class="medicine">
							<span>-</span>
							{{ $medicine->name }}
							@if (! is_null($medicine->modality) and ! empty($medicine->modality))
								<br>{{ $medicine->modality }}
							@endif
						</div>
					@endforeach
				</div>

				@if ($prescription->text and ! empty($prescription->text))
					<div class="text">
						{!! nl2br($prescription->text) !!}
					</div>
				@endif

				@if ($prescription->prolonged_treatment)
					<div class="text">
						<strong>Tratamiento prolongado</strong>
					</div>
				@endif

				<div class="date">
					<div class="input">
						<input id="js-prescription-date-input" type="date" value="{{ date('Y-m-d') }}" onChange="
							$('#js-prescription-date').empty().append('Buenos Aires, <br>' + new Date($('#js-prescription-date-input').val() + 'T12:00:00').toLocaleDateString('es-AR', { year: 'numeric', month: 'long', day: 'numeric' }));
						">
					</div>
					<div class="text" id="js-prescription-date">
						<script>
							$(function(){
								$('#js-prescription-date').empty().append('Buenos Aires, <br>' + new Date($('#js-prescription-date-input').val() + 'T12:00:00').toLocaleDateString('es-AR', { year: 'numeric', month: 'long', day: 'numeric' }));
							});
						</script>
					</div>
				</div>

				<div class="professional-signature">
					<div>
						<strong>Dr. {{ $professional->firstname }} {{ $professional->lastname }}</strong> <br>
						{{ $professions[$professional->profession] }} <br>
						M.N. {{ $professional->registration_number }}
					</div>
				</div>

			</div>
			
		</div>

</div>

@endsection
