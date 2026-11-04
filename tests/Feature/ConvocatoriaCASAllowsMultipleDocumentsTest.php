<?php

namespace Tests\Feature;

use App\Models\ConvocatoriaCAS;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ConvocatoriaCASAllowsMultipleDocumentsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_multiple_documents_can_share_the_same_process_code(): void
    {
        ConvocatoriaCAS::query()->create([
            'codigo_cas' => '004-2026',
            'proceso' => 'CAS N°004-2026-MDL',
            'anio' => 2026,
            'fecha' => '2026-11-04',
            'tipo' => 'Bases',
            'titulo' => 'Especialista en Abastecimiento',
            'pdf' => 'convocatorias-cas/bases.pdf',
            'status' => 'Publicado',
        ]);

        ConvocatoriaCAS::query()->create([
            'codigo_cas' => '004-2026',
            'proceso' => 'CAS N°004-2026-MDL',
            'anio' => 2026,
            'fecha' => '2026-11-05',
            'tipo' => 'Resultados Preliminares',
            'titulo' => 'Especialista en Abastecimiento',
            'pdf' => 'convocatorias-cas/resultados-preliminares.pdf',
            'status' => 'Nuevo',
        ]);

        $this->assertDatabaseCount('convocatorias__c_a_s', 2);
    }
}
