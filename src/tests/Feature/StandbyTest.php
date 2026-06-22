<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Machine;
use App\Models\StandbySet;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StandbyTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;
    private User $worker;
    private Tool $tool;

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

        $category   = ToolCategory::create([
            'company_id' => $this->company->id,
            'name'       => 'ドリル',
        ]);
        $this->tool = Tool::create([
            'company_id'       => $this->company->id,
            'tool_category_id' => $category->id,
            'name'             => 'ドリル3.0mm',
            'maker'            => 'OSG',
            'model'            => 'A-GDR-3.0',
        ]);
    }

    public function test_admin_can_view_standby_index(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/standby')
            ->assertStatus(200);
    }

    public function test_worker_can_view_standby_index(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/standby')
            ->assertStatus(200);
    }

    public function test_guest_is_redirected_from_standby_index(): void
    {
        $this->get('/app/standby')
            ->assertRedirect('/login');
    }

    public function test_admin_can_view_create_standby_form(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/standby/create')
            ->assertStatus(200);
    }

    public function test_worker_cannot_view_create_standby_form(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/standby/create')
            ->assertStatus(403);
    }

    public function test_admin_can_create_standby_set(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/standby', [
                'tool_ids' => [$this->tool->id],
            ])
            ->assertRedirect('/app/standby');

        $this->assertDatabaseHas('standby_sets', [
            'company_id' => $this->company->id,
        ]);
    }

    public function test_admin_cannot_create_standby_set_without_tools(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/standby', [
                'tool_ids' => [],
            ])
            ->assertSessionHasErrors('tool_ids');
    }

    public function test_worker_cannot_create_standby_set(): void
    {
        $this->actingAs($this->worker)
            ->post('/app/standby', [
                'tool_ids' => [$this->tool->id],
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_delete_standby_set(): void
    {
        $set = StandbySet::create(['company_id' => $this->company->id]);
        $set->tools()->attach($this->tool->id);

        $this->actingAs($this->admin)
            ->delete("/app/standby/{$set->id}")
            ->assertRedirect('/app/standby');

        $this->assertDatabaseMissing('standby_sets', ['id' => $set->id]);
    }

    public function test_worker_cannot_delete_standby_set(): void
    {
        $set = StandbySet::create(['company_id' => $this->company->id]);

        $this->actingAs($this->worker)
            ->delete("/app/standby/{$set->id}")
            ->assertStatus(403);
    }

    public function test_admin_can_assign_standby_set_to_pot(): void
    {
        $machine = Machine::create([
            'company_id'        => $this->company->id,
            'name'              => 'MC-001',
            'magazine_capacity' => 10,
            'available_spots'   => 10,
            'is_active'         => true,
        ]);

        $set = StandbySet::create(['company_id' => $this->company->id]);
        $set->tools()->attach($this->tool->id);

        $this->actingAs($this->admin)
            ->post("/app/standby/{$set->id}/assign", [
                'machine_id' => $machine->id,
                'pot_number' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('magazine_pots', [
            'machine_id' => $machine->id,
            'pot_number' => 1,
        ]);
        $this->assertDatabaseMissing('standby_sets', ['id' => $set->id]);
    }

    public function test_worker_cannot_assign_standby_set(): void
    {
        $machine = Machine::create([
            'company_id'        => $this->company->id,
            'name'              => 'MC-001',
            'magazine_capacity' => 10,
            'available_spots'   => 10,
            'is_active'         => true,
        ]);

        $set = StandbySet::create(['company_id' => $this->company->id]);

        $this->actingAs($this->worker)
            ->post("/app/standby/{$set->id}/assign", [
                'machine_id' => $machine->id,
                'pot_number' => 1,
            ])
            ->assertStatus(403);
    }
}
