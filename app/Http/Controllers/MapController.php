<?php

namespace App\Http\Controllers;

use App\Models\LayerType;
use App\Models\Operation;
use Barryvdh\DomPDF\Facade\Pdf;

class MapController extends Controller
{
    public function index()
    {
        return view('map');
    }

    public function report($operationId)
    {
        $operation = Operation::findByProfile($operationId);
        if (is_null($operation)) abort(404);
        $layers = $operation->layers->whereIn('layer_type_id', [LayerType::IMAGE, LayerType::THERMO])->sortBy('created_at');
        $pdf = Pdf::loadView('report', compact('operation', 'layers'));
        return $pdf->download($operation->name . ' - Report.pdf');
    }
}
