<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name', 100);
            $table->string('program_code', 20)->unique();
            $table->enum('level', ['O-Level', 'A-Level']);
            $table->text('description')->nullable();
            $table->string('duration', 50)->nullable();
            $table->text('subjects')->nullable();
            $table->text('requirements')->nullable();
            $table->decimal('fees', 10, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_programs');
    }
};
