<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    const TIPO_MATERIALES            = 1;
    const TIPO_MANO_OBRA_Y_SERVICIOS = 2;
    const TIPO_HERRAMIENTA_Y_EQUIPO  = 4;
    const TIPO_MAQUINARIA            = 8;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'materiales';

    /**
     * @var string
     */
    protected $primaryKey = 'id_material';

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'tipo_material'];

    /**
     * @var bool
     */
    public $timestamps = false;
}
