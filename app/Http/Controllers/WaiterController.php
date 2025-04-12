<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class WaiterController extends Controller
{
    public function index()
    {
        // Проверяем права при помощи политики
        $this->authorize('accessWaiterHome', Auth::user());

        return view('waiter.home');
    }
}
