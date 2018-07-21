<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCdsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('cds', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id');
            $table->text('title');
            $table->year('year');
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors');
            $table->unique(['author_id', 'title', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('cds', function(Blueprint $table) {
            $table->dropForeign('cds_author_id_foreign');
            $table->drop();
        });
    }
}
