@extends('layouts.app')

@section('content')

<div class="hc-list">
	
	<div class="center">
		
		<div class="hc-title">Listado de Administradores</div>

		<div class="hc-buttons">
			<div class="hc-button">
				<a href="{{ route('administrators.create') }}" class="btn">Agregar</a>
			</div>

			<div class="hc-button-right">
				@include('partials.pagination', ['items' => $administrators, 'route' => route('administrators.index')])
			</div>
		</div>

		<div class="hc-items">

			<div class="hc-item hc-item-title">
				<div class="hc-item-cont">
					<div class="hc-item-data-cont"><!--
						--><div class="hc-item-data admisor-id">
							<div class="t">Id</div>
						</div><!--
						--><div class="hc-item-data admisor-name">
							<div class="t">Nombre y Apellido</div>
						</div><!--
						--><div class="hc-item-data admisor-email hidden">
							<div class="t">Email</div>
						</div><!--
					--></div>
				</div>
			</div>
			
			@foreach($administrators as $key => $administrator)
				<div class="hc-item">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont"><!--
							--><div class="hc-item-data admisor-id">
								<div class="t">Id</div>
								<div class="p">{{ $administrator->id }}</div>
							</div><!--
							--><div class="hc-item-data admisor-name">
								<div class="t">Nombre y Apellido</div>
								<div class="p">{{ $administrator->name }}</div>
							</div><!--
							--><div class="hc-item-data admisor-email hidden">
								<div class="t">Email</div>
								<div class="p">{{ $administrator->email }}</div>
							</div><!--
						--></div>
						<ul class="hc-item-options">
							<li>
								<a href="{{ route('administrators.edit', ['id' => $administrator->id]) }}">Datos</a>
							</li>
						</ul>
						<div class="hc-item-toggle">
							<span class="lnr lnr-chevron-down"></span>
						</div>
					</div>
				</div>
			@endforeach

		</div>

	</div>

</div>

@endsection
