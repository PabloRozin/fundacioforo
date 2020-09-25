<div class="prescription">

    <div class="professional-information" style="padding-top:30px">
        <div class="name">Dr. {{ $professional->firstname }} {{ $professional->lastname }}</div>
        @if (isset($professional->prescription_cv) and ! empty($professional->prescription_cv))
            <div class="number">{!! strip_tags(nl2br($professional->prescription_cv), '<br>') !!}</div>
        @endif
        <div class="number" style="font-weight:400">M.N. {{ $professional->registration_number }}</div>
    </div>

    <div class="patient-information">
        <div class="item">
            <span class="title">Paciente:</span>
            <span class="text">{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</span>
        </div>
        <div class="item">
            <span class="title">Documento ({{ $patient->patient_document_type }}):</span>
            <span class="text">{{ $patient->patient_document_number }}</span>
        </div>
        <div class="item">
            <span class="title">Obra social ({{ $patient->patient_medical_coverage }}{{ (isset($patient->patient_medical_coverage_plan) and ! empty($patient->patient_medical_coverage_plan)) ? ' ' . $patient->patient_medical_coverage_plan : '' }}):</span>
            <span class="text">{{ $patient->patient_medical_coverage_number }}</span>
        </div>
    </div>

    <div class="text">
        <strong>Rp/</strong>
    </div>

    <div class="medicines">
        @foreach ($prescription->medicines as $medicine)
            <div class="medicine">
                <span>-</span>
                {{ $medicine->name }}
                @if (! is_null($medicine->modality) and ! empty($medicine->modality))
                    <br>{{ $medicine->modality }}
                @endif
            </div>
        @endforeach
    </div>

    @if ($prescription->text and ! empty($prescription->text))
        <div class="text">
            {!! strip_tags(nl2br($prescription->text), '<br>') !!}
        </div>
    @endif

    <div class="text">
        @if ($prescription->prolonged_treatment)
            <strong>Tratamiento prolongado</strong>
        @endif

        @if ($duplicated)
            <strong style="margin-top:10px;display:block">Duplicado</strong>
        @endif
    </div>

    <div class="date">
        @if ( ! $duplicated)
            <div class="input">
                <input id="js-prescription-date-input" type="date" value="{{ date('Y-m-d') }}" onChange="
                    $('#js-prescription-date').empty().append('Buenos Aires, <br>' + new Date($('#js-prescription-date-input').val() + 'T12:00:00').toLocaleDateString('es-AR', { year: 'numeric', month: 'long', day: 'numeric' }));
                    $('#js-prescription-date-duplicated').empty().append('Buenos Aires, <br>' + new Date($('#js-prescription-date-input').val() + 'T12:00:00').toLocaleDateString('es-AR', { year: 'numeric', month: 'long', day: 'numeric' }));
                ">
            </div>
        @endif
        <div class="text" id="js-prescription-date{{ ($duplicated) ? '-duplicated' : '' }}">
             @if ( ! $duplicated)
                <script>
                    $(function(){
                        $('#js-prescription-date').empty().append('Buenos Aires, <br>' + new Date($('#js-prescription-date-input').val() + 'T12:00:00').toLocaleDateString('es-AR', { year: 'numeric', month: 'long', day: 'numeric' }));
                        $('#js-prescription-date-duplicated').empty().append('Buenos Aires, <br>' + new Date($('#js-prescription-date-input').val() + 'T12:00:00').toLocaleDateString('es-AR', { year: 'numeric', month: 'long', day: 'numeric' }));
                    });
                </script>
            @endif
        </div>
    </div>

    <div class="professional-signature">
        <div>
            <strong>Dr. {{ $professional->firstname }} {{ $professional->lastname }}</strong> <br>
            {{ $professions[$professional->profession] }} <br>
            M.N. {{ $professional->registration_number }}
        </div>
    </div>

    <div class="professional-information" style="padding-top:58px;">
        @if (isset($professional->prescription_signature) and ! empty($professional->prescription_signature))
            <div class="number">{!! strip_tags(nl2br($professional->prescription_signature), '<br>') !!}</div>
        @endif
    </div>

</div>