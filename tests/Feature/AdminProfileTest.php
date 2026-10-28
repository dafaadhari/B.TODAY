<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Moch Dapa Adhari',
            'email' => 'dapa@btoday.com',
            'password' => bcrypt('password-lama'),
            'role' => 'super_admin',
        ]);
    }

    public function test_profile_page_is_linked_from_the_account_menu(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee(route('admin.profile.edit'));

        $this->actingAs($this->admin)
            ->get(route('admin.profile.edit'))
            ->assertOk()
            ->assertSee('Profil Saya')
            ->assertSee('dapa@btoday.com')
            ->assertSee('MD');
    }

    public function test_name_can_be_changed_without_password_but_role_stays_the_same(): void
    {
        $this->actingAs($this->admin)
            ->put(route('admin.profile.update'), ['name' => 'Dapa Adhari', 'email' => 'dapa@btoday.com', 'role' => 'reporter'])
            ->assertRedirect(route('admin.profile.edit'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertSame('Dapa Adhari', $this->admin->fresh()->name);
        $this->assertSame('super_admin', $this->admin->fresh()->role);
        $this->assertDatabaseHas('audit_logs', ['user_id' => $this->admin->id, 'action' => 'update_profile']);
    }

    public function test_changing_email_requires_the_current_password_and_a_unique_address(): void
    {
        User::create(['name' => 'Admin Dua', 'email' => 'admin2@btoday.com', 'password' => bcrypt('password')]);

        $this->actingAs($this->admin)->from(route('admin.profile.edit'))
            ->put(route('admin.profile.update'), ['name' => 'Moch Dapa Adhari', 'email' => 'baru@btoday.com'])
            ->assertSessionHasErrorsIn('profile', ['current_password' => 'Masukkan password saat ini untuk mengganti email.']);

        $this->actingAs($this->admin)->from(route('admin.profile.edit'))
            ->put(route('admin.profile.update'), ['name' => 'Moch Dapa Adhari', 'email' => 'baru@btoday.com', 'current_password' => 'salah'])
            ->assertSessionHasErrorsIn('profile', ['current_password' => 'Password saat ini salah.']);

        $this->actingAs($this->admin)->from(route('admin.profile.edit'))
            ->put(route('admin.profile.update'), ['name' => 'Moch Dapa Adhari', 'email' => 'admin2@btoday.com', 'current_password' => 'password-lama'])
            ->assertSessionHasErrorsIn('profile', ['email' => 'Email ini sudah dipakai akun lain.']);

        $this->assertSame('dapa@btoday.com', $this->admin->fresh()->email);

        $this->actingAs($this->admin)
            ->put(route('admin.profile.update'), ['name' => 'Moch Dapa Adhari', 'email' => ' Baru@BToday.com ', 'current_password' => 'password-lama'])
            ->assertSessionHasNoErrors();

        $this->assertSame('baru@btoday.com', $this->admin->fresh()->email);
    }

    public function test_password_change_validates_input(): void
    {
        $cases = [
            [['current_password' => 'salah', 'password' => 'password-baru', 'password_confirmation' => 'password-baru'], 'current_password', 'Password saat ini salah.'],
            [['current_password' => 'password-lama', 'password' => 'pendek', 'password_confirmation' => 'pendek'], 'password', 'Password baru minimal 8 karakter.'],
            [['current_password' => 'password-lama', 'password' => 'password-baru', 'password_confirmation' => 'beda-sendiri'], 'password', 'Ulangan password baru tidak cocok.'],
            [['current_password' => 'password-lama', 'password' => 'password-lama', 'password_confirmation' => 'password-lama'], 'password', 'Password baru harus berbeda dari password saat ini.'],
        ];

        foreach ($cases as [$input, $field, $message]) {
            $this->actingAs($this->admin)->from(route('admin.profile.edit'))
                ->put(route('admin.profile.password'), $input)
                ->assertSessionHasErrorsIn('password', [$field => $message]);
        }

        $this->assertTrue(Hash::check('password-lama', $this->admin->fresh()->password));
    }

    public function test_password_change_logs_out_other_devices(): void
    {
        Event::fake([OtherDeviceLogout::class]);

        $this->actingAs($this->admin)
            ->put(route('admin.profile.password'), [
                'current_password' => 'password-lama',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect(route('admin.profile.edit'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('password-baru', $this->admin->fresh()->password));
        Event::assertDispatched(OtherDeviceLogout::class);
        $this->assertDatabaseHas('audit_logs', ['user_id' => $this->admin->id, 'action' => 'update_password']);
    }
}
