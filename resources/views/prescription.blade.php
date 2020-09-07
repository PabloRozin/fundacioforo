@extends('layouts.app')

@section('sectionClass') prescription-section @endsection

@section('content')

<div class="hc-list">

	<div class="center">

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ $back_url }}" class="btn btn-secondary">Volver</a>
			</div>
			<div class="hc-button">
				<button type="submit" class="btn btn-secondary send-to-print">PDF</button>
			</div>
		</div>

		<div class="prescription-container">

			@include('partials.prescription', ['duplicated' => false])

			<div style="page-break-before: always;"></div>
			
			@include('partials.prescription', ['duplicated' => true])

		</div>
	
	</div>

</div>

@endsection
