<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCaseFileOwnersForEntityStatement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('case_file_owners', function (Blueprint $table) {
            // Change column from string to text
            $table->text('entity_statement')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_file_owners', function (Blueprint $table) {
            // Change back to string
            $table->string('entity_statement')->change();
        });
    }
}
