<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->boolean('contains_2d')->nullable(); // Replace 'some_column' with an actual column name to position it
        });
    }

    public function down()
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->dropColumn('contains_2d');
        });
    }
};
