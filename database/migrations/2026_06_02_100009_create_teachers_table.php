<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('email', 100)->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('employee_id', 50)->unique()->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization', 100)->nullable();
            $table->text('subjects_taught')->nullable();
            $table->unsignedSmallInteger('experience_years')->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('bio')->nullable();
            $table->enum('status', ['active', 'inactive', 'retired'])->default('active');
            $table->date('hire_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
