@extends('layouts.app')

@section('sectionClass') prescription-section @endsection

@section('content')

<div class="hc-list">

	<div class="center">

		<div class="hc-buttons">
			@if (in_array(Auth::user()->permissions, ['professional']))
				<div class="hc-button">
					<a href="{{ route('patients.prescriptions.duplicate', ['patient_id' => $patient->id, 'prescription_id' => $prescription->id]) }}" class="btn">Duplicar</a>
				</div>
			@endif
			<div class="hc-button">
				<a href="{{ $back_url }}" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button">
				<button type="submit" class="btn btn-secondary send-to-print">PDF</button>
			</div>
		</div>

		<div class="prescription">

			<div class="logo">
				@if (Auth::user()->account->logo)
					<img class="big" src="{{ Auth::user()->account->logo }}" alt="Evolución HCD">
				@else
					<img class="big" src="/images/evolucion-hcd-logo-interno.jpg" alt="Evolución HCD">
				@endif
			</div>

			<div class="patient-information">
				<div class="item">
					<span class="title">Fecha:</span>
					<span class="text">{{ date('d-m-Y', strtotime($prescription->date)) }}</span>
				</div>
				<div class="item">
					<span class="title">Paciente:</span>
					<span class="text">{{ $patient->patient_fistname }} {{ $patient->patient_lastname }}</span>
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

			<div class="medicines">
				@for($i=1; $i<=20; $i++)
					@if ($prescription->{"medicine-".$i})
						<div class="medicine">
							{{ nl2br($prescription->{"medicine-".$i}) }}
						</div>
					@endif
				@endfor
			</div>

			<div class="professional-signature">
				<div>
					{{ $professional->firstname }} {{ $professional->lastname }} <br>
	                {{ $professions[$professional->profession] }} <br>
	                M.N. {{ $professional->registration_number }}
                </div>
			</div>

		</div>

</div>

@endsection
