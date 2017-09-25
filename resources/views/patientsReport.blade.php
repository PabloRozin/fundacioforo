@extends('layouts.app')

@section('content')

<div class="hc-list">
	
	<div class="center">
		
		<div class="hc-title">Reporte de pacientes del {{ date('d-m-Y', strtotime($since)) }} al {{ date('d-m-Y', strtotime($to)) }}</div>

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ $back_url }}" type="submit" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button"p>
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

			@foreach($patients as $key => $patient)
				<div class="hc-item">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont hc"><!--
							--><div class="hc-item-data report-id">
								<div class="t">Id</div>
								<div class="p">{{ $patient->id }}</div>
							</div><!--
							--><div class="hc-item-data report-name">
								<div class="t">Nombre y Apellido</div>
								<div class="p">{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</div>
							</div><!--
							--><div class="hc-item-data report-visits">
								<div class="t">Consultas</div>
								<div class="p">
									<?php $first = true ?>
									@foreach ($consultationTypes as $key => $consultationType)
										<?php $hcDates = $patient->hcDates()
											->select('hc_dates.*', 'professionals.id', 'professionals.user_id')
											->join('professionals', 'professionals.id', '=', 'hc_dates.professional_id')
											->orderBy('professionals.firstname','ASC')
											->where('hc_dates.created_at', '>=', $since.' 00:00:00')
											->where('hc_dates.created_at', '<=', $to.' 23:59:59')
											->where('type', $consultationType['id']);
											if (in_array(Auth::user()->permissions, ['professional']))
												$hcDates = $hcDates->where('professionals.user_id', '=', Auth::user()->id);
											if (in_array(Auth::user()->permissions, ['administrator']))
												$hcDates = $hcDates->where('type', '!=', 'otros');
			 							?>
										@if ($hcDates->count())
											@if ( ! $first)
												<br><br>
											@endif
											<?php 
												$actualProfessional = 0;
												$first = false;
											?>
											<strong>{{ $consultationType['value'] }}</strong>
											({{ $hcDates->count() }})
											@foreach ($hcDates->get() as $key => $hcDate)
												@if ( ! in_array(Auth::user()->permissions, ['professional']) and $actualProfessional != $hcDate->professional->id)
													<?php $actualProfessional = $hcDate->professional->id; ?>
													<br>
													<a href="{{ route('professionals.edit', ['patient_id' => $hcDate->professional->id]) }}">
														{{ $hcDate->professional->firstname }} 
														{{ $hcDate->professional->lastname }} 
														(<?php $hcDatesActual = $patient->hcDates()
															->where('hc_dates.created_at', '>=', $since.' 00:00:00')
															->where('hc_dates.created_at', '<=', $to.' 23:59:59')
															->where('professional_id', $hcDate->professional->id)
															->where('type', $consultationType['id']);
															if (in_array(Auth::user()->permissions, ['administrator']))
																$hcDatesActual = $hcDatesActual->where('type', '!=', 'otros');
															echo $hcDatesActual->count();
							 							?>)
													</a>
												@endif
												<br>
												- Fecha: {{ date('d-m-Y', strtotime($hcDate->created_at)) }}
												@if ($consultationType['id'] == 'otros' and ! empty($hcDate->type_info))
													/ Info: {{ $hcDate->type_info }}
												@endif
											@endforeach
										@endif
									@endforeach
									<?php $admisions = $patient->admissions()
										->select('patient_admisions.*', 'professionals.id', 'professionals.user_id')
										->join('professionals', 'professionals.id', '=', 'patient_admisions.professional_id')
										->orderBy('professionals.firstname','ASC')
										->orderBy('patient_admisions.created_at','ASC')
										->where('patient_admisions.created_at', '>=', $since.' 00:00:00')
										->where('patient_admisions.created_at', '<=', $to.' 23:59:59');
		 							?>
									@if (in_array(Auth::user()->permissions, ['professional']))
										<?php $admisions = $admisions->where('professionals.user_id', '=', Auth::user()->id); ?>
									@endif
									@if ($admisions->count())
										<br><br>
			 							<strong>Admisiones</strong>
			 							({{ $admisions->count() }})
			 							@foreach ($admisions->get() as $key => $admision)
											<br>
											- Fecha: {{ date('d-m-Y', strtotime($admision->created_at)) }}
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
