<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('name');              // 機械名
            $table->string('machine_number')->nullable();    // 機械番号・管理番号
            $table->string('maker')->nullable(); // メーカー
            $table->string('model')->nullable(); // 型式
            $table->string('location')->nullable(); // 設置場所

            $table->unsignedInteger('magazine_capacity')->nullable(); // マガジン本数

            $table->boolean('is_active')->default(true); // 使用中かどうか
            $table->text('note')->nullable(); // 備考

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
