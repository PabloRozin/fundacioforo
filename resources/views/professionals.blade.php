@extends('layouts.app')

@section('content')

<div class="popup full_size">

	<div class="popup-content vertical_align">

		<div class="popup-cont">

			<div class="popup-close">
				<span class="lnr lnr-cross"></span>
			</div>

			<div class="popup-search popup-body">

				<div class="hc-title">Buscador de profesionales</div>

				<form class="form" action="{{ route('professionals.index') }}" method="GET">

					<div class="item">
						<input class="focus" type="text" name="id" placeholder="ID">
					</div>

					<div class="item">
						<input type="text" name="document_number" placeholder="Nº de documento">
					</div>

					<div class="item">
						<input type="text" name="registration_number" placeholder="Matrícula">
					</div>

					<div class="item">
						<input type="text" name="firstname" placeholder="Nombre">
					</div>

					<div class="item">
						<input type="text" name="lastname" placeholder="Apellido">
					</div>

					<div class="item">
						<input type="email" name="email" placeholder="Email">
					</div>

					<div class="item">
						<input type="text" name="district" placeholder="Barrio">
					</div>

					<div class="item">
						<select name="profession">
							<option value="">Especialidad</option>
							<option value="psicología">Psicología</option>
							<option value="médico_psiquiatra">Psiquiatra</option>
							<option value="psicopedagogia">Psicopedagogía</option>
							<option value="at">AT</option>
							<option value="otros">Otros</option>
						</select>
					</div>

					<div class="item">
						<button type="submit" class="btn">Buscar</button>
					</div>

				</form>

			</div>

			@if (in_array(Auth::user()->permissions, ['administrator', 'admin', 'professional']))
				<div class="popup-report popup-body">

					<div class="hc-title">Reporte general de profesionales</div>

					<form class="form" action="{{ route('professionals.report') }}" method="GET">

						<div class="item">
							<input class="focus" type="date" name="since" placeholder="Fecha desde" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>

						<div class="item">
							<input type="date" name="to" placeholder="Fecha hasta" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>

						<div class="item">
							<button type="submit" class="btn">Buscar</button>
						</div>

					</form>

				</div>

				<div class="popup-report-one popup-body">

					<div class="hc-title">Reporte de profesional <span class="report-data-name"></span></div>

					<form class="form" action="{{ route('professionals.report') }}" method="GET">

						<div class="item">
							<input class="focus" type="date" name="since" placeholder="Fecha desde" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>

						<div class="item">
							<input type="date" name="to" placeholder="Fecha hasta" min="1979-12-31" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
						</div>

						<div class="item">
							<button type="submit" class="btn">Buscar</button>
						</div>

					</form>

				</div>
			@endif

		</div>

	</div>

</div>

<div class="hc-list">

	<div class="center">

		<div class="hc-title">Listado de profesionales</div>

		<div class="hc-buttons">
			@if (in_array(Auth::user()->permissions, ['admin']))
				<div class="hc-button">
					<a href="{{ route('professionals.create') }}" class="btn">Agregar</a>
				</div>
			@endif
			@if (in_array(Auth::user()->permissions, ['administrator', 'admin']))
				<div class="hc-button">
					<button class="btn btn-secondary act-report">Reporte</a>
				</div>
			@endif
			@if (isset($back_url))
				<div class="hc-button">
					<a href="{{ $back_url }}" type="button" class="btn btn-secondary">Volver</a>
				</div>
			@endif
			<div class="hc-button-right hide-on-celphone">
				@include('partials.pagination', ['items' => $professionals, 'route' => route('professionals.index')])

				<div class="hc-button">
					<button class="btn act-search">Buscar</a>
				</div>
			</div>
		</div>

		<div class="hc-items">

			<div class="hc-item hc-item-title">
				<div class="hc-item-cont">
					<div class="hc-item-data-cont"><!--
						--><div class="hc-item-data id">
							<div class="t">Id</div>
						</div><!--
						--><div class="hc-item-data name">
							<div class="t">Nombre y Apellido</div>
						</div><!--
						--><div class="hc-item-data phone hidden">
							<div class="t">Teléfono</div>
						</div><!--
						--><div class="hc-item-data email hidden">
							<div class="t">Email</div>
						</div><!--
					--></div>
				</div>
			</div>

			@foreach($professionals as $key => $professional)
				<div class="hc-item">
					<div class="hc-item-cont">
						<div class="hc-item-data-cont"><!--
							--><div class="hc-item-data id">
								<div class="t">Id</div>
								<div class="p">{{ $professional->id }}</div>
							</div><!--
							--><div class="hc-item-data name">
								<div class="t">Nombre y Apellido</div>
								<div class="p">{{ $professional->firstname }} {{ $professional->lastname }}</div>
							</div><!--
							--><div class="hc-item-data phone hidden">
								<div class="t">Teléfono</div>
								<div class="p">
									@if ($professional->phone_1)
										{{ $professional->phone_1 }}
									@endif
									@if ($professional->phone_2)
										<br>{{ $professional->phone_2 }}
									@endif
									@if ($professional->phone_3)
										<br>{{ $professional->phone_3 }}
									@endif
								</div>
							</div><!--
							--><div class="hc-item-data email hidden">
								<div class="t">Email</div>
								<div class="p">{{ $professional->email }}</div>
							</div><!--
						--></div>
						<ul class="hc-item-options">
							<li>
								@if ((in_array(Auth::user()->permissions, ['professional']) and $professional->user_id == Auth::user()->id) or in_array(Auth::user()->permissions, ['admin']))
									<a href="{{ route('professionals.edit', ['id' => $professional['id']]) }}">Datos</a>
								@else
									<a href="{{ route('professionals.show', ['id' => $professional['id']]) }}">Datos</a>
								@endif
							</li>
							@if (Auth::user()->account->prescriptions)
								<li>
									<a href="{{ route('professionals.prescriptions.index', ['id' => $professional['id']]) }}">Recetas</a>
								</li>
							@endif
							@if (in_array(Auth::user()->permissions, ['administrator', 'admin']))
								<li>
									<span class="act-report-one" data-id="{{ $professional['id'] }}" data-name="{{ $professional['firstname'] }} {{ $professional['lastname'] }}">Reporte</span>
								</li>
							@endif
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
