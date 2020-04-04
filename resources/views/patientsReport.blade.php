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
                <button type="submit" class="btn btn-secondary send-to-print">PDF</button>
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

            @if($hcDates)
                @foreach($hcDates['patients'] as $patient)
                    <div class="hc-item">
                        <div class="hc-item-cont">
                            <div class="hc-item-data-cont hc"><!--
                                --><div class="hc-item-data report-id">
                                    <div class="t">Id</div>
                                    <div class="p">{{ $patient['data']->system_id }}</div>
                                </div><!--
                                --><div class="hc-item-data report-name">
                                    <div class="t">Nombre y Apellido</div>
                                    <div class="p">{{ $patient['data']->patient_firstname }} {{ $patient['data']->patient_lastname }}</div>
                                </div><!--
                                --><div class="hc-item-data report-visits">
                                    <div class="t">Consultas</div>
                                    <div class="p">
                                        @foreach ($patient['consultationTypes'] as $typeId => $type)
                                            <strong>{{ $consultationTypes[$typeId] }}</strong> ({{ $type['count'] }})
                                            <br>
                                            @foreach ($type['professionals'] as $professionalId => $professional)
                                                <a href="{{ route('professionals.edit', ['professional_id' => $professional['data']->id]) }}">
                                                    {{ $professional['data']->firstname }}
                                                    {{ $professional['data']->lastname }}
                                                </a> ({{ count($professional['dates']) }})
                                                <br>
                                                @foreach ($professional['dates'] as $hcDate)
                                                    - Fecha: {{ date('d-m-Y', strtotime($hcDate->created_at)) }}
                                                    @if ($typeId == 'otros' and ! empty($hcDate->type_info))
                                                        / Info: {{ $hcDate->type_info }}
                                                    @endif
                    <div style="display:none">
                        <pre>
                            {{ var_dump($hcDate) }}
                        </pre>
                    </div>
                                                    <br>
                                                @endforeach
                                            @endforeach
                                            <br>
                                        @endforeach
                                        @if (isset($patient['admissions']))
                                            <strong>Admisiones</strong> ({{ count($patient['admissions']) }})
                                            @foreach ($patient['admissions'] as $key => $admission)
                                                <br>
                                                - Fecha: {{ date('d-m-Y', strtotime($admission->created_at)) }}
                                            @endforeach
                                            <br>
                                        @endif
                                    </div>
                                </div><!--
                            --></div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

    </div>

</div>

@endsection
