<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRtlColumnToLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('gutenberg_layouts', function (Blueprint $table) {
            $table->boolean('rtl')->default(false); 
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Schema::table('gutenberg_layouts', function (Blueprint $table) { 
            $table->dropColumn('rtl');
        }); 
    }
}
