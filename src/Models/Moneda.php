<?php

namespace Ghi\Core\Models; 

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'monedas';

    /**
     * @var string
     */
    protected $primaryKey = 'id_moneda';

    /**
     * @var bool
     */
    public $timestamps = false;
}
