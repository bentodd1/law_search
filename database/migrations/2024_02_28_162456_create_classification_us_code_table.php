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
        Schema::create('classification_american_code', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification_id')->constrained()->onDelete('cascade');
            $table->foreignId('american_code_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Add a unique constraint
            $table->unique(['classification_id', 'american_code_id'], 'american_class_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classification_american_code');
    }
};
