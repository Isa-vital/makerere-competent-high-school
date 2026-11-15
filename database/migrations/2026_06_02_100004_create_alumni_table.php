<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('maiden_name', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->year('graduation_year');
            $table->string('class_level', 50)->nullable();
            $table->string('course_studied', 100)->nullable();
            $table->string('current_profession', 100)->nullable();
            $table->string('current_employer', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('achievements')->nullable();
            $table->boolean('willing_to_mentor')->default(false);
            $table->boolean('willing_to_speak')->default(false);
            $table->boolean('willing_to_donate')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
