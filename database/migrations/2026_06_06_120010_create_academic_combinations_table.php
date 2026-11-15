<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_combinations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 100)->nullable();
            $table->string('subjects')->nullable();
            $table->string('faculty', 50)->index()->comment('sciences, arts, commercial');
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_combinations');
    }
};
