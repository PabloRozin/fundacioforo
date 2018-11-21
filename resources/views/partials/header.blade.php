<header id="header">
	
	<div class="center">
		
		<h1 class="logo">
			<a href="{{ route('patients.index') }}">
				@if (Auth::user()->account->logo)
					<img class="big" src="{{ Auth::user()->account->logo }}" alt="Evolución HCD">
					<img class="small" src="{{ Auth::user()->account->logo }}" alt="Evolución HCD">
				@else
					<img class="big" src="/images/evolucion-hcd-logo-interno.jpg" alt="Evolución HCD">
					<img class="small" src="/images/evolucion-hcd-logo-interno.jpg" alt="Evolución HCD">
				@endif
			</a>
		</h1>

		<nav class="menu">
			<ul>
				<li>
					<a href="{{ route('patients.index') }}">Pacientes</a>
				</li>
				<li>
					<a href="{{ route('professionals.index') }}">Profesionales</a>
				</li>
				@if (in_array(Auth::user()->permissions, ['superadmin','admin']))
					<li>
						<a href="{{ route('administrators.index') }}">Administradores</a>
					</li>
				@endif
				@if (in_array(Auth::user()->permissions, ['superadmin','superadmin']))
					<li>
						<a href="{{ route('accounts.index') }}">Cuentas</a>
					</li>
				@endif
				<li class="user">
					<span>Bienvenido 
						@if (in_array(Auth::user()->permissions, ['superadmin','professional']))
							<a class="name" href="{{ route('professionals.edit', ['id' => App\Professional::where('user_id', Auth::user()->id)->first()->id]) }}">{{ Auth::user()->name }}</a>
						@else
							<strong>{{ Auth::user()->name }}</strong>
						@endif
					</span>
					<a class="logout" href="/logout">Salir</a>
				</li>
			</ul>
		</nav>

		<div class="act-search lnr lnr-magnifier"></div>
		<div class="act-menu lnr lnr-menu"></div>

	</div>

</header>