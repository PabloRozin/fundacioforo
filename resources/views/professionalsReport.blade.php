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

            @foreach($hcDates['professionals'] as $key => $professional)
                <div class="hc-item">
                    <div class="hc-item-cont">
                        <div class="hc-item-data-cont hc"><!--
                            --><div class="hc-item-data report-id">
                                <div class="t">Id</div>
                                <div class="p">{{ $professional['data']->id }}</div>
                            </div><!--
                            --><div class="hc-item-data report-name">
                                <div class="t">Nombre y Apellido</div>
                                <div class="p">{{ $professional['data']->firstname }} {{ $professional['data']->lastname }}</div>
                            </div><!--
                            --><div class="hc-item-data report-visits">
                                <div class="t">Consultas</div>
                                <div class="p">
                                    @foreach ($professional['consultationTypes'] as $typeId => $type)
                                        <strong>{{ $consultationTypes[$typeId] }}</strong> ({{ $type['count'] }})
                                        <br>
                                        @foreach ($type['patients'] as $patientId => $patient)
                                            <a href="{{ route('patients.edit', ['patient_id' => $patient['data']->id]) }}">
                                                {{ $patient['data']->patient_firstname }}
                                                {{ $patient['data']->patient_lastname }}
                                            </a> ({{ count($patient['dates']) }})
                                            <br>
                                            @foreach ($patient['dates'] as $hcDate)
                                                - Fecha: {{ date('d-m-Y', strtotime($hcDate->created_at)) }}
                                                @if ($typeId == 'otros' and ! empty($hcDate->type_info))
                                                    / Info: {{ $hcDate->type_info }}
                                                @endif
                                                <br>
                                            @endforeach
                                        @endforeach
                                        <br>
                                    @endforeach
                                    @if (isset($professional['admissions']))
                                        <strong>Admisiones</strong> ({{ count($professional['admissions']) }})
                                        @foreach ($professional['admissions'] as $key => $admission)
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

        </div>

    </div>

</div>

@endsection
