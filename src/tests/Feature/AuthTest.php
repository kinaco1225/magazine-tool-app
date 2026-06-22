<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $role = 'admin'): User
    {
        $company = Company::create(['name' => 'テスト会社']);
        return User::factory()->create([
            'company_id' => $company->id,
            'role'       => $role,
        ]);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createUser();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/app/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = $this->createUser();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email'    => 'notfound@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->post('/logout');

        $this->assertGuest();
    }

    public function test_root_redirects_to_login_for_guest(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_root_redirects_to_dashboard_for_authenticated_user(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect('/app/dashboard');
    }
}
