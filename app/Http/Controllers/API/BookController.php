<?php

namespace App\Http\Controllers\API;

use App\Author;
use App\Book;
use App\Http\Resources\AuthorBooksResource;
use App\Http\Resources\BooksResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Get top variable Authors
     * @param Request $request
     * @param         $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function topAuthors(Request $request, $limit) {
        $response = DB::table('books')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->select(DB::raw('authors.author, COUNT(books.author_id) as author_book_count'))
            ->groupBy('authors.author', 'books.author_id')
            ->orderBy('author_book_count', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json($response, 200, [],  JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get books by author
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function booksByAuthor(Request $request) {
        $request->validate([
            'author' => 'required|max:255'
        ]);

        $response = AuthorBooksResource::collection(
            Author::where('type', '=', Author::ENUM_BOOK)
                  ->where('author', 'LIKE', '%' . $request->get('author') . '%')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get books before some year
     * @param Request $request
     * @param         $before
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function beforeBooks(Request $request, $before) {
        $response = BooksResource::collection(
            Book::where('year', '<=', $before)
                ->orderBy('year', 'DESC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get books after some year
     * @param Request $request
     * @param         $after
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function afterBooks(Request $request, $after) {
        $response = BooksResource::collection(
            Book::where('year', '>=', $after)
                ->orderBy('year', 'ASC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get books between some years
     * @param Request $request
     * @param         $yearFrom
     * @param         $yearTo
     * @return \Illuminate\Http\JsonResponse
     */
    public function betweenBooks(Request $request, $yearFrom, $yearTo) {
        $response = BooksResource::collection(
            Book::whereBetween('year', [$yearFrom, $yearTo])
                ->orderBy('year', 'DESC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get average books per year
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function averagePerYear(Request $request) {
        $request->validate([
            'author' => 'max:255'
        ]);

        $response = DB::table('authors')
                      ->join('books', 'authors.id', '=', 'books.author_id')
                      ->select(DB::raw(
                          <<<'EOD'
            authors.author,
              CASE WHEN (MAX(books.year) - MIN(books.year))=0 THEN COUNT(books.isbn)
                ELSE COUNT(books.isbn) / (MAX(books.year) - MIN(books.year))::float
              END AS AVERAGE
EOD
                      ))
                      ->groupBy('authors.author')
                      ->where('type', '=', Author::ENUM_BOOK);

        if ($request->has('author')) {
            $response = $response
                ->where('authors.author', 'LIKE', '%' . $request->get('author') . '%');
        }

        return response()->json($response->get(), 200, [],  JSON_UNESCAPED_UNICODE);
    }
}
