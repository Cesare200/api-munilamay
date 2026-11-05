<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicStorageUrlTest extends TestCase
{
    public function test_public_storage_urls_are_relative_to_the_current_domain(): void
    {
        config(['app.url' => 'https://api.munilamay.gob.pe']);
        Storage::forgetDisk('public');

        $url = Storage::disk('public')->url('configuracion/documento.pdf');

        $this->assertSame('/storage/configuracion/documento.pdf', $url);
    }
}
