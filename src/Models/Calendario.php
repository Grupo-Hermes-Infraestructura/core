<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'PresupuestoObra.calendarios';

    /**
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo'
    ];
}
