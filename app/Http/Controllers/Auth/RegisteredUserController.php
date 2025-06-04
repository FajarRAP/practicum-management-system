<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SecurityQuestion;
use App\Models\User;
use App\Models\UserSecurityQuestion;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'security_question' => SecurityQuestion::first(),
            'roles' => Role::all()->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'formatted_name' => Str::of($role->name)->replace('_', ' ')->title()
            ])->values(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'security_question' => ['required', 'exists:security_questions,id'],
            'security_question_answer' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole($this->evaluateEmail($request->email));

        UserSecurityQuestion::create([
            'user_id' => $user->id,
            'security_question_id' => $request->security_question,
            'answer' => $request->security_question_answer,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
    private function evaluateEmail(string $email): string
    {
        if (Str::of($email)->endsWith('@asist.trisakti.ac.id')) return 'assistant';
        if (Str::of($email)->endsWith('@std.trisakti.ac.id')) return 'student';
        if (Str::of($email)->endsWith('@trisakti.ac.id')) return 'lecturer';
        if (Str::of($email)->endsWith('@laboran.trisakti.ac.id')) return 'lab_tech';

        return 'student';
    }
}
