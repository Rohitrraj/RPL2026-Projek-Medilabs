<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'patient')
            ->first();

        if ($user) {
            Password::broker()->sendResetLink([
                'email' => $user->email,
            ]);
        }

        return back()->with(
            'success',
            'Jika email terdaftar sebagai akun pasien, tautan reset password akan dikirim.'
        );
    }

    public function showResetPasswordForm(
        Request $request,
        string $token
    ): View {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
    $validated = $request->validate([
    'token' => ['required'],
    'email' => ['required', 'email'],
    'password' => [
        'required',
        'confirmed',
        PasswordRule::min(8),
    ],
    'password_confirmation' => ['required'],
]);

        $patientExists = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'patient')
            ->exists();

        if (! $patientExists) {
            return back()
                ->withErrors([
                    'email' => 'Tautan reset password tidak valid atau telah kedaluwarsa.',
                ])
                ->withInput($request->only('email'));
        }

$status = Password::broker()->reset(
    [
        'email' => $validated['email'],
        'password' => $validated['password'],
        'password_confirmation' => $validated['password_confirmation'],
        'token' => $validated['token'],
    ],
    function (User $user, string $password) {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
    }
);

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withErrors([
                    'email' => 'Tautan reset password tidak valid atau telah kedaluwarsa.',
                ])
                ->withInput($request->only('email'));
        }

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil diperbarui. Silakan login dengan password baru.');
    }
}