<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Валидация роли
        $request->validate([
            'role' => 'required|in:waiter,cook'
        ]);

        // Проверка блокировки
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Определение guard
        $guard = $this->resolveGuard($request->input('role'));

        // Попытка аутентификации
        if ($this->attemptLoginUsingGuard($guard, $request)) {
            return $this->sendRoleSpecificResponse($request, $guard);
        }

        // Увеличение счетчика попыток
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function resolveGuard(string $role): string
    {
        return match($role) {
            'waiter' => 'waiter',
            'cook' => 'cook',
            default => throw ValidationException::withMessages([
                'role' => ['Недопустимая роль пользователя']
            ])
        };
    }

    protected function attemptLoginUsingGuard(string $guard, Request $request): bool
    {
        return Auth::guard($guard)->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    protected function sendRoleSpecificResponse(Request $request, string $guard)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return redirect()->intended(
            $this->redirectPathForGuard($guard)
        );
    }

    protected function redirectPathForGuard(string $guard): string
    {
        return match($guard) {
//            'waiter' => route('home'),
//            'cook' => route('home'),
            default => '/home'
        };
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
        ];
    }

    public function __construct()
    {
        // Middleware для аутентифицированных пользователей
        $this->middleware('auth:cook,waiter')->only('logout');

        // Middleware для незарегистрированных пользователей
        $this->middleware('guest')->except('logout');
    }
}
