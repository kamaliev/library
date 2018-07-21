<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Book Scan
     */
    public function testBookScan() {
        $response = $this->json('POST', '/api/v1/scan', [
            'isbn' => '9785699653836',
            'author_full_name' => 'Михаил Булгаков',
            'title'=> 'Мастер и Маргарита',
            'year'=> '2013'
        ]);

        $response->assertStatus(200);

        $response = $this->json('POST', '/api/v1/scan', [
            'isbn' => '9785699653836',
            'author_full_name' => 'Михаил Булгаков',
            'title'=> 'Мастер и Маргарита',
            'year'=> '2013'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Cd scan test
     */
    public function testCdScan() {
        $response = $this->json('POST', '/api/v1/scan', [
            'isbn' => '',
            'author_full_name' => 'Би-2',
            'title'=> 'Варвара',
            'year'=> '2010'
        ]);

        $response->assertStatus(200);

        $response = $this->json('POST', '/api/v1/scan', [
            'isbn' => '',
            'author_full_name' => 'Би-2',
            'title'=> 'Варвара',
            'year'=> '2010'
        ]);

        $response->assertStatus(409);
    }
}
