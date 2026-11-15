<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fees_structures', function (Blueprint $table) {
            $table->id();
            $table->string('class_level', 50)->comment('e.g., S.1 - S.3, S.4, S.5 - S.6');
            $table->string('fee_type', 50)->comment('boarding, day, boarding_bursary, day_bursary');
            $table->decimal('amount', 12, 2);
            $table->string('amount_secondary', 50)->nullable()->comment('e.g., for split bursary amounts 203,000/=/300,000/=');
            $table->string('term', 20)->nullable();
            $table->string('year', 10)->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['class_level', 'fee_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees_structures');
    }
};
