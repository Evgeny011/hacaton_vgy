<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Показать форму регистрации
     */
    public function viewRegister()
    {
        return view('registration');
    }

    /**
     * Обработать регистрацию пользователя
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|min:4|unique:users',
            'phone' => 'required|regex:/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/',
            'name' => 'required|min:8',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
            'login.required' => 'Логин обязателен для заполнения',
            'login.min' => 'Логин должен содержать минимум 4 символа',
            'login.unique' => 'Этот логин уже занят',
            'phone.required' => 'Номер телефона обязателен',
            'phone.regex' => 'Номер должен быть в формате +7(XXX)-XXX-XX-XX',
            'name.required' => 'ФИО обязательно для заполнения',
            'name.min' => 'ФИО должно содержать минимум 8 символов',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email',
            'email.unique' => 'Этот email уже занят',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Создание пользователя
        $user = User::create([
            'login' => $request->login,
            'phone' => $request->phone,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Автоматическая авторизация после регистрации
        Auth::login($user);

        return redirect()->route('index')->with('success', 'Регистрация прошла успешно!');
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('log');
    }
}