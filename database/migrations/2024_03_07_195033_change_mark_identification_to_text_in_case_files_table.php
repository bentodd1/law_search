<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMarkIdentificationToTextInCaseFilesTable extends Migration
{
    public function up()
    {
        Schema::table('case_file_headers', function (Blueprint $table) {
            $table->text('mark_identification')->change();
        });
    }

    public function down()
    {
        Schema::table('case_file_headers', function (Blueprint $table) {
            // Reverse the changes, possibly back to string with a specific length
            $table->string('mark_identification', 255)->change();
        });
    }
}
