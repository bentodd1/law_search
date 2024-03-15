<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseFileEventStatementsTable extends Migration
{
    public function up()
    {
        Schema::create('case_file_event_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained()->onDelete('cascade');
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->text('description_text')->nullable();
            $table->date('date')->nullable();
            $table->integer('number')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_file_event_statements');
    }
}
