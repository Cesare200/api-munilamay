<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocInteres;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocInteresController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DocInteres::where('status', 'publicado');

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('anio')) {
            $query->whereYear('fecha', $request->query('anio'));
        }

        $docs = $query->orderBy('fecha', 'desc')
            ->paginate($request->query('per_page', 15));

        $docs->getCollection()->transform(function ($doc) {
            $path = ltrim($doc->pdf, '/');
            $doc->pdf_url = $doc->pdf ? Storage::disk('public')->url($path) : null;
            return $doc;
        });

        return response()->json([
            'status' => 'success',
            'data' => $docs,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $doc = DocInteres::where('status', 'publicado')->find($id);

        if (! $doc) {
            return response()->json([
                'status' => 'error',
                'message' => 'Documento de interés no encontrado o no disponible.',
            ], 404);
        }

        $path = ltrim($doc->pdf, '/');
        $doc->pdf_url = $doc->pdf ? Storage::disk('public')->url($path) : null;

        return response()->json([
            'status' => 'success',
            'data' => $doc,
        ], 200);
    }
}