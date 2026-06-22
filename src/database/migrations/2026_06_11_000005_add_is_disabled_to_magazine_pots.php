<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('magazine_pots') && ! Schema::hasColumn('magazine_pots', 'is_disabled')) {
            Schema::table('magazine_pots', function (Blueprint $table) {
                $table->boolean('is_disabled')->default(false)->after('pot_number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('magazine_pots', 'is_disabled')) {
            Schema::table('magazine_pots', function (Blueprint $table) {
                $table->dropColumn('is_disabled');
            });
        }
    }
};
