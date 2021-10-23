<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{

    protected $table='Renipress';
    protected $fillable = [
        'Codigo',
        'NombreEs',
        'Estado'
    ];

}
