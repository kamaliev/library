<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cd extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['author_id', 'title', 'year'];

    /**
     * Get book author
     */
    public function author() {
        return $this->belongsTo(Author::class);
    }
}
