@extends('layouts.app')

@section('content')

<div class="hc-list">

	<div class="center">

		<div class="hc-buttons">
			<div class="hc-button">
				<button class="btn calendar-newAppointment">Crear cita</button>
			</div>
			<div class="hc-button">
				<button class="btn btn-secondary calendar-monthView">Mes</button>
			</div>
			<div class="hc-button">
				<button class="btn btn-secondary calendar-weekView">Semana</button>
			</div>
			<div class="hc-button">
				<button class="btn btn-secondary calendar-dayView">Día</button>
			</div>

			<div class="hc-button-right">
				<div class="hc-button">
					<button class="btn btn-secondary calendar-goToToday">Hoy</button>
				</div>
				<div class="hc-button">
					<button class="btn btn-secondary lnr lnr-chevron-left calendar-prev"></button>
				</div>
				<div class="hc-button">
					<button class="btn btn-secondary lnr lnr-chevron-right calendar-next"></button>
				</div>
			</div>
		</div>

		<div class="hc-agenda-calendar">

			<div id="agenda-calendar"></div>

		</div>

	</div>

</div>

@endsection

@section('scripts')

<script>

	document.addEventListener('DOMContentLoaded', function() {

		var calendarEl = document.getElementById('agenda-calendar');

		var calendar = new FullCalendar.Calendar(calendarEl, {
			plugins: [ 'dayGrid', 'timeGrid' ],
			header:
			{
				left: 'title',
				center: '',
				right: ''
			},
			locale: 'es',
			customButtons: {
				newAppointment: {
					text: 'Nueva cita',
					click: function() {

					}
				}
			}
		});

		$('.calendar-monthView').click(function() {
			calendar.changeView('dayGridMonth');
		});

		$('.calendar-weekView').click(function() {
			calendar.changeView('timeGridWeek');
		});

		$('.calendar-dayView').click(function() {
			calendar.changeView('timeGridDay');
		});

		$('.calendar-goToToday').click(function() {
			calendar.changeView('dayGridMonth', '{{ date('Y-m-d') }}');
		});

		$('.calendar-prev').click(function() {
			calendar.prev();
		});

		$('.calendar-next').click(function() {
			calendar.next();
		});

		calendar.render();
	});

</script>

@endsection
