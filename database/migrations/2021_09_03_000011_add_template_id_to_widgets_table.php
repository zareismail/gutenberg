<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateIdToWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('gutenberg_widgets', function (Blueprint $table) {
            $table
                ->foreignId('template_id')
                ->nullable()
                ->after('config')
                ->constrained('gutenberg_templates');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('gutenberg_widgets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('template_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
