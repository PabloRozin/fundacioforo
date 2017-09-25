<table cellpadding="0" cellspacing="0" style="widows:185mm;font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
	<tr>
		<td style="width:185mm;text-align:center;">
			<img src="{{ public_path() }}/images/fundacionforo-logo.png" alt="Fundación Foro">
		</td>
	</tr>
	<tr>
		<td style="width:185mm;padding:30px 0;text-align:center;"><strong>Reporte de profesionales del {{ date('d-m-Y', strtotime($since)) }} al {{ date('d-m-Y', strtotime($to)) }}</strong></td>
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
	@foreach($professionals as $key => $professional)
		<tr>
			<td style="vertical-align:top;width:15mm;border-bottom:solid 1px #d9d9d9;padding:5px 0;">
				{{ $professional->id }}
			</td>
			<td style="vertical-align:top;width:50mm;border-bottom:solid 1px #d9d9d9;padding:5px 0;">
				{{ $professional->firstname }} {{ $professional->lastname }}
			</td>
			<td style="vertical-align:top;width:120mm;border-bottom:solid 1px #d9d9d9;padding:5px 0;">
				<?php $first = true ?>
				@foreach ($consultationTypes as $key => $consultationType)
					<?php $hcDates = $professional->hcDates()
						->select('hc_dates.*', 'patients.id')
						->join('patients', 'patients.id', '=', 'hc_dates.patient_id')
						->orderBy('patients.patient_firstname','ASC')
						->where('hc_dates.created_at', '>=', $since.' 00:00:00')
						->where('hc_dates.created_at', '<=', $to.' 23:59:59')
						->where('type', $consultationType['id']);
						if (in_array(Auth::user()->permissions, ['administrator']))
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
								<em>
									{{ $hcDate->patient->patient_firstname }} 
									{{ $hcDate->patient->patient_lastname }} 
									(<?php $hcDatesActual = $professional->hcDates()
										->where('hc_dates.created_at', '>=', $since.' 00:00:00')
										->where('hc_dates.created_at', '<=', $to.' 23:59:59')
										->where('patient_id', $hcDate->patient->id)
										->where('type', $consultationType['id']);
										if (in_array(Auth::user()->permissions, ['administrator']))
											$hcDatesActual = $hcDatesActual->where('type', '!=', 'otros');
										echo $hcDatesActual->count();
		 							?>)
								</em>
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
						- Fecha: {{ date('d-m-Y', strtotime($admision->created_at)) }} / Paciente: {{ $admision->patient_firstname }} {{ $admision->patient_lastname }}
					@endforeach
				@endif
			</td>
		</tr>
	@endforeach
</table>