<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('books', function(Blueprint $table) {
            $table->increments('id');
            $table->string('isbn', 13)->unique();
            $table->integer('author_id');
            $table->text('title');
            $table->year('year');
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors');
            $table->index('author_id');
            $table->index('title');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('books', function(Blueprint $table) {
            $table->dropForeign('books_author_id_foreign');
            $table->drop();
        });
    }
}
