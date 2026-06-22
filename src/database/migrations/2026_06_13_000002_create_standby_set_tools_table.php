<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standby_set_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standby_set_id')->constrained('standby_sets')->cascadeOnDelete();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->unique(['standby_set_id', 'tool_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standby_set_tools');
    }
};
