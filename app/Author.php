<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model {
    /**
     * Enum types
     */
    const ENUM_CD = 'cd';
    const ENUM_BOOK = 'book';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authors';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['author', 'type'];

    /**
     * Get the cds from author.
     */
    public function cds() {
        return $this->hasMany(Cd::class);
    }

    /**
     * Get the cds from author.
     */
    public function books() {
        return $this->hasMany(Book::class);
    }
}
