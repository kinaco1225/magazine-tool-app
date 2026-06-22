<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magazine_pots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('pot_number');
            $table->timestamps();

            $table->unique(['machine_id', 'pot_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_pots');
    }
};
