<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Machine;
use App\Models\MagazinePot;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MagazineTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;
    private User $worker;
    private Machine $machine;
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
        $this->machine = Machine::create([
            'company_id'        => $this->company->id,
            'name'              => 'MC-001',
            'magazine_capacity' => 10,
            'available_spots'   => 10,
            'is_active'         => true,
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

    public function test_admin_can_view_magazine_index(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/magazines')
            ->assertStatus(200);
    }

    public function test_worker_can_view_magazine_index(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/magazines')
            ->assertStatus(200);
    }

    public function test_guest_is_redirected_from_magazine_index(): void
    {
        $this->get('/app/magazines')
            ->assertRedirect('/login');
    }

    public function test_anyone_can_view_machine_magazine(): void
    {
        $this->actingAs($this->worker)
            ->get("/app/magazines/{$this->machine->id}")
            ->assertStatus(200);
    }

    public function test_admin_can_view_create_pot_form(): void
    {
        $this->actingAs($this->admin)
            ->get("/app/magazines/{$this->machine->id}/pots/create/1")
            ->assertStatus(200);
    }

    public function test_worker_cannot_view_create_pot_form(): void
    {
        $this->actingAs($this->worker)
            ->get("/app/magazines/{$this->machine->id}/pots/create/1")
            ->assertStatus(403);
    }

    public function test_admin_can_create_pot(): void
    {
        $this->actingAs($this->admin)
            ->post("/app/magazines/{$this->machine->id}/pots", [
                'pot_number' => 1,
                'tool_ids'   => [$this->tool->id],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('magazine_pots', [
            'machine_id' => $this->machine->id,
            'pot_number' => 1,
        ]);
    }

    public function test_worker_cannot_create_pot(): void
    {
        $this->actingAs($this->worker)
            ->post("/app/magazines/{$this->machine->id}/pots", [
                'pot_number' => 1,
                'tool_ids'   => [$this->tool->id],
            ])
            ->assertStatus(403);
    }

    public function test_anyone_can_view_pot_detail(): void
    {
        $pot = MagazinePot::create([
            'machine_id' => $this->machine->id,
            'pot_number' => 1,
        ]);

        $this->actingAs($this->worker)
            ->get("/app/magazines/{$this->machine->id}/pots/{$pot->id}")
            ->assertStatus(200);
    }

    public function test_admin_can_disable_pot(): void
    {
        $this->actingAs($this->admin)
            ->post("/app/magazines/{$this->machine->id}/pots/1/disable")
            ->assertRedirect();

        $this->assertDatabaseHas('magazine_pots', [
            'machine_id'  => $this->machine->id,
            'pot_number'  => 1,
            'is_disabled' => true,
        ]);
    }

    public function test_worker_cannot_disable_pot(): void
    {
        $this->actingAs($this->worker)
            ->post("/app/magazines/{$this->machine->id}/pots/1/disable")
            ->assertStatus(403);
    }

    public function test_admin_can_enable_pot(): void
    {
        $pot = MagazinePot::create([
            'machine_id'  => $this->machine->id,
            'pot_number'  => 1,
            'is_disabled' => true,
        ]);

        $this->actingAs($this->admin)
            ->post("/app/magazines/{$this->machine->id}/pots/{$pot->id}/enable")
            ->assertRedirect();

        // enablePot はレコードを削除することで使用可能に戻す
        $this->assertDatabaseMissing('magazine_pots', ['id' => $pot->id]);
    }

    public function test_admin_can_delete_pot(): void
    {
        $pot = MagazinePot::create([
            'machine_id' => $this->machine->id,
            'pot_number' => 1,
        ]);

        $this->actingAs($this->admin)
            ->delete("/app/magazines/{$this->machine->id}/pots/{$pot->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('magazine_pots', ['id' => $pot->id]);
    }

    public function test_worker_cannot_delete_pot(): void
    {
        $pot = MagazinePot::create([
            'machine_id' => $this->machine->id,
            'pot_number' => 1,
        ]);

        $this->actingAs($this->worker)
            ->delete("/app/magazines/{$this->machine->id}/pots/{$pot->id}")
            ->assertStatus(403);
    }
}
