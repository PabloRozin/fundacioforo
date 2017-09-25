@extends('layouts.app')

@section('sectionName', 'auth')

@section('content')

<div class="auth">

    <div class="logo">
        <img src="/images/fundacionforo-logo.jpg" alt="Fundación Foro">
    </div>

    <div class="content">

        <form class="form" role="form" method="POST" action="{{ url('/login') }}">

            {{ csrf_field() }}

            <div class="item {{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
            </div>

            <div class="item {{ $errors->has('password') ? ' has-error' : '' }}">
                 <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña">
            </div>

            <div class="item">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>

            <div class="bottom">
                <button type="submit" class="btn btn-100">
                    Ingresar
                </button>
                <div class="options">
                    <div class="option">
                        <a href="{{ url('/password/reset') }}">Recuperar contraseña</a>
                    </div>
                </div>
            </div>

        </form>

    </div>

</div>

@endsection
