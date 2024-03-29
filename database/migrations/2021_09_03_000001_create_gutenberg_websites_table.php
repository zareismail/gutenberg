<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_websites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('title', 200);
            $table->string('description', 500);
            $table->string('component');
            $table->string('directory')->unique();
            $table->boolean('fallback')->default(false);
            $table->string('locale')->default(app()->getLocale());
            $table->string('marked_as', 20)->default('inactive');
            $table->json('config')->nullable();
            $table->foreignId('gutenberg_layout_id')->constrained('gutenberg_layouts');
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
        Schema::dropIfExists('gutenberg_websites');
    }
}
