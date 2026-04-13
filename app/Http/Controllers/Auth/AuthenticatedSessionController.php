<?php

namespace App\Http\Controllers\Auth;

use App\Actions\AuthAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly AuthAction $authAction
    ) {}

    // Retorna a view de login
    public function create(): View
    {
        return view('auth.login');
    }

    // Faz o login a partir da action
    public function store(LoginRequest $request): RedirectResponse
    {
        $this->authAction->login($request);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    // Faz o logout a partir da action
    public function destroy(): RedirectResponse
    {
        $this->authAction->logout();

        return redirect('/');
    }
}