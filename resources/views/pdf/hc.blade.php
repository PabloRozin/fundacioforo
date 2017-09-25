<table cellpadding="0" cellspacing="0" style="widows:185mm;font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
	<tr>
		<td style="width:185mm;text-align:center;">
			<img src="{{ public_path() }}/images/fundacionforo-logo.png" alt="Fundación Foro">
		</td>
	</tr>
	<tr>
		<td style="width:185mm;padding:30px 0;text-align:center;"><strong>Historia Clínica de "{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}"</strong></td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" style="widows:185mm;font-size:16px;color:#333;line-height:22px;font-weight:300;font-family:Helvetica;">
	@foreach($hc_dates as $key => $hc_date)
		<tr>
			<td style="vertical-align:top;width:45mm;border-top:solid 1px #d9d9d9;padding:10px 0;">
				{{ date('d-m-Y', strtotime($hc_date->created_at)) }}<br>
				@if ($hc_date->type != 'otros')
					<span style="font-size:13px;display: inline-block;">{{ $hc_types[$hc_date->type] }}</span>
				@else
					<span style="font-size:13px;display: inline-block;">{{ $hc_date->type_info }}</span>
				@endif
			</td>
			<td style="vertical-align:top;width:140mm;border-top:solid 1px #d9d9d9;padding:10px 0;">
				{{ nl2br($hc_date->detail) }}
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top;width:45mm;padding:0 0 10px 0;">
			</td>
			<td style="vertical-align:top;width:140mm;padding:0 0 10px 0;">
				<br>
				<span style="border-top:solid 1px #d9d9d9; display:block; width: 55mm;padding-top:5px;">				
					{{ $hc_date->professional->firstname }} {{ $hc_date->professional->lastname }} ({{ $hc_date->professional->registration_number }})
				</span>
			</td>
		</tr>
		<?php /*
		@if( ! empty($hc_date['files']))
			@foreach(explode(',', $hc_date['files']) as $file)
				<tr>
					<td colspan="3" style="vertical-align:top;width:185mm;padding:0 0 5px 0;">
						<a target="_blank" href="{{ asset($file) }}" style="text-decoration: none; color:#2ca5ae">
							Ver Archivo
						</a>
					</td>
				</tr>
			@endforeach
		@endif
		*/ ?>
	@endforeach
</table>