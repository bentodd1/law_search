<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastProcessedElementToScannedFilesTable extends Migration
{
    public function up()
    {
        Schema::table('scanned_files', function (Blueprint $table) {
            $table->string('last_processed_element')->nullable()->after('scanned');
        });
    }

    public function down()
    {
        Schema::table('scanned_files', function (Blueprint $table) {
            $table->dropColumn('last_processed_element');
        });
    }
}

