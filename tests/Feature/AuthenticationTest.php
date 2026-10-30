<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_inventory_pages(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
        $this->get(route('products.index'))
            ->assertRedirect(route('login'));
        $this->get(route('stock-movements.index'))
            ->assertRedirect(route('login'));
    }

    public function test_login_page_is_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Iniciar sesión')
            ->assertSee('Correo electrónico')
            ->assertSee('Contraseña');
    }

    public function test_user_can_sign_in_and_sign_out(): void
    {
        $user = User::factory()->create([
            'email' => 'estudiante@example.test',
            'password' => 'clave-segura-123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'estudiante@example.test',
            'password' => 'clave-segura-123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('dashboard'))->assertOk()->assertSee($user->name);

        $this->post(route('logout'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertGuest();
    }

    public function test_invalid_credentials_do_not_sign_the_user_in(): void
    {
        User::factory()->create([
            'email' => 'estudiante@example.test',
            'password' => 'clave-segura-123',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'estudiante@example.test',
                'password' => 'incorrecta',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => 'desconocido@example.test',
                'password' => 'incorrecta',
            ])->assertSessionHasErrors('email');
        }

        $this->post(route('login.store'), [
            'email' => 'desconocido@example.test',
            'password' => 'incorrecta',
        ])->assertTooManyRequests();
    }

    public function test_authenticated_user_can_update_profile_details(): void
    {
        $user = User::factory()->create([
            'name' => 'Usuario de prueba',
            'email' => 'usuario@example.test',
        ]);

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Cambiar contraseña')
            ->assertSee('Guardar perfil')
            ->assertSee('Usuario de prueba');

        $this->put(route('profile.update'), [
            'name' => 'Nombre actualizado',
            'email' => 'actualizado@example.test',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nombre actualizado',
            'email' => 'actualizado@example.test',
        ]);
    }

    public function test_profile_email_must_be_unique(): void
    {
        $user = User::factory()->create(['email' => 'usuario@example.test']);
        User::factory()->create(['email' => 'otro@example.test']);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'otro@example.test',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_change_password_with_current_password(): void
    {
        $user = User::factory()->create(['password' => 'clave-anterior-123']);

        $this->actingAs($user)
            ->put(route('profile.password.update'), [
                'current_password' => 'clave-anterior-123',
                'password' => 'clave-nueva-456',
                'password_confirmation' => 'clave-nueva-456',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertTrue(password_verify('clave-nueva-456', $user->fresh()->password));
    }

    public function test_password_change_rejects_wrong_current_password(): void
    {
        $user = User::factory()->create(['password' => 'clave-anterior-123']);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.password.update'), [
                'current_password' => 'incorrecta',
                'password' => 'clave-nueva-456',
                'password_confirmation' => 'clave-nueva-456',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(password_verify('clave-anterior-123', $user->fresh()->password));
    }

    public function test_guest_cannot_access_or_update_profile(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->put(route('profile.update'), [
            'name' => 'Intruso',
            'email' => 'intruso@example.test',
        ])->assertRedirect(route('login'));
    }
}
