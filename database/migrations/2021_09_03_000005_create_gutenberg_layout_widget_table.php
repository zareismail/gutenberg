<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGutenbergLayoutWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gutenberg_layout_widget', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gutenberg_layout_id')->constrained('gutenberg_layouts');
            $table->foreignId('gutenberg_widget_id')->constrained('gutenberg_widgets');
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('gutenberg_layout_widget');
    }
}
