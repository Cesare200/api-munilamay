<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_root_displays_the_municipal_website_homepage(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Municipalidad Distrital de Lamay');
        $response->assertSee('/assets/css/home.css', false);
        $response->assertSee('/assets/images/lamay-logo.png', false);
        $response->assertSee('/api/configuracion-institucional', false);
    }
}
