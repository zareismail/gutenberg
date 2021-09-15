<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergPluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_plugins', function (Blueprint $table) {
            $table->id(); 
            $table->string('name', 200); 
            $table->string('note')->nullable(); 
            $table->string('marked_as', 20)->default('inactive');  
            $table->json('assets')->nullable();    
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gutenberg_plugins');
    }
}
