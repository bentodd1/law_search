<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseFilesTable extends Migration
{
    public function up()
    {
        Schema::create('case_files', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->string('registration_number')->nullable();
            $table->date('transaction_date')->nullable();
            $table->foreignId('correspondent_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_files');
    }
}
