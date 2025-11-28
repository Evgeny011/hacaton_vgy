@extends('layouts.base')
@section('content')
    <h2 class="mainTitle">Регистрация</h2>
    <div class="formWrap">
        <form method='POST' action='{{ route("storeUser") }}' class='form'>
            @csrf
            <label class='accFormLabel'>Логин
                <input type="text" name='login' class='{{ $errors->has("login") ? "hasErrorInput" : "" }}' placeholder='мин. 4 символа' value='{{ old("login") }}'>
                @error('login')
                    <p class='errorMsg'>Логин должен содержать минимум 4 символа</p>
                @enderror
            </label>
            <label class='accFormLabel'>Номер телефона
                <input type="text" name='phone' class='{{ $errors->has("phone") ? "hasErrorInput" : "" }}' placeholder='+7(XXX)-XXX-XX-XX' value='{{ old("phone") }}'>
                @error('phone')
                    <p class='errorMsg'>Номер должен быть в формате +7(XXX)-XXX-XX-XX</p>
                @enderror
            </label>
            <label class='accFormLabel'>ФИО
                <input type="text" name='name' class='{{ $errors->has("name") ? "hasErrorInput" : "" }}' placeholder='мин. 8 символов' value='{{ old("name") }}'>
                @error('name')
                    <p class='errorMsg'>{{ $message }}</p>
                @enderror
            </label>
            <label class='accFormLabel'>Email
                <input type="email" name='email' class='{{ $errors->has("email") ? "hasErrorInput" : "" }}' value='{{ old("email") }}'>
                @error('email')
                    <p class='errorMsg'>{{ $message }}</p>
                @enderror
            </label>
            <label class='accFormLabel'>Пароль
                <input type="password" name='password' class='{{ $errors->has("password") ? "hasErrorInput" : "" }}' placeholder='мин. 6 символов'>
                @error('password')
                    <p class='errorMsg'>Пароль должен содержать минимум 6 символов</p>
                @enderror
            </label>
            <label class='accFormLabel'>Повторите пароль
                <input type="password" name='password_confirmation' class='{{ $errors->has("password") ? "hasErrorInput" : "" }}' placeholder='мин. 6 символов'>
            </label>
            <button type='submit' class="btn">Зарегистрироваться</button>
        </form>
        <ul>
        </ul>
    </div>
@endsection