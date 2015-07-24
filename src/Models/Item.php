<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var string
     */
    protected $primaryKey = 'id_item';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Transaccion relacionada con este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaccion()
    {
        return $this->belongsTo(Transaccion::class, 'id_transaccion', 'id_transaccion');
    }

    /**
     * Item antecedente de este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemAntecedente()
    {
        return $this->belongsTo(Item::class, 'item_antecedente', 'id_item');
    }

    /**
     * Concepto relacionado con este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto', 'id_concepto');
    }

    /**
     * Material relacionado con este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material');
    }
}
