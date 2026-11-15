<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('value', 100)->nullable()->comment('e.g., 65%, #1, 95%');
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('color', 30)->default('primary')->comment('primary, success, warning, info, danger');
            $table->string('category', 50)->default('school')->index()
                ->comment('school, academic_results, alumni_giveback');
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
