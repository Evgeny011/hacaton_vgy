@extends('layouts.base')
@section('content')
<div class="login-container">
    <h2 class="mainTitle">Вход</h2>
    <div class="formWrap">
        <form class='form' method='POST' action='{{ route("login") }}'>
            @csrf
            <label class='accFormLabel'>Логин
                <input type="text" name='login' class='{{ $errors->has("login") ? "hasErrorInput" : "" }}' value='{{ old("login") }}'>
                @error('login')
                    <p class='errorMsg'>{{ $message }}</p>
                @enderror
            </label>
            <label class='accFormLabel'>Пароль
                <input type="password" name='password' class='{{ $errors->has("password") ? "hasErrorInput" : "" }}'>
                @error('password')
                    <p class='errorMsg'>{{ $message }}</p>
                @enderror
            </label>
            <div class="rememberWrap">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" class="rememberLabel">Запомнить меня</label>
            </div>
            <button class="btn">Войти</button>
        </form>
    </div>
</div>

<script>
    document.body.classList.add('no-scroll', 'login-page');
</script>
@endsection