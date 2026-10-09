<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InteraccionMercado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InteraccionMercadoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = InteraccionMercado::where('status', 'publicado');

        if ($request->filled('anio')) {
            $query->where('anio', $request->query('anio'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('fecha', 'desc')
            ->paginate($request->query('per_page', 15));

        $items->getCollection()->transform(function ($item) {
            $path = ltrim($item->pdf, '/');
            $item->pdf_url = $item->pdf ? Storage::disk('public')->url($path) : null;
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $item = InteraccionMercado::where('status', 'publicado')->find($id);

        if (! $item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Documento no encontrado o no disponible.',
            ], 404);
        }

        $path = ltrim($item->pdf, '/');
        $item->pdf_url = $item->pdf ? Storage::disk('public')->url($path) : null;

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 200);
    }
}