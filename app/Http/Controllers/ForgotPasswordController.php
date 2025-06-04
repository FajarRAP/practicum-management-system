<?php

namespace App\Http\Controllers;

use App\Models\SecurityQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password', [
            'security_question' => SecurityQuestion::first(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'security_question' => ['required', 'exists:security_questions,id'],
            'security_question_answer' => ['required', 'string', 'max:255'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!($user->securityQuestion->answer === $request->security_question_answer)) {
            return back()->with('error', 'The answer to the security question is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect(route('login'))->with('success', 'Successfully reset password.');
    }
}
