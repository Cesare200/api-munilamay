<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstrumentoGestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstrumentoGestionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = InstrumentoGestion::where('status', 'Publicado');

        if ($request->filled('tipo')) {
            $query->where('tipo', strtoupper($request->query('tipo')));
        }

        if ($request->filled('anio')) {
            $query->where('anio', $request->query('anio'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('aprobado_por', 'like', "%{$search}%");
            });
        }

        $instrumentos = $query->orderBy('anio', 'desc')
            ->orderBy('fecha', 'desc')
            ->paginate($request->query('per_page', 15));

        $instrumentos->getCollection()->transform(function ($item) {
            $path = ltrim($item->pdf, '/');
            $item->pdf_url = $item->pdf ? Storage::disk('public')->url($path) : null;
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $instrumentos,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $instrumento = InstrumentoGestion::where('status', 'Publicado')->find($id);

        if (! $instrumento) {
            return response()->json([
                'status' => 'error',
                'message' => 'Instrumento de gestión no encontrado.',
            ], 404);
        }

        $path = ltrim($instrumento->pdf, '/');
        $instrumento->pdf_url = $instrumento->pdf ? Storage::disk('public')->url($path) : null;

        return response()->json([
            'status' => 'success',
            'data' => $instrumento,
        ], 200);
    }
}