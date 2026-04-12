<?php

namespace App\Http\Controllers\Auth;

use App\Actions\AuthAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly AuthAction $authAction
    ) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        try {
            $this->authAction->register($request->validated());

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'error' => 'Ocorreu um erro ao registrar o usuário. Por favor, tente novamente.',
                ]);
        }
    }
}