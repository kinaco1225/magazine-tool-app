<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private User $admin;
    private User $worker;
    private ToolCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company  = Company::create(['name' => 'テスト会社']);
        $this->admin    = User::factory()->create([
            'company_id' => $this->company->id,
            'role'       => 'admin',
        ]);
        $this->worker   = User::factory()->create([
            'company_id' => $this->company->id,
            'role'       => 'worker',
        ]);
        $this->category = ToolCategory::create([
            'company_id' => $this->company->id,
            'name'       => 'ドリル',
        ]);
    }

    private function createTool(array $attrs = []): Tool
    {
        return Tool::create(array_merge([
            'company_id'      => $this->company->id,
            'tool_category_id'=> $this->category->id,
            'name'            => 'テスト工具',
            'maker'           => 'OSG',
            'model'           => 'A-GDR-3.0',
        ], $attrs));
    }

    public function test_admin_can_view_tool_index(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/tools')
            ->assertStatus(200);
    }

    public function test_worker_can_view_tool_index(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/tools')
            ->assertStatus(200);
    }

    public function test_guest_is_redirected_from_tool_index(): void
    {
        $this->get('/app/tools')
            ->assertRedirect('/login');
    }

    public function test_anyone_can_view_tools_by_category(): void
    {
        $this->actingAs($this->worker)
            ->get("/app/tools/category/{$this->category->id}")
            ->assertStatus(200);
    }

    public function test_admin_can_view_create_tool_form(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/tools/create')
            ->assertStatus(200);
    }

    public function test_worker_cannot_view_create_tool_form(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/tools/create')
            ->assertStatus(403);
    }

    public function test_admin_can_create_tool(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/tools', [
                'name'             => 'ドリル3.0mm',
                'tool_category_id' => $this->category->id,
                'maker'            => 'OSG',
                'model'            => 'A-GDR-3.0',
                'stock_quantity'   => 10,
                'reorder_point'    => 3,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tools', [
            'name'       => 'ドリル3.0mm',
            'company_id' => $this->company->id,
        ]);
    }

    public function test_admin_cannot_create_tool_without_required_fields(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/tools', [
                'name'  => '',
                'maker' => '',
                'model' => '',
            ])
            ->assertSessionHasErrors(['name', 'maker', 'model', 'tool_category_id']);
    }

    public function test_worker_cannot_create_tool(): void
    {
        $this->actingAs($this->worker)
            ->post('/app/tools', [
                'name'             => 'ドリル3.0mm',
                'tool_category_id' => $this->category->id,
                'maker'            => 'OSG',
                'model'            => 'A-GDR-3.0',
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_view_edit_tool_form(): void
    {
        $tool = $this->createTool();

        $this->actingAs($this->admin)
            ->get("/app/tools/{$tool->id}/edit")
            ->assertStatus(200);
    }

    public function test_admin_can_update_tool(): void
    {
        $tool = $this->createTool();

        $this->actingAs($this->admin)
            ->put("/app/tools/{$tool->id}", [
                'name'             => '更新済み工具',
                'tool_category_id' => $this->category->id,
                'maker'            => 'OSG',
                'model'            => 'A-GDR-4.0',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tools', [
            'id'   => $tool->id,
            'name' => '更新済み工具',
        ]);
    }

    public function test_worker_cannot_update_tool(): void
    {
        $tool = $this->createTool();

        $this->actingAs($this->worker)
            ->put("/app/tools/{$tool->id}", [
                'name'             => '書き換え',
                'tool_category_id' => $this->category->id,
                'maker'            => 'OSG',
                'model'            => 'X',
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_delete_tool(): void
    {
        $tool = $this->createTool();

        $this->actingAs($this->admin)
            ->delete("/app/tools/{$tool->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('tools', ['id' => $tool->id]);
    }

    public function test_worker_cannot_delete_tool(): void
    {
        $tool = $this->createTool();

        $this->actingAs($this->worker)
            ->delete("/app/tools/{$tool->id}")
            ->assertStatus(403);
    }
}
