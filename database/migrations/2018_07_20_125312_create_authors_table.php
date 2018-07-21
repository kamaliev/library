<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('authors', function(Blueprint $table) {
            $table->increments('id');
            $table->string('author');
            $table->enum('type', ['cd', 'book']);

            $table->unique(['author','type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('authors', function(Blueprint $table) {
            $table->drop();
        });
    }
}
