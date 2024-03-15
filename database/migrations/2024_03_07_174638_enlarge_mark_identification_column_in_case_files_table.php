<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnlargeMarkIdentificationColumnInCaseFilesTable extends Migration
{
    public function up()
    {
        Schema::table('case_file_headers', function (Blueprint $table) {
            $table->string('mark_identification', 1024)->change(); // You can adjust the size as needed
            // Or use $table->text('mark_identification')->change(); for very long data
        });
    }

    public function down()
    {
        Schema::table('case_files_headers', function (Blueprint $table) {
            // Reverse the changes made in up()
            $table->string('mark_identification', 255)->change(); // Assuming the original size was 255
        });
    }
}
