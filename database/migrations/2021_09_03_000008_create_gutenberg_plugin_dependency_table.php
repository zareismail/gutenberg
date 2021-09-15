<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergPluginDependencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_plugin_dependencuy', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('plugin_id')->constrained('gutenberg_plugins');    
            $table->foreignId('dependency_id')->constrained('gutenberg_plugins');    
            $table->integer('order')->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gutenberg_plugin_dependencuy');
    }
}
