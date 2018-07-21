<?php
namespace App\Http\Controllers;


use App\Author;
use App\Book;
use App\Cd;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CdController;
use App\Http\Resources\AuthorBooksResource;
use App\Http\Resources\AuthorCdsResource;
use App\Http\Resources\BooksResource;
use App\Http\Resources\CdsResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/**
 * Abstract Controller For Book and Cd
 * Class AbstractApiController
 * @package App\Http\Controllers
 */
class AbstractApiController extends Controller implements ApiInterface {
    /**
     * For table name
     * @var string
     */
    private $table;

    /**
     * For Resources
     * @var JsonResource
     */
    private $resource;

    /** For author Resource
     * @var JsonResource
     */
    private $authorResource;

    /**
     * For Models
     * @var Model
     */
    private $model;

    /**
     * For ENUM Author type
     * @var string
     */
    private $enum;

    /**
     * Chek who called construct
     * AbstractApiController constructor.
     */
    public function __construct() {
        switch(get_called_class()) {
            case CdController::class : {
                $this->table = 'cds';
                $this->authorResource = AuthorCdsResource::class;
                $this->resource = CdsResource::class;
                $this->model = Cd::class;
                $this->enum = Author::ENUM_CD;

                break;
            }
            case BookController::class : {
                $this->table = 'books';
                $this->authorResource = AuthorBooksResource::class;
                $this->resource = BooksResource::class;
                $this->model = Book::class;
                $this->enum = Author::ENUM_BOOK;

                break;
            }
        }
    }

    /**
     * Get top variable Authors
     * @param Request $request
     * @param         $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function topAuthors(Request $request, string $limit) {
        $response = DB::table($this->table)
                      ->join('authors', $this->table . '.author_id', '=', 'authors.id')
                      ->select(DB::raw('authors.author, COUNT(' . $this->table . '.author_id) as author_count'))
                      ->groupBy('authors.author', $this->table . '.author_id')
                      ->orderBy('author_count', 'DESC')
                      ->limit($limit)
                      ->get();

        return response()->json($response, 200, [],  JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get items by author
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemsByAuthor(Request $request) {
        $request->validate([
            'author' => 'required|max:255'
        ]);

        $response = $this->authorResource::collection(
            Author::where('type', '=', $this->enum)
                  ->where('author', 'LIKE', '%' . $request->get('author') . '%')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get items before some year
     * @param Request $request
     * @param         $before
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function beforeYear(Request $request, $before) {
        $response = $this->resource::collection(
            $this->model::where('year', '<=', $before)
              ->orderBy('year', 'DESC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get items after some year
     * @param Request $request
     * @param         $after
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function afterYear(Request $request, $after) {
        $response = $this->resource::collection(
            $this->model::where('year', '>=', $after)
              ->orderBy('year', 'ASC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get items between some years
     * @param Request $request
     * @param         $yearFrom
     * @param         $yearTo
     * @return \Illuminate\Http\JsonResponse
     */
    public function betweenYears(Request $request, string $yearFrom, string $yearTo) {
        $response = $this->resource::collection(
            $this->model::whereBetween('year', [$yearFrom, $yearTo])
              ->orderBy('year', 'DESC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get average items per year
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function averagePerYear(Request $request) {
        $request->validate([
            'author' => 'max:255'
        ]);

        $selectRow = null;

        if (get_called_class() == BookController::class) {
            $selectRow = DB::raw('                        
                        authors.author,
              CASE WHEN (MAX(books.year) + 1 - MIN(books.year))=0 THEN COUNT(books.isbn)
                ELSE COUNT(books.isbn) / (MAX(books.year) + 1 - MIN(books.year))::float
              END AS AVERAGE');
        } else {
            $selectRow = DB::raw('
                authors.author,
                  CASE WHEN (MAX(cds.year) + 1 - MIN(cds.year))=0 THEN COUNT(cds.id)
                    ELSE COUNT(cds.id) / (MAX(cds.year) + 1 - MIN(cds.year))::float
                  END AS AVERAGE');
        }

        $response = DB::table('authors')
                      ->join($this->table, 'authors.id', '=', $this->table . '.author_id')
                      ->select($selectRow)
                      ->groupBy('authors.author')
                      ->where('type', '=', $this->enum);

        if ($request->has('author')) {
            $response = $response
                ->where('authors.author', 'LIKE', '%' . $request->get('author') . '%');
        }

        return response()->json($response->get(), 200, [],  JSON_UNESCAPED_UNICODE);
    }
}