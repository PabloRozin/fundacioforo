@extends('layouts.app')

@section('content')

<div class="hc-list">

    <div class="center">

        <div class="hc-title">Reporte de medicamentos del paciente {{ $patient->patient_firstname }} {{ $patient->patient_lastname }} del {{ date('d-m-Y', strtotime($since)) }} al {{ date('d-m-Y', strtotime($to)) }}</div>

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
                        --><div class="hc-item-data report-name">
                            <div class="t">Profesional</div>
                        </div><!--
                        --><div class="hc-item-data report-visits">
                            <div class="t">Medicamentos</div>
                        </div><!--
                    --></div>
                </div>
            </div>

            @if($professionals)
                @foreach($professionals as $professional)
                    <div class="hc-item">
                        <div class="hc-item-cont">
                            <div class="hc-item-data-cont hc"><!--
                                --><div class="hc-item-data report-name">
                                    <div class="t">Profesional</div>
                                    <div class="p">
                                        <a href="{{ route('professionals.edit', ['professional_id' => $professional['data']->professional_id]) }}">
                                            {{ $professional['data']->firstname }} {{ $professional['data']->lastname }}
                                        </a>
                                    </div>
                                </div><!--
                                --><div class="hc-item-data report-medicines">
                                    <div class="t">Medicamentos</div>
                                    <div class="p">
                                        <?php foreach ($professional['prescriptions'] as $key => $prescription): ?>
                                            @if($key != 0)
                                                <br>
                                            @endif
                                            <strong>{{ date('d-m-Y', strtotime($prescription->date)) }}</strong>
                                            @foreach ($prescription->medicines as $medicine)
                                                <br>
                                                - {{ $medicine->name }}
                                            @endforeach
                                            <br>
                                        <?php endforeach ?>
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
