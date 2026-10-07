<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Decreto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DecretoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Decreto::where('status', true);

        if ($request->filled('anio')) {
            $query->where('anio', $request->query('anio'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where('numero', 'like', "%{$search}%");
        }

        $decretos = $query->orderBy('fecha', 'desc')
            ->paginate($request->query('per_page', 15));

        $decretos->getCollection()->transform(function ($decreto) {
            $decreto->pdf_url = $decreto->pdf ? Storage::disk('public')->url($decreto->pdf) : null;
            return $decreto;
        });

        return response()->json([
            'status' => 'success',
            'data' => $decretos,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $decreto = Decreto::where('status', true)->find($id);

        if (! $decreto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Decreto no encontrado o inactivo.',
            ], 404);
        }

        $decreto->pdf_url = $decreto->pdf ? Storage::disk('public')->url($decreto->pdf) : null;

        return response()->json([
            'status' => 'success',
            'data' => $decreto,
        ], 200);
    }
}