<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;


class AdminController extends Controller
{
    public function viewAdmin () {
        $orders = Order::all();
        return view('admin')->with('orders', $orders);
    }

}
