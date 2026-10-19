<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrdenamientoTerritorial;
use Illuminate\Http\Request;

class OrdenamientoTerritorialController extends Controller
{
    public function index(Request $request)
    {
        $query = OrdenamientoTerritorial::where('estado', true);

        if ($request->has('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $documentos = $query->orderBy('created_at', 'desc')->get()->map(function ($doc) {
            return [
                'id'           => $doc->id,
                'titulo'       => $doc->titulo,
                'categoria'    => $doc->categoria,
                'subcategoria' => $doc->subcategoria,
                'descripcion'  => $doc->descripcion,
                'pdf_url'      => asset('storage/' . $doc->archivo_pdf),
                'fecha'        => $doc->created_at->format('d/m/Y'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $documentos,
        ], 200, [
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept',
        ]);
    }
}