<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Machine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MachineTest extends TestCase
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

    private function createMachine(array $attrs = []): Machine
    {
        return Machine::create(array_merge([
            'company_id' => $this->company->id,
            'name'       => 'テスト機械',
            'is_active'  => true,
        ], $attrs));
    }

    public function test_admin_can_view_machine_list(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/machines')
            ->assertStatus(200);
    }

    public function test_worker_can_view_machine_list(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/machines')
            ->assertStatus(200);
    }

    public function test_guest_is_redirected_from_machine_list(): void
    {
        $this->get('/app/machines')
            ->assertRedirect('/login');
    }

    public function test_admin_can_view_create_machine_form(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/machines/create')
            ->assertStatus(200);
    }

    public function test_worker_cannot_view_create_machine_form(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/machines/create')
            ->assertStatus(403);
    }

    public function test_admin_can_create_machine(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/machines', [
                'name'              => 'MC-001',
                'machine_number'    => 'NO-001',
                'maker'             => 'FANUC',
                'model'             => 'α-D21MiA',
                'location'          => 'A棟1F',
                'magazine_capacity' => 30,
                'is_active'         => true,
            ])
            ->assertRedirect('/app/machines');

        $this->assertDatabaseHas('machines', [
            'name'       => 'MC-001',
            'company_id' => $this->company->id,
        ]);
    }

    public function test_admin_cannot_create_machine_without_name(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/machines', [
                'name'      => '',
                'is_active' => true,
            ])
            ->assertSessionHasErrors('name');
    }

    public function test_worker_cannot_create_machine(): void
    {
        $this->actingAs($this->worker)
            ->post('/app/machines', [
                'name'      => 'MC-002',
                'is_active' => true,
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_view_edit_machine_form(): void
    {
        $machine = $this->createMachine();

        $this->actingAs($this->admin)
            ->get("/app/machines/{$machine->id}/edit")
            ->assertStatus(200);
    }

    public function test_admin_can_update_machine(): void
    {
        $machine = $this->createMachine();

        $this->actingAs($this->admin)
            ->put("/app/machines/{$machine->id}", [
                'name'      => '更新済み機械',
                'is_active' => true,
            ])
            ->assertRedirect('/app/machines');

        $this->assertDatabaseHas('machines', [
            'id'   => $machine->id,
            'name' => '更新済み機械',
        ]);
    }

    public function test_worker_cannot_update_machine(): void
    {
        $machine = $this->createMachine();

        $this->actingAs($this->worker)
            ->put("/app/machines/{$machine->id}", [
                'name'      => '書き換え',
                'is_active' => true,
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_delete_machine(): void
    {
        $machine = $this->createMachine();

        $this->actingAs($this->admin)
            ->delete("/app/machines/{$machine->id}")
            ->assertRedirect('/app/machines');

        $this->assertDatabaseMissing('machines', ['id' => $machine->id]);
    }

    public function test_worker_cannot_delete_machine(): void
    {
        $machine = $this->createMachine();

        $this->actingAs($this->worker)
            ->delete("/app/machines/{$machine->id}")
            ->assertStatus(403);
    }
}
