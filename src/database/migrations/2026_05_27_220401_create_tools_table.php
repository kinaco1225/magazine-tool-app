<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tool_category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');                          // 工具名
            $table->string('maker')->nullable();             // メーカー
            $table->string('model')->nullable();             // 型式

            $table->text('note')->nullable();               // 備考

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
