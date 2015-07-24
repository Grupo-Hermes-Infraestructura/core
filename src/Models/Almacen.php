<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    const TIPO_MATERIALES   = 0;
    const TIPO_MAQUINARIA   = 1;
    const TIPO_MAQUINARIA_CONTROL_INSUMOS = 2;
    const TIPO_MANO_OBRA    = 3;
    const TIPO_SERVICIOS    = 4;
    const TIPO_HERRAMIENTAS = 5;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'almacenes';

    /**
     * @var string
     */
    protected $primaryKey = 'id_almacen';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Obra relacionada con este almacen
     *
     * @return mixed
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }
}
