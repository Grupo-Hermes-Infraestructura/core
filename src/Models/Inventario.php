<?php

namespace Ghi\Core\Models;

use Ghi\Core\Presenters\InventarioPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Inventario extends Model
{
    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'inventarios';

    /**
     * @var string
     */
    protected $primaryKey = 'id_lote';

    /**
     * Campos que seran convertidos a una instancia de Carbon
     *
     * @var array
     */
    protected $dates = ['fecha_desde', 'fecha_hasta'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $presenter = InventarioPresenter::class;

    /**
     * Material relacionado con el inventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    /**
     * Item relacionado con este lote de inventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}
