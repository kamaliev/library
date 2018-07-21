<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Interface ApiInterface
 */
interface ApiInterface {
    /**
    * Get top variable Authors
    * @param Request $request
    * @param string  $limit
    * @return mixed
    */
    public function topAuthors(Request $request, string $limit);

    /**
     * Get items by author
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemsByAuthor(Request $request);

    /**
     * Get item before some year
     * @param Request $request
     * @param         $before
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function beforeYear(Request $request, $before);

    /**
     * Get item after some year
     * @param Request $request
     * @param         $after
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function afterYear(Request $request, $after);

    /**
     * Get books between some years
     * @param Request $request
     * @param         $yearFrom
     * @param         $yearTo
     * @return \Illuminate\Http\JsonResponse
     */
    public function betweenYears(Request $request, string $yearFrom, string $yearTo);

    /**
     * Get average books per year
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function averagePerYear(Request $request);
}