<?php

namespace Ghi\Core\Models;

use Ghi\Core\Presenters\DiaNoLaborablePresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class DiaNoLaborable extends Model
{
    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'PresupuestoObra.dias_no_laborables';

    /**
     * @var array
     */
    protected $fillable = [
        'fecha',
        'descripcion',
    ];

    /**
     * @var array
     */
    protected $dates = ['fecha'];

    /**
     * @var
     */
    protected $presenter = DiaNoLaborablePresenter::class;
}
