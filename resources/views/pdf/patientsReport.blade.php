<table cellpadding="0" cellspacing="0" style="widows:185mm;font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
	<tr>
		<td style="width:185mm;text-align:center;">
			<img src="{{ public_path() }}/images/fundacionforo-logo.png" alt="Fundación Foro">
		</td>
	</tr>
	<tr>
		<td style="width:185mm;padding:30px 0;text-align:center;"><strong>Reporte de pacientes del {{ date('d-m-Y', strtotime($since)) }} al {{ date('d-m-Y', strtotime($to)) }}</strong></td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" style="widows:185mm;border-bottom:solid 2px #d9d9d9;font-size:13px;color:#333;line-height:13px;font-weight:300;text-transform:uppercase;font-family:Helvetica;">
	<tr>
		<td style="width:15mm;padding-bottom:10px;">Id</td>
		<td style="width:50mm;padding-bottom:10px;">Nombre</td>
		<td style="width:120mm;padding-bottom:10px;">Consultas</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" style="widows:185mm;font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
	@foreach($patients as $key => $patient)
		<tr>
			<td style="vertical-align:top;width:15mm;border-bottom:solid 1px #d9d9d9;padding:5px 0;">
				{{ $patient->id }}
			</td>
			<td style="vertical-align:top;width:50mm;border-bottom:solid 1px #d9d9d9;padding:5px 0;">
				{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}
			</td>
			<td style="vertical-align:top;width:120mm;border-bottom:solid 1px #d9d9d9;padding:5px 0;">
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
								<br>
								<em>
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
								</em>
								<?php $actualProfessional = $hcDate->professional->id ?>
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
			</td>
		</tr>
	@endforeach
</table>