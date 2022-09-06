<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergLayoutPluginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_layout_plugin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gutenberg_plugin_id')->constrained('gutenberg_plugins');
            $table->foreignId('gutenberg_layout_id')->constrained('gutenberg_layouts');
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
        Schema::dropIfExists('gutenberg_layout_plugin');
    }
}
