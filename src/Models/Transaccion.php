<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    const TIPO_ENTRADA_EQUIPO     = 33;
    const OPCIONES_ENTRADA_EQUIPO = 8;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'transacciones';

    /**
     * @var string
     */
    protected $primaryKey = 'id_transaccion';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Obtiene las transacciones que son entradas de equipo
     *
     * @param $query
     * @return mixed
     */
    public function scopeEntradaEquipo($query)
    {
        return $query->where('tipo_transaccion', static::TIPO_ENTRADA_EQUIPO)
            ->where('opciones', static::OPCIONES_ENTRADA_EQUIPO);
    }

    /**
     * Items relacionados con esta transaccion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'id_transaccion', 'id_transaccion');
    }

    /**
     * Empresa relacionada con esta transaccion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }
}
