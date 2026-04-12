<?php

namespace App\Http\Controllers\Auth;

use App\Actions\AuthAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly AuthAction $authAction
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $this->authAction->login(
                credentials: [
                    'email' => $data['email'],
                    'password' => $data['password'],
                ],
                remember: $data['remember'] ?? false
            );

            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'error' => 'Ocorreu um erro ao realizar o login. Por favor, tente novamente.',
                ]);
        }
    }

    public function destroy(): RedirectResponse
    {
        try {
            $this->authAction->logout();

            return redirect('/');
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'error' => 'Ocorreu um erro ao realizar logout. Por favor, tente novamente.',
            ]);
        }
    }
}