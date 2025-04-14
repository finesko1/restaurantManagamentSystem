<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class WaiterController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('accessWaiterHome', User::class);
        return view('layouts.app');
    }
}
