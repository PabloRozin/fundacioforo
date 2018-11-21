@extends('layouts.app')

@section('content')

<div class="hc-list">
	
	<div class="center">
		
		<div class="hc-title">Reporte de profesionales del {{ date('d-m-Y', strtotime($since)) }} al {{ date('d-m-Y', strtotime($to)) }}</div>

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ $back_url }}" type="submit" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button">
				<a href="{{ $pdf_url }}" type="submit" target="_blank" class="btn btn-secondary">PDF</a>
			</div>
		</div>

		<div class="hc-items">

			<div class="hc-item hc-item-title">
				<div class="hc-item-cont">
					<div class="hc-item-data-cont hc"><!--
						--><div class="hc-item-data report-id">
							<div class="t">Id</div>
						</div><!--
						--><div class="hc-item-data report-name">
							<div class="t">Nombre y Apellido</div>
						</div><!--
						--><div class="hc-item-data report-visits">
							<div class="t">Consultas</div>
						</div><!--
					--></div>
				</div>
			</div>

			@foreach($professionals as $key => $professional)
				<div class="hc-item">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont hc"><!--
							--><div class="hc-item-data report-id">
								<div class="t">Id</div>
								<div class="p">{{ $professional->id }}</div>
							</div><!--
							--><div class="hc-item-data report-name">
								<div class="t">Nombre y Apellido</div>
								<div class="p">{{ $professional->firstname }} {{ $professional->lastname }}</div>
							</div><!--
							--><div class="hc-item-data report-visits">
								<div class="t">Consultas</div>
								<div class="p">
									<?php $first = true ?>
									@foreach ($consultationTypes as $key => $consultationType)
										<?php $hcDates = $professional->hcDates()
											->select('hc_dates.*', 'patients.id')
											->join('patients', 'patients.id', '=', 'hc_dates.patient_id')
											->orderBy('patients.patient_firstname','ASC')
											->dateWhere('hc_dates.created_at', '>=', $since.' 00:00:00')
											->dateWhere('hc_dates.created_at', '<=', $to.' 23:59:59')
											->where('type', $consultationType['id']);
											if (in_array(Auth::user()->permissions, ['superadmin','administrator']))
												$hcDates = $hcDates->where('type', '!=', 'otros');
											?>
										@if ($hcDates->count())
											@if ( ! $first)
												<br><br>
											@endif
											<?php 
												$actualpatient = 0;
												$first = false;
											?>
											<strong>{{ $consultationType['value'] }}</strong>
											({{ $hcDates->count() }})
											@foreach ($hcDates->get() as $key => $hcDate)
												@if ($actualpatient != $hcDate->patient->id)
													<br>
													<a href="{{ route('patients.edit', ['patient_id' => $hcDate->patient->id]) }}">
														{{ $hcDate->patient->patient_firstname }} 
														{{ $hcDate->patient->patient_lastname }} 
														(<?php $hcDatesActual = $professional->hcDates()
															->dateWhere('hc_dates.created_at', '>=', $since.' 00:00:00')
															->dateWhere('hc_dates.created_at', '<=', $to.' 23:59:59')
															->where('patient_id', $hcDate->patient->id)
															->where('type', $consultationType['id']);
															if (in_array(Auth::user()->permissions, ['superadmin','administrator']))
																$hcDatesActual = $hcDatesActual->where('type', '!=', 'otros');
															echo $hcDatesActual->count();
							 							?>)
													</a>
													<?php $actualpatient = $hcDate->patient->id ?>
												@endif
												<br>
												- Fecha: {{ date('d-m-Y', strtotime($hcDate->created_at)) }}
												@if ($consultationType['id'] == 'otros' and ! empty($hcDate->type_info))
													/ Info: {{ $hcDate->type_info }}
												@endif
											@endforeach
										@endif
									@endforeach
									<?php $admisions = $professional->admissions()
										->select('patient_admisions.*', 'professionals.id', 'professionals.user_id', 'patients.patient_firstname as patient_firstname', 'patients.patient_lastname as patient_lastname')
										->join('professionals', 'professionals.id', '=', 'patient_admisions.professional_id')
										->join('patients', 'patients.id', '=', 'patient_admisions.patient_id')
										->orderBy('professionals.firstname','ASC')
										->orderBy('patient_admisions.created_at','ASC')
										->dateWhere('patient_admisions.created_at', '>=', $since.' 00:00:00')
										->dateWhere('patient_admisions.created_at', '<=', $to.' 23:59:59');
		 							?>
									@if (in_array(Auth::user()->permissions, ['superadmin','professional']))
										<?php $admisions = $admisions->where('professionals.user_id', '=', Auth::user()->id); ?>
									@endif
									@if ($admisions->count())
										<br><br>
			 							<strong>Admisiones</strong>
			 							({{ $admisions->count() }})
			 							@foreach ($admisions->get() as $key => $admision)
											<br>
											- Fecha: {{ date('d-m-Y', strtotime($admision->created_at)) }} / Paciente: {{ $admision->patient_firstname }} {{ $admision->patient_lastname }}
			 							@endforeach
									@endif
								</div>
							</div><!--
						--></div>
					</div>
				</div>
			@endforeach

		</div>

	</div>

</div>

@endsection
