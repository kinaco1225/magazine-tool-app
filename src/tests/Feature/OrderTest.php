<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Order;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
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
            'stock_quantity'   => 5,
            'manages_stock'    => true,
        ]);
    }

    public function test_admin_can_create_order(): void
    {
        $this->actingAs($this->admin)
            ->post("/app/tools/{$this->tool->id}/orders", [
                'quantity' => 10,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'tool_id'    => $this->tool->id,
            'company_id' => $this->company->id,
            'quantity'   => 10,
            'status'     => 'ordered',
        ]);
    }

    public function test_admin_cannot_create_order_with_zero_quantity(): void
    {
        $this->actingAs($this->admin)
            ->post("/app/tools/{$this->tool->id}/orders", [
                'quantity' => 0,
            ])
            ->assertSessionHasErrors('quantity');
    }

    public function test_worker_cannot_create_order(): void
    {
        $this->actingAs($this->worker)
            ->post("/app/tools/{$this->tool->id}/orders", [
                'quantity' => 10,
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_receive_order(): void
    {
        Order::create([
            'company_id' => $this->company->id,
            'tool_id'    => $this->tool->id,
            'quantity'   => 10,
            'status'     => 'ordered',
            'ordered_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->post("/app/tools/{$this->tool->id}/orders/receive", [
                'quantity' => 10,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'tool_id' => $this->tool->id,
            'status'  => 'received',
        ]);
        $this->assertDatabaseHas('tools', [
            'id'             => $this->tool->id,
            'stock_quantity' => 15,
        ]);
    }

    public function test_admin_can_partially_receive_order(): void
    {
        Order::create([
            'company_id' => $this->company->id,
            'tool_id'    => $this->tool->id,
            'quantity'   => 10,
            'status'     => 'ordered',
            'ordered_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->post("/app/tools/{$this->tool->id}/orders/receive", [
                'quantity' => 6,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'tool_id'  => $this->tool->id,
            'quantity' => 6,
            'status'   => 'received',
        ]);
        $this->assertDatabaseHas('orders', [
            'tool_id'  => $this->tool->id,
            'quantity' => 4,
            'status'   => 'ordered',
        ]);
        $this->assertDatabaseHas('tools', [
            'id'             => $this->tool->id,
            'stock_quantity' => 11,
        ]);
    }

    public function test_admin_cannot_receive_more_than_ordered(): void
    {
        Order::create([
            'company_id' => $this->company->id,
            'tool_id'    => $this->tool->id,
            'quantity'   => 5,
            'status'     => 'ordered',
            'ordered_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->post("/app/tools/{$this->tool->id}/orders/receive", [
                'quantity' => 99,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_worker_cannot_receive_order(): void
    {
        $this->actingAs($this->worker)
            ->post("/app/tools/{$this->tool->id}/orders/receive", [
                'quantity' => 5,
            ])
            ->assertStatus(403);
    }
}
