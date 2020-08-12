<table cellpadding="0" cellspacing="0" style="widows:185mm;font-size:16px;color:#333;line-height:20px;font-weight:300;font-family:Helvetica;">
    <tr>
        <td style="width:185mm;text-align:center;">
            @if(Auth::user()->account->id == 1)
                <div style="padding-bottom:30px;">
                    <img src="{{ public_path('/images/evolucion-hcd-logo-75.jpg') }}">
                </div>
            @else
                <div style="padding-bottom:30px;">
                    <img src="{{ public_path('/images/evolucion-hcd-logo-interno.jpg') }}">
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td style="width:185mm;padding:30px 0;text-align:center;"><strong>Historia Clínica de "{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}"</strong></td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0" style="widows:185mm;font-size:16px;color:#333;line-height:22px;font-weight:300;font-family:Helvetica;page-break-inside: auto;">
    @foreach($hc_dates as $key => $hc_date)
        <tr>
            <td style="vertical-align:top;width:45mm;border-top:solid 1px #d9d9d9;padding:10px 10px;">
            </td>
            <td style="vertical-align:top;width:140mm;border-top:solid 1px #d9d9d9;padding:10px 0;">
            </td>
        </tr>
        <?php
            $texts = strip_tags($hc_date->detail, '<br>');
            $texts = html_entity_decode($texts);
            $texts = nl2br($texts);
            $texts = str_replace('<br />', '<br>', $texts);
            $texts = explode('<br>', $texts);

            $first = true;
        ?>
        @foreach ($texts as $text)
            <tr>
                <td style="vertical-align:top;width:45mm;padding:0;">
                    @if ($first)
                        {{ date('d-m-Y', strtotime($hc_date->created_at)) }}<br>
                        @if ($hc_date->type != 'otros' and isset($hc_types[$hc_date->type]))
                            <span style="font-size:13px;display: inline-block;line-height:16px;padding-right:10px">{{ $hc_types[$hc_date->type] }}</span>
                        @elseif ($hc_date->type == 'otros')
                            <span style="font-size:13px;display: inline-block;line-height:16px;padding-right:10px">{{ $hc_date->type_info }}</span>
                        @endif
                        <?php
                            $first = false;
                        ?>
                    @endif
                </td>
                <td style="vertical-align:top;width:140mm;padding:0 0 10px 0;">
                    {{ $text }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td style="vertical-align:top;width:45mm;padding:0 0 20px 0;">
            </td>
            <td style="vertical-align:top;width:140mm;padding:0 0 20px 0;">
                <span style="border-top:solid 1px #d9d9d9; display:block; width: 55mm;padding-top:5px;font-size:13px;display: inline-block;line-height:16px;">
                    {{ $hc_date->professional->firstname }} {{ $hc_date->professional->lastname }} <br>
                    {{ $professions[$hc_date->professional->profession] }} <br>
                    M.N. {{ $hc_date->professional->registration_number }}
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
