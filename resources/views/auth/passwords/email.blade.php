@extends('layouts.app')

@section('content')

<div class="auth">

    <div class="logo">
        <img style="width: 100%;height:auto;margin-bottom:30px;" src="/images/evolucion-hcd-logo.jpg" alt="Evolución HCD">
    </div>

    <div class="content">

        <form class="form" role="form" method="POST" action="{{ url('/password/email') }}">

            {{ csrf_field() }}

            <div class="item {{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>

            <div class="bottom">
                <button type="submit" class="btn btn-100">
                    Recuperar
                </button>
                <div class="options">
                    <div class="option">
                        <a href="{{ url('/login') }}">Volver</a>
                    </div>
                </div>
            </div>

        </form>

    </div>

</div>

@endsection


@section('scripts.footer')

@if (session('status'))
    <script type="text/javascript">
        $(document).ready(function() { send_msj('success', '<?php echo $flash_message ?>'); });
    </script>
@endif

@endsection