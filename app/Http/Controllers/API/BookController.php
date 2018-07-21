<?php

namespace App\Http\Controllers\API;

use App\Author;
use App\Book;
use App\Http\Controllers\AbstractApiController;
use App\Http\Resources\AuthorBooksResource;
use App\Http\Resources\BooksResource;

class BookController extends AbstractApiController {
    /**
     * Init
     * BookController constructor.
     */
    public function __construct() {
        $this->initialCongiguration(
            'books',
            AuthorBooksResource::class,
            BooksResource::class,
            Book::class,
            Author::ENUM_BOOK);
    }
}
