<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['isbn', 'author_id', 'title', 'year'];

    /**
     * Get book author
     */
    public function author() {
        return $this->belongsTo(Author::class);
    }
}
