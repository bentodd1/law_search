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
        Schema::create('case_file_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained()->onDelete('cascade');
            $table->date('filing_date')->nullable();
            $table->integer('status_code')->nullable();
            $table->date('status_date')->nullable();
            $table->string('mark_identification')->nullable();
            $table->integer('mark_drawing_code')->nullable();
            $table->date('published_for_opposition_date')->nullable();
            $table->string('attorney_docket_number')->nullable();
            $table->string('attorney_name')->nullable();
            // ... other fields
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_file_headers');
    }
};
