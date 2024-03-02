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
        Schema::create('case_file_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_number');
            $table->string('party_type');
            $table->string('country');
            $table->string('legal_entity_type_code');
            $table->string('entity_statement');
            $table->string('party_name');
            $table->string('address_1');
            $table->string('city');
            $table->string('postcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_file_owners');
    }
};
