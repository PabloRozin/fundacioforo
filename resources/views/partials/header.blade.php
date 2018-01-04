<header id="header">
	
	<div class="center">
		
		<h1 class="logo">
			<a href="{{ route('patients.index') }}">
				<img class="big" src="{{ asset('/files'.Auth::user()->account->logo) }}" alt="Fundacion Foro">
				<img class="small" src="{{ asset('/files'.Auth::user()->account->logo) }}" alt="Fundacion Foro">
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
				@if (in_array(Auth::user()->permissions, ['admin']))
					<li>
						<a href="{{ route('administrators.index') }}">Administradores</a>
					</li>
				@endif
				@if (in_array(Auth::user()->permissions, ['superadmin']))
					<li>
						<a href="{{ route('accounts.index') }}">Cuentas</a>
					</li>
				@endif
				<li class="user">
					<span>Bienvenido 
						@if (in_array(Auth::user()->permissions, ['professional']))
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