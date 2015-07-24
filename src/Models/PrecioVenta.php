<?php

namespace Ghi\Core\Models; 

use Illuminate\Database\Eloquent\Model;

class PrecioVenta extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'PresupuestoObra.precios_venta';

    /**
     * @var array
     */
    protected $fillable = ['precio_produccion', 'precio_estimacion'];
}
