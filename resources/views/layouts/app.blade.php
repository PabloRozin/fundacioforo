<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Title -->
	<title>Historia Clínica</title>

	<!-- Meta Data -->
	<meta name='googleBot' content="NOINDEX, NOFOLLOW" />
	<meta name="robots" content="NOINDEX, NOFOLLOW" />

	<!--[if lt IE 7]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
	<![endif]-->

	<!--[if lt IE 8]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
	<![endif]-->

	<!--[if lt IE 9]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->

	<!--[if lt IE 10]>
		<script src="/js/Placeholders.min.js"></script>
	<![endif]-->

	<!-- Styles -->
	<link href="{{ asset('/js/dropzone/dropzone.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/js/fullcalendar/core/main.css') }}" rel="stylesheet">
	<link href="{{ asset('/js/fullcalendar/daygrid/main.css') }}" rel="stylesheet">
	<link href="{{ asset('/js/fullcalendar/timegrid/main.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/web.css') }}?1" rel="stylesheet">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700" rel="stylesheet">

</head>
<body id="@yield('sectionName')">

	@if (Auth::user())
		@include('partials.header', [])
	@endif

	<div class="wrapper">

	@yield('content')

	</div>

	@include('partials.footer', [])

	<!-- Scripts -->
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
	<script src="{{ asset('/js/jquery.noty.packaged.min.js') }}"></script>
	<script src="{{ asset('/js/dropzone/dropzone.min.js') }}?2"></script>
	<script src="{{ asset('/js/fullcalendar/core/main.js') }}?2"></script>
	<script src="{{ asset('/js/fullcalendar/core/locales/es.js') }}?2"></script>
	<script src="{{ asset('/js/fullcalendar/daygrid/main.js') }}?2"></script>
	<script src="{{ asset('/js/fullcalendar/timegrid/main.js') }}?2"></script>
	<script src="{{ asset('/js/web.js') }}?3"></script>

	@if (session()->has('success'))
		<script>
			$(document).ready(function() { send_msj('success', '{{ session()->get('success') }}') });
		</script>
	@endif

	@if (session()->has('error'))
		<script>
			$(document).ready(function() { send_msj('error', '{{ session()->get('error') }}') });
		</script>
	@endif

	@if (Auth::user())
		@yield('scripts.footer')
	@endif

	@yield('scripts')

</body>
</html>
