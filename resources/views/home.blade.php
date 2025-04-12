@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if ($user = Auth::guard('waiter')->check())
            <div class="waiter-block">
                <h2>Welcome, waiter!</h2>
            </div>
        @elseif ($user = Auth::guard('cook')->check())
            <div class="cook-block">
                <h2>Welcome, cook!</h2>
            </div>
        @else
            <div class="default-block">
                <h2>Welcome!</h2>
            </div>
        @endif
    </div>
</div>
@endsection
