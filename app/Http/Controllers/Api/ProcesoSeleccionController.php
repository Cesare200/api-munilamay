<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProcesoSeleccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProcesoSeleccionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ProcesoSeleccion::where('status', 'publicado');

        if ($request->filled('year')) {
            $query->where('year', $request->query('year'));
        }

        if ($request->filled('filter')) {
            $query->where('filter', $request->query('filter'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('filter', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('date', 'desc')
            ->paginate($request->query('per_page', 20));

        $items->getCollection()->transform(function ($item) {
            $path = ltrim($item->file, '/');
            $item->file_url = $item->file ? Storage::disk('public')->url($path) : null;
            return $item;
        });

        return response()->json([
            'entidad' => 'MUNICIPALIDAD DISTRITAL DE LAMAY – CALCA',
            'tituloSeccion' => 'CONVOCATORIA DEL PROCESO DE SELECCIÓN',
            'data' => $items,
        ], 200);
    }
}