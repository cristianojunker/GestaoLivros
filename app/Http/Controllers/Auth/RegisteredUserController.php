<?php

namespace App\Http\Controllers\Auth;

use App\Actions\AuthAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        $this->authAction->register($request->validated());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}