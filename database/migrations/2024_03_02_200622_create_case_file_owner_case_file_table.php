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
        Schema::create('case_file_owner_case_file', function (Blueprint $table) {
            $table->foreignId('case_file_id')->constrained()->onDelete('cascade');
            $table->foreignId('case_file_owner_id')->constrained()->onDelete('cascade');
            $table->primary(['case_file_id', 'case_file_owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_file_owner_case_file');
    }
};
