<table cellpadding="0" cellspacing="0" style="font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
	<tr>
		<td style="width:185mm;text-align:center;">
			<div style="padding-bottom:30px;"><img src="{{ public_path() }}/images/fundacionforo-logo.png" alt="Fundación Foro"></div>
			<div><strong>Reporte de profesionales del {{ date('d-m-Y', strtotime($since)) }} al {{ date('d-m-Y', strtotime($to)) }}</strong></div>
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" style="padding-top:30px;font-size:13px;color:#333;line-height:13px;font-weight:300;text-transform:uppercase;font-family:Helvetica;">
	<tr>
		<td style="width:15mm;">Id</td>
		<td style="width:50mm;">Nombre</td>
		<td style="width:120mm;">Consultas</td>
	</tr>
</table>

<?php 
	$actualProfessional = 0;
?>
@foreach($professionals as $key => $professional)
	@if ($actualProfessional != $professional->id)
		<table cellpadding="0" cellspacing="0" style="font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
			<tr>
				<td style="padding:15px 0 0 0">
				</td>
				<td style="padding:15px 0 0 0">
				</td>
				<td style="padding:15px 0 0 0">
				</td>
			</tr>
		</table>
	@endif
	<?php 
		if ($actualProfessional != $professional->id) 
		{
			$firstProfessional = true;
		}
		$actualProfessional = $professional->id;
	?>
	@foreach ($consultationTypes as $key => $consultationType)
		<?php 
			$firstConsultation = true;
		?>
		<?php if ($firstProfessional): ?>
			<table cellpadding="0" cellspacing="0" style="font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
				<tr>
					<td style="vertical-align:top;width:15mm;margin-top:5px;border-top:solid 1px #d9d9d9;padding:15px 0 10px 0;">
						{{ $professional->id }}
					</td>
					<td style="vertical-align:top;width:50mm;margin-top:5px;border-top:solid 1px #d9d9d9;padding:15px 0 10px 0;">
						{{ $professional->firstname }} {{ $professional->lastname }}
					</td>
					<td style="vertical-align:top;width:120mm;margin-top:5px;border-top:solid 1px #d9d9d9;padding:15px 0 10px 0;">
					</td>
				</tr>
			</table>
		<?php endif ?>
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
			<table cellpadding="0" cellspacing="0" style="font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
				<tr>
					<td style="vertical-align:top;width:15mm;">
					</td>
					<td style="vertical-align:top;width:50mm;">
					</td>
					<td style="vertical-align:top;width:120mm;">
						<?php 
							$actualpatient = 0;
							$firstConsultation = false;
						?>
						<strong>{{ $consultationType['value'] }}</strong>
						({{ $hcDates->count() }})
					</td>
				</tr>
			</table>
			@foreach ($hcDates->get() as $key => $hcDate)
				<table cellpadding="0" cellspacing="0" style="font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
					<tr>
						<td style="vertical-align:top;width:15mm;">
						</td>
						<td style="vertical-align:top;width:50mm;">
						</td>
						<td style="vertical-align:top;width:120mm;">
							@if ($actualpatient != $hcDate->patient->id)
								<em>
									{{ $hcDate->patient->patient_firstname }} 
									{{ $hcDate->patient->patient_lastname }} 
									<?php $hcDatesActual = $professional->hcDates()
										->where('hc_dates.created_at', '>=', $since.' 00:00:00')
										->where('hc_dates.created_at', '<=', $to.' 23:59:59')
										->where('patient_id', $hcDate->patient->id)
										->where('type', $consultationType['id']);
										if (in_array(Auth::user()->permissions, ['administrator']))
											$hcDatesActual = $hcDatesActual->where('type', '!=', 'otros');
									?>
									({{ $hcDatesActual->count() }})
								</em>
								<?php $actualpatient = $hcDate->patient->id ?>
							@endif
							<div>
								- Fecha: {{ date('d-m-Y', strtotime($hcDate->created_at)) }}
								@if ($consultationType['id'] == 'otros' and ! empty($hcDate->type_info))
									/ Info: {{ $hcDate->type_info }}
								@endif
							</div>
						</td>
					</tr>
				</table>
			@endforeach
		@endif
		<?php $firstProfessional = false; ?>
	@endforeach
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
	<table cellpadding="0" cellspacing="0" style="font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
		<tr>
			<td style="vertical-align:top;width:15mm;padding:0 0 5px 0;">
			</td>
			<td style="vertical-align:top;width:50mm;padding:0 0 5px 0;">
			</td>
			<td style="vertical-align:top;width:120mm;padding:0 0 5px 0;">
				<strong>Admisiones</strong>
				({{ $admisions->count() }})
			</td>
		</tr>
	</table>
	@foreach ($admisions->get() as $key => $admision)
		<table cellpadding="0" cellspacing="0" style="font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
			<tr>
				<td style="vertical-align:top;width:15mm;padding:0 0 5px 0;">
				</td>
				<td style="vertical-align:top;width:50mm;padding:0 0 5px 0;">
				</td>
				<td style="vertical-align:top;width:120mm;padding:0 0 5px 0;">
					<div>
						- Fecha: {{ date('d-m-Y', strtotime($admision->created_at)) }} / Paciente: {{ $admision->patient_firstname }} {{ $admision->patient_lastname }}
					</div>
				</td>
			</tr>
		</table>
	@endforeach
@endif