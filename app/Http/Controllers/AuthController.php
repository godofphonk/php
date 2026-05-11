<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
        ], [
            'name.required' => 'Пожалуйста, введите ваше ФИО',
            'name.max' => 'ФИО не должно превышать 255 символов',
            'email.required' => 'Пожалуйста, введите email',
            'email.email' => 'Пожалуйста, введите корректный email',
            'email.unique' => 'Пользователь с таким email уже существует',
            'password.required' => 'Пожалуйста, введите пароль',
            'password.min' => 'Пароль должен содержать минимум 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'phone.required' => 'Пожалуйста, введите номер телефона',
            'phone.regex' => 'Пожалуйста, введите корректный номер телефона (10-15 цифр)',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'visitor',
        ]);

        Auth::login($user);

        if ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        }

        return redirect()->route('home');
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Пожалуйста, введите email',
            'email.email' => 'Пожалуйста, введите корректный email',
            'password.required' => 'Пожалуйста, введите пароль',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->isInstructor()) {
                return redirect()->route('instructor.dashboard');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
