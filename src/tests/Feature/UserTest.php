<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;
    private User $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create(['name' => 'テスト会社']);
        $this->admin   = User::factory()->create([
            'company_id' => $this->company->id,
            'role'       => 'admin',
        ]);
        $this->worker  = User::factory()->create([
            'company_id' => $this->company->id,
            'role'       => 'worker',
        ]);
    }

    public function test_admin_can_view_user_list(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/users')
            ->assertStatus(200);
    }

    public function test_worker_cannot_view_user_list(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/users')
            ->assertStatus(403);
    }

    public function test_guest_is_redirected_from_user_list(): void
    {
        $this->get('/app/users')
            ->assertRedirect('/login');
    }

    public function test_admin_can_view_create_user_form(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/users/create')
            ->assertStatus(200);
    }

    public function test_admin_can_create_user(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/users', [
                'name'                  => '新規ユーザー',
                'email'                 => 'new@example.com',
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'role'                  => 'worker',
            ])
            ->assertRedirect('/app/users');

        $this->assertDatabaseHas('users', [
            'email'      => 'new@example.com',
            'company_id' => $this->company->id,
        ]);
    }

    public function test_admin_cannot_create_user_with_duplicate_email(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/users', [
                'name'                  => '重複ユーザー',
                'email'                 => $this->worker->email,
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'role'                  => 'worker',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_worker_cannot_create_user(): void
    {
        $this->actingAs($this->worker)
            ->post('/app/users', [
                'name'  => '新規ユーザー',
                'email' => 'new@example.com',
                'role'  => 'worker',
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_view_edit_user_form(): void
    {
        $this->actingAs($this->admin)
            ->get("/app/users/{$this->worker->id}/edit")
            ->assertStatus(200);
    }

    public function test_admin_can_update_user(): void
    {
        $this->actingAs($this->admin)
            ->put("/app/users/{$this->worker->id}", [
                'name'  => '更新済みユーザー',
                'email' => $this->worker->email,
                'role'  => 'worker',
            ])
            ->assertRedirect('/app/users');

        $this->assertDatabaseHas('users', [
            'id'   => $this->worker->id,
            'name' => '更新済みユーザー',
        ]);
    }

    public function test_worker_cannot_update_user(): void
    {
        $this->actingAs($this->worker)
            ->put("/app/users/{$this->admin->id}", [
                'name'  => '書き換え',
                'email' => $this->admin->email,
                'role'  => 'admin',
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $this->actingAs($this->admin)
            ->delete("/app/users/{$this->worker->id}")
            ->assertRedirect('/app/users');

        $this->assertDatabaseMissing('users', ['id' => $this->worker->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $this->actingAs($this->admin)
            ->delete("/app/users/{$this->admin->id}")
            ->assertRedirect('/app/users')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_worker_cannot_delete_user(): void
    {
        $this->actingAs($this->worker)
            ->delete("/app/users/{$this->admin->id}")
            ->assertStatus(403);
    }
}
