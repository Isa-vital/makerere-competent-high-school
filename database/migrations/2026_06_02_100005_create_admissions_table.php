<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('student_name', 100);
            $table->string('parent_name', 100);
            $table->string('email', 100);
            $table->string('phone', 20);
            $table->text('address');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('previous_school', 100)->nullable();
            $table->string('class_applying', 50);
            $table->string('documents_path')->nullable();
            $table->boolean('application_fee_paid')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'enrolled'])->default('pending')->index();
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
