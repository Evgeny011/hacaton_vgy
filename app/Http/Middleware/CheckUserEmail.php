<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckUserEmail
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->email === 'admin@mail.ru') {
            return $next($request);
        }

        return redirect('/');
    }
}

