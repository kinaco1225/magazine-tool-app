<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
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
            'stock_quantity'   => 10,
            'reorder_point'    => 3,
            'manages_stock'    => true,
        ]);
    }

    public function test_admin_can_view_inventory(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/inventory')
            ->assertStatus(200);
    }

    public function test_worker_can_view_inventory(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/inventory')
            ->assertStatus(200);
    }

    public function test_guest_is_redirected_from_inventory(): void
    {
        $this->get('/app/inventory')
            ->assertRedirect('/login');
    }

    public function test_admin_can_add_stock(): void
    {
        $this->actingAs($this->admin)
            ->put("/app/inventory/{$this->tool->id}", [
                'type'     => 'add',
                'quantity' => 5,
            ])
            ->assertRedirect('/app/inventory');

        $this->assertDatabaseHas('tools', [
            'id'             => $this->tool->id,
            'stock_quantity' => 15,
        ]);
    }

    public function test_admin_can_use_stock(): void
    {
        $this->actingAs($this->admin)
            ->put("/app/inventory/{$this->tool->id}", [
                'type'     => 'use',
                'quantity' => 3,
            ])
            ->assertRedirect('/app/inventory');

        $this->assertDatabaseHas('tools', [
            'id'             => $this->tool->id,
            'stock_quantity' => 7,
        ]);
    }

    public function test_admin_can_set_stock(): void
    {
        $this->actingAs($this->admin)
            ->put("/app/inventory/{$this->tool->id}", [
                'type'     => 'set',
                'quantity' => 20,
            ])
            ->assertRedirect('/app/inventory');

        $this->assertDatabaseHas('tools', [
            'id'             => $this->tool->id,
            'stock_quantity' => 20,
        ]);
    }

    public function test_stock_does_not_go_below_zero_on_use(): void
    {
        $this->actingAs($this->admin)
            ->put("/app/inventory/{$this->tool->id}", [
                'type'     => 'use',
                'quantity' => 999,
            ])
            ->assertRedirect('/app/inventory');

        $this->assertDatabaseHas('tools', [
            'id'             => $this->tool->id,
            'stock_quantity' => 0,
        ]);
    }

    public function test_worker_cannot_update_stock(): void
    {
        $this->actingAs($this->worker)
            ->put("/app/inventory/{$this->tool->id}", [
                'type'     => 'add',
                'quantity' => 5,
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_update_reorder_point(): void
    {
        $this->actingAs($this->admin)
            ->put("/app/inventory/{$this->tool->id}/reorder-point", [
                'reorder_point' => 5,
            ])
            ->assertRedirect('/app/inventory');

        $this->assertDatabaseHas('tools', [
            'id'            => $this->tool->id,
            'reorder_point' => 5,
        ]);
    }

    public function test_worker_cannot_update_reorder_point(): void
    {
        $this->actingAs($this->worker)
            ->put("/app/inventory/{$this->tool->id}/reorder-point", [
                'reorder_point' => 5,
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_toggle_manages_stock_on(): void
    {
        $this->tool->update(['manages_stock' => false]);

        $this->actingAs($this->admin)
            ->post("/app/inventory/{$this->tool->id}/manages-stock", [
                'manages_stock' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tools', [
            'id'            => $this->tool->id,
            'manages_stock' => true,
        ]);
    }

    public function test_admin_can_toggle_manages_stock_off(): void
    {
        $this->actingAs($this->admin)
            ->post("/app/inventory/{$this->tool->id}/manages-stock", [
                'manages_stock' => false,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tools', [
            'id'            => $this->tool->id,
            'manages_stock' => false,
        ]);
    }

    public function test_worker_cannot_toggle_manages_stock(): void
    {
        $this->actingAs($this->worker)
            ->post("/app/inventory/{$this->tool->id}/manages-stock", [
                'manages_stock' => false,
            ])
            ->assertStatus(403);
    }
}
