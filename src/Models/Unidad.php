<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'unidades';

    /**
     * @var string
     */
    protected $primaryKey = 'unidad';

    /**
     * @var array
     */
    protected $fillable = ['unidad', 'descripcion'];

    /**
     * @var bool
     */
    public $timestamps = false;
    
    /**
    * @var bool
    */
    public $incrementing = false;
}
