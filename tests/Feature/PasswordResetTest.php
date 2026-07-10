<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_can_open_forgot_password_form(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Lupa Password');
    }

    public function test_authenticated_user_cannot_open_forgot_password_form(): void
    {
        $user = User::factory()->create([
            'role' => 'patient',
        ]);

        $this->actingAs($user)
            ->get(route('password.request'))
            ->assertRedirect(route('home'));
    }

    public function test_patient_can_request_password_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'patient',
            'email' => 'patient@example.com',
        ]);

        $this->post(route('password.email'), [
            'email' => $user->email,
        ])->assertSessionHas('success');

        Notification::assertSentTo(
            $user,
            ResetPassword::class
        );
    }

    public function test_unknown_email_does_not_reveal_account_existence(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'unknown@example.com',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas(
                'success',
                'Jika email terdaftar sebagai akun pasien, tautan reset password akan dikirim.'
            );

        Notification::assertNothingSent();
    }

    public function test_admin_does_not_receive_patient_reset_link(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->post(route('password.email'), [
            'email' => $admin->email,
        ])->assertSessionHas('success');

        Notification::assertNothingSent();
    }

    public function test_patient_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'role' => 'patient',
            'email' => 'patient@example.com',
            'password' => Hash::make('password-lama'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertTrue(
            Hash::check('password-baru', $user->fresh()->password)
        );

        $this->assertFalse(
            Hash::check('password-lama', $user->fresh()->password)
        );
    }

    public function test_invalid_token_is_rejected(): void
    {
        $user = User::factory()->create([
            'role' => 'patient',
            'email' => 'patient@example.com',
        ]);

        $response = $this->from(
            route('password.reset', [
                'token' => 'invalid-token',
                'email' => $user->email,
            ])
        )->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ]);

        $response
            ->assertRedirect(
                route('password.reset', [
                    'token' => 'invalid-token',
                    'email' => $user->email,
                ])
            )
            ->assertSessionHasErrors('email');
    }

    public function test_password_confirmation_must_match(): void
    {
        $user = User::factory()->create([
            'role' => 'patient',
            'email' => 'patient@example.com',
        ]);

        $token = Password::broker()->createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password-baru',
            'password_confirmation' => 'password-berbeda',
        ])->assertSessionHasErrors('password');
    }
}