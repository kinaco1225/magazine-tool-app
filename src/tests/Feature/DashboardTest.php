<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
            ->get('/app/dashboard')
            ->assertStatus(200);
    }

    public function test_worker_can_access_dashboard(): void
    {
        $worker = $this->createUser('worker');

        $this->actingAs($worker)
            ->get('/app/dashboard')
            ->assertStatus(200);
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/app/dashboard')
            ->assertRedirect('/login');
    }
}
