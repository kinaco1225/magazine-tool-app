<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // magazine_pots から tool_id を削除
        if (Schema::hasColumn('magazine_pots', 'tool_id')) {
            Schema::table('magazine_pots', function (Blueprint $table) {
                $table->dropForeign(['tool_id']);
                $table->dropColumn('tool_id');
            });
        }

        // 中間テーブルを作成（未作成の場合のみ）
        if (! Schema::hasTable('magazine_pot_tools')) {
            Schema::create('magazine_pot_tools', function (Blueprint $table) {
                $table->id();
                $table->foreignId('magazine_pot_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['magazine_pot_id', 'tool_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_pot_tools');

        if (! Schema::hasColumn('magazine_pots', 'tool_id')) {
            Schema::table('magazine_pots', function (Blueprint $table) {
                $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            });
        }
    }
};
