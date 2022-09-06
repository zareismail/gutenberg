<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergFragmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_fragments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('gutenberg_websites');
            $table->string('name', 200);
            $table->string('fragment');
            $table->string('prefix');
            $table->boolean('fallback')->default(false);
            $table->string('marked_as', 20)->default('inactive');
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gutenberg_fragments');
    }
}
