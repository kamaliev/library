<?php

namespace App\Http\Controllers\API;

use App\Author;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScannerController extends Controller {
    /**
     * Author model
     * @var Author
     */
    private $author;

    /**
     * Enum Auth model type
     * @var string
     */
    private $type;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function scan(Request $request) {

        if (!$this->checkValidation($request, $errors)) {
            return response()->json($errors, 422);
        }

        if(empty($request->get('isbn'))) {
            return $this
                ->create($request, Author::ENUM_CD)
                ->saveItems($request);
        } else {
            return $this
                ->create($request, Author::ENUM_BOOK)
                ->saveItems($request);
        }
    }

    /**
     * Ger rules for scanner
     * @return array
     */
    private function rules() {
        return [
            'isbn' => 'unique:books',
            'author_full_name' => 'required',
            'title' => 'required',
            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
        ];
    }

    /**
     * Check validation
     * @param Request $request
     * @param array   $errors
     * @return bool
     */
    private function checkValidation(Request $request, &$errors = []) {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();

            Log::channel('scanner')
               ->error('Validation error', [
                   'params' => $request->toArray(),
                   'messages' => $errors
               ]);

            return false;
        }

        return true;
    }

    /**
     * Create author item
     * @param Request $request
     * @param string  $type
     * @return $this
     */
    private function create(Request $request, string $type) {
        $this->type = $type;

        $this->author = Author::firstOrCreate([
            'author' => $request->get('author_full_name'),
            'type' => $this->type
        ]);

        return $this;
    }

    /**
     * Save item by author
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function saveItems(Request $request) {
        switch($this->type) {
            case Author::ENUM_CD: {
                try {
                    $this->author->cds()->create($request->all());

                    return $this->response($request);
                } catch(QueryException $exception) {
                    Log::channel('scanner')
                       ->error('Validation error', [
                           'params' => $request->toArray(),
                           'messages' => 'Data had duplicate'
                       ]);

                    return response()->json([
                        'message' => 'Data has duplicate'
                    ], 409);
                }
            }
            case Author::ENUM_BOOK: {
                $this->author->books()->create($request->all());

                return $this->response($request);
            }
        }
    }

    /**
     * Send success response
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function response(Request $request) {
        Log::channel('scanner')
           ->info('Data has been saved', [
               'params' => $request->toArray()
           ]);

        return response()->json([
            'message' => 'Data type [' . $this->type . '] has been saved'
        ]);
    }
}
