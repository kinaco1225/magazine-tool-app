<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ToolCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolCategoryTest extends TestCase
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

    private function createCategory(string $name = 'ドリル'): ToolCategory
    {
        return ToolCategory::create([
            'company_id' => $this->company->id,
            'name'       => $name,
        ]);
    }

    public function test_admin_can_view_create_category_form(): void
    {
        $this->actingAs($this->admin)
            ->get('/app/tool-categories/create')
            ->assertStatus(200);
    }

    public function test_worker_cannot_view_create_category_form(): void
    {
        $this->actingAs($this->worker)
            ->get('/app/tool-categories/create')
            ->assertStatus(403);
    }

    public function test_admin_can_create_tool_category(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/tool-categories', ['name' => 'エンドミル'])
            ->assertRedirect();

        $this->assertDatabaseHas('tool_categories', [
            'name'       => 'エンドミル',
            'company_id' => $this->company->id,
        ]);
    }

    public function test_admin_cannot_create_category_without_name(): void
    {
        $this->actingAs($this->admin)
            ->post('/app/tool-categories', ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_worker_cannot_create_tool_category(): void
    {
        $this->actingAs($this->worker)
            ->post('/app/tool-categories', ['name' => 'エンドミル'])
            ->assertStatus(403);
    }

    public function test_admin_can_view_edit_category_form(): void
    {
        $category = $this->createCategory();

        $this->actingAs($this->admin)
            ->get("/app/tool-categories/{$category->id}/edit")
            ->assertStatus(200);
    }

    public function test_admin_can_update_tool_category(): void
    {
        $category = $this->createCategory();

        $this->actingAs($this->admin)
            ->put("/app/tool-categories/{$category->id}", ['name' => 'タップ'])
            ->assertRedirect();

        $this->assertDatabaseHas('tool_categories', [
            'id'   => $category->id,
            'name' => 'タップ',
        ]);
    }

    public function test_worker_cannot_update_tool_category(): void
    {
        $category = $this->createCategory();

        $this->actingAs($this->worker)
            ->put("/app/tool-categories/{$category->id}", ['name' => 'タップ'])
            ->assertStatus(403);
    }

    public function test_admin_can_delete_tool_category(): void
    {
        $category = $this->createCategory();

        $this->actingAs($this->admin)
            ->delete("/app/tool-categories/{$category->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('tool_categories', ['id' => $category->id]);
    }

    public function test_worker_cannot_delete_tool_category(): void
    {
        $category = $this->createCategory();

        $this->actingAs($this->worker)
            ->delete("/app/tool-categories/{$category->id}")
            ->assertStatus(403);
    }
}
