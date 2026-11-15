<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_stats', function (Blueprint $table) {
            $table->id();
            $table->string('stat_key', 100)->unique();
            $table->string('label');
            $table->string('value', 50);
            $table->string('suffix', 20)->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('category', 50)->default('homepage')->index();
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_stats');
    }
};
