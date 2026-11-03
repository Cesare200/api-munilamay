<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CorsMiddlewareTest extends TestCase
{
    public function test_file_responses_include_cors_headers(): void
    {
        Route::get('/test-file-response', fn () => response()->file(base_path('tests/Feature/CorsMiddlewareTest.php')));

        $response = $this->get('/test-file-response');

        $response->assertOk();
        $response->assertHeader('Access-Control-Allow-Origin', '*');
        $response->assertHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->assertHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    }
}
