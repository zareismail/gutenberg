<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCacheTimeToWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('gutenberg_widgets', function (Blueprint $table) {
            $table->unsignedInteger('ttl')->default(300); 
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Schema::table('gutenberg_widgets', function (Blueprint $table) { 
            $table->dropColumn('ttl');
        }); 
    }
}
