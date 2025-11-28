<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Log;
use App\Models\User;
use App\Http\Controllers\MainController;


class LoginController extends Controller
{
    public function storeUser (Request $request) {
        $credentials = $request->validate([
            'login' => 'required|string|min:4|max:64',
            'password' => 'required|string',
        ]);
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // return (new MainController)->viewIndex();
            return back()->withInput()->withErrors([
                'login' => trans('auth.failed'),
            ]);
            // throw ValidationException
        }
        $request->session()->regenerate();

        return redirect()->route('index');
    }
}
