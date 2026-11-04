<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Filament\Auth\Pages\EditProfile;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_profile_page_redirects_guests_to_the_admin_login(): void
    {
        $response = $this->get('/admin/profile');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_update_their_own_profile_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Usuario inicial',
            'email' => 'inicial@example.com',
            'password' => 'Old-password-123!',
        ]);
        $otherUser = User::factory()->create([
            'name' => 'Otro usuario',
            'email' => 'otro@example.com',
        ]);

        $this->actingAs($user);

        $this->get('/admin/profile')
            ->assertOk()
            ->assertSee('Usuario inicial')
            ->assertSee('inicial@example.com')
            ->assertSee('Mi cuenta');

        Livewire::test(EditProfile::class)
            ->set('data.name', 'Nombre actualizado')
            ->set('data.email', 'actualizado@example.com')
            ->set('data.currentPassword', 'Old-password-123!')
            ->set('data.password', 'New-password-456!')
            ->set('data.passwordConfirmation', 'New-password-456!')
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();
        $otherUser->refresh();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nombre actualizado',
            'email' => 'actualizado@example.com',
        ]);
        $this->assertTrue(Hash::check('New-password-456!', $user->password));
        $this->assertDatabaseHas('users', [
            'id' => $otherUser->id,
            'name' => 'Otro usuario',
            'email' => 'otro@example.com',
        ]);
    }
}
