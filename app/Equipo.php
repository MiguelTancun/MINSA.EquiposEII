<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table='EquipoEII';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'Codigo',
        'IDUsuario'
    ];

    public function scopeByFilter($query, string $type = null, string $filter = null)
    {
        if($type == '1'){
            return $query->where('Renipress.Codigo','LIKE' , "%{$filter}%")
            ->orwhere('Renipress.NombreES','LIKE' , "%{$filter}%");

        } else if($type == '2') {
            return $query->where('Usuario.Nombre','LIKE' , "%{$filter}%");
        }
        return $query;
    }

}
