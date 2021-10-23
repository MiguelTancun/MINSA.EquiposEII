<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Equipo;
use App\Exports\EquipoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\ApiResponser;


class EquipoController extends Controller
{
    use ApiResponser;

    public function getEquipo() {
        $paginate = request('paginate');
        $type = request('type');
        $search = request('search');
        $response = Equipo::byFilter($type,$search)
        ->select( 'ID as id', 
        'Renipress.Codigo as codeHospital',
        'Renipress.NombreES as hospital', 'Usuario.Nombre as personal' 
        )
        ->join('Renipress','EquipoEII.Codigo', '=', 'Renipress.Codigo')
        ->join('Usuario','EquipoEII.IDUsuario', '=', 'Usuario.IDUsuario')
        ->paginate($paginate);
    return $this->successResponse($response);
    }

    public function delete(Equipo $equipo) {
        $equipo->delete();
        return $this->successResponse('Se elimino de manera satisfactoria');
    }

    public function allDelete($equipos) {
        $equipoArray = explode(',', $equipos);
        Equipo::whereKey($equipoArray)->delete();
        return $this->successResponse('Se eliminaron los registros de manera satisfactoria');
    }

    public function export($equipos) {
        $equipoArray = explode(',', $equipos);
        return (new EquipoExport($equipoArray))->download('equipos.xlsx');
    }

    public function saveRelation(Request $request) {
        try{
        $hospital = $request->hospital;
        $usuario = $request->usuario;

        $validation = Equipo::where('Codigo', $hospital)->where('IDUsuario', $usuario)->get();
        if(count($validation) == 0){
            $newEquipo = new Equipo();
            $newEquipo->Codigo = $hospital;
            $newEquipo->IDUsuario = $usuario;
            $newEquipo->save();
            return $this->successResponse(['result'=>'success']);
        }else {
            return response()->json(['error' => 'Registros repetidos' ,'code' => 400], 400);
        }
        }catch (\Exception $e){
            return $this->successResponse(['result'=>'error', 'message'=>'error 500', 'track'=>$e->getTrace()]);
        }
     
    }
}