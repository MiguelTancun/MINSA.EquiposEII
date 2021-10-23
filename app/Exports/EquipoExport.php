<?php

namespace App\Exports;

use App\Equipo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class EquipoExport implements FromQuery
{
    use Exportable;
    protected $equipos;

    public function __construct($equipos)
    {
        $this->equipos = $equipos;
    }

    public function query()
    {
        return Equipo::query()->select( 'ID as id', 
        'Renipress.Codigo as codeHospital',
        'Renipress.NombreES as hospital', 'Usuario.Nombre as personal' 
        )
        ->join('Renipress','EquipoEII.Codigo', '=', 'Renipress.Codigo')
        ->join('Usuario','EquipoEII.IDUsuario', '=', 'Usuario.IDUsuario')
        ->whereKey($this->equipos);
    }
}