<?php

use App\Http\Controllers\API\ScannerController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function() {
    Route::post('scan', 'API\ScannerController@scan');

    Route::prefix('books')->group(function() {
        Route::get('top/{limit}', 'API\BookController@topAuthors');

        Route::get('author', 'API\BookController@itemsByAuthor');

        Route::get('author/average', 'API\BookController@averagePerYear');

        Route::get('before/{before}', 'API\BookController@beforeYear')
             ->where('before', '[0-9]{4}');

        Route::get('after/{after}', 'API\BookController@afterYear')
            ->where('after', '[0-9]{4}');

        Route::get('between/{yearFrom}/{yearTo}', 'API\BookController@betweenYears')
            ->where(['yearFrom' => '[0-9]{4}', 'yearTo' => '[0-9]{4}']);
    });

    Route::prefix('cds')->group(function() {
        Route::get('top/{limit}', 'API\CdController@topAuthors');

        Route::get('author', 'API\CdController@itemsByAuthor');

        Route::get('author/average', 'API\CdController@averagePerYear');

        Route::get('before/{before}', 'API\CdController@beforeYear')
             ->where('before', '[0-9]{4}');

        Route::get('after/{after}', 'API\CdController@afterYear')
             ->where('after', '[0-9]{4}');

        Route::get('between/{yearFrom}/{yearTo}', 'API\CdController@betweenYears')
             ->where(['yearFrom' => '[0-9]{4}', 'yearTo' => '[0-9]{4}']);
    });
});
