<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Centro;
use App\Traits\ApiResponser;


class CentroController extends Controller
{
    use ApiResponser;

    public function getCentro() {
        
        $response = Centro::select( 'Codigo as code',
        'NombreES as label'
        )
        ->get();

        return $this->successResponse($response);
    }
}