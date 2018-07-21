<?php

namespace App\Http\Controllers\API;

use App\Author;
use App\Cd;
use App\Http\Resources\AuthorCdsResource;
use App\Http\Resources\CdsResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CdController extends Controller {
    /**
     * Get top variable Authors
     * @param Request $request
     * @param         $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function topAuthors(Request $request, $limit) {
        $response = DB::table('cds')
                      ->join('authors', 'cds.author_id', '=', 'authors.id')
                      ->select(DB::raw('authors.author, COUNT(cds.author_id) as author_cd_count'))
                      ->groupBy('authors.author', 'cds.author_id')
                      ->orderBy('author_cd_count', 'DESC')
                      ->limit($limit)
                      ->get();

        return response()->json($response, 200, [],  JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get cds by author
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cdsByAuthor(Request $request) {
        $request->validate([
            'author' => 'required|max:255'
        ]);

        $response = AuthorCdsResource::collection(
            Author::where('type', '=', Author::ENUM_CD)
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
    public function beforeCds(Request $request, $before) {
        $response = CdsResource::collection(
            Cd::where('year', '<=', $before)
                ->orderBy('year', 'DESC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get cds after some year
     * @param Request $request
     * @param         $after
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function afterCds(Request $request, $after) {
        $response = CdsResource::collection(
            Cd::where('year', '>=', $after)
                ->orderBy('year', 'ASC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get cds between some years
     * @param Request $request
     * @param         $yearFrom
     * @param         $yearTo
     * @return \Illuminate\Http\JsonResponse
     */
    public function betweenCds(Request $request, $yearFrom, $yearTo) {
        $response = CdsResource::collection(
            Cd::whereBetween('year', [$yearFrom, $yearTo])
                ->orderBy('year', 'DESC')->get()
        );

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get average cds per year
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function averagePerYear(Request $request) {
        $request->validate([
            'author' => 'max:255'
        ]);

        $response = DB::table('authors')
                      ->join('cds', 'authors.id', '=', 'cds.author_id')
                      ->select(DB::raw(
                          <<<'EOD'
            authors.author,
              CASE WHEN (MAX(cds.year) - MIN(cds.year))=0 THEN COUNT(cds.id)
                ELSE COUNT(cds.id) / (MAX(cds.year) - MIN(cds.year))::float
              END AS AVERAGE
EOD
                      ))
                      ->groupBy('authors.author')
                      ->where('type', '=', Author::ENUM_CD);

        if ($request->has('author')) {
            $response = $response
                ->where('authors.author', 'LIKE', '%' . $request->get('author') . '%');
        }

        return response()->json($response->get(), 200, [],  JSON_UNESCAPED_UNICODE);
    }
}
