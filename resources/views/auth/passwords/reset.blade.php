@extends('layouts.app')

@section('content')

<div class="auth">

    <div class="logo">
        <img src="/images/fundacionforo-logo.jpg" alt="Fundación Foro">
    </div>

    <div class="content">

        <form class="form" role="form" method="POST" action="{{ url('/password/email') }}">

            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="item {{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>

            <div class="item {{ $errors->has('password') ? ' has-error' : '' }}">
                 <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña">
            </div>

            <div class="item {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                 <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Contraseña">
            </div>

            <div class="bottom">
                <button type="submit" class="btn btn-100">
                    Cambiar contraseña
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