<?php

namespace App\Http\Controllers\API;

use App\Author;
use App\Http\Controllers\AbstractApiController;
use App\Http\Resources\AuthorCdsResource;
use App\Http\Resources\CdsResource;

class CdController extends AbstractApiController {
    /**
     * Init
     * CdController constructor.
     */
    public function __construct() {
        $this->initialCongiguration(
            'cds',
            AuthorCdsResource::class,
            CdsResource::class,
            Cd::class,
            Author::ENUM_CD);
    }
}
