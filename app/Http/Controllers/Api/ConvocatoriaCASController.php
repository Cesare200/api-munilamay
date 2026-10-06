<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConvocatoriaCAS;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConvocatoriaCASController extends Controller
{
    // ESTA ES LA FUNCIÓN 'index' QUE TE FALTA Y QUE LA RUTA BUSCA
    public function index(Request $request): JsonResponse
    {
        $query = ConvocatoriaCAS::query();

        // Filtro por Año (?anio=2026)
        if ($request->has('anio') && !empty($request->anio)) {
            $query->where('anio', $request->anio);
        }

        // Buscador por Código o Proceso (?buscar=001)
        if ($request->has('buscar') && !empty($request->buscar)) {
            $query->where('codigo_cas', 'like', '%' . $request->buscar . '%')
                  ->orWhere('proceso', 'like', '%' . $request->buscar . '%');
        }

        $convocatorias = $query->orderBy('fecha', 'desc')->get();

        $resultado = $convocatorias->map(function ($convocatoria) {
            $fechaPublicacion = Carbon::parse($convocatoria->fecha);
            $diasTranscurridos = $fechaPublicacion->diffInDays(Carbon::now());
            
            // Lógica de estados por tiempo
            $estadoDinamico = $convocatoria->status;
            if ($estadoDinamico !== 'Finalizado') {
                if ($diasTranscurridos >= 2) {
                    $estadoDinamico = 'Publicado';
                } else {
                    $estadoDinamico = 'Nuevo';
                }
            }

            return [
                'id' => $convocatoria->id,
                'codigo_cas' => $convocatoria->codigo_cas,
                'proceso' => $convocatoria->proceso,
                'anio' => $convocatoria->anio,
                'fecha' => $convocatoria->fecha,
                'tipo' => $convocatoria->tipo,
                'titulo' => $convocatoria->titulo,
                'pdf_url' => asset('storage/' . $convocatoria->pdf),
                'status' => $estadoDinamico,
                'dias_activo' => $diasTranscurridos
            ];
        });

        return response()->json($resultado, 200);
    }
}
