<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergPluginTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_plugin_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gutenberg_plugin_id')->constrained('gutenberg_plugins');
            $table->foreignId('gutenberg_template_id')->constrained('gutenberg_templates');
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
        Schema::dropIfExists('gutenberg_plugin_template');
    }
}
