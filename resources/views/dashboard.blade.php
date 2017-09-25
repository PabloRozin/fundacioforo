@extends('layouts.app')

@section('content')

<div class="hc-list">
	
	<div class="center">
		
		<div class="hc-title">Listado de pacientes</div>

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ route('patients.index') }}" class="btn">Agregar</a>
				<button class="btn btn-secondary act-report">Agregar</a>
			</div>
		</div>

	</div>

</div>

@endsection
