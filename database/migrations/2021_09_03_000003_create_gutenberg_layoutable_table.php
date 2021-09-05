<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergLayoutableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_layoutable', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('gutenberg_layout_id')->constrained('gutenberg_layouts'); 
            $table->morphs('layoutable');   
            $table->json('config')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gutenberg_layoutable');
    }
}
