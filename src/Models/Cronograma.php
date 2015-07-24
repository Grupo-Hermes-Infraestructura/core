<?php

namespace Ghi\Core\Models;

use Ghi\Core\Presenters\CronogramaPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Cronograma extends Model
{
    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $primaryKey = 'id_cronograma';

    /**
     * @var string
     */
    protected $table = 'cronogramas';

    /**
     * @var array
     */
    protected $fillable = ['fecha_desde', 'fecha_hasta', 'cantidad', 'avance',];

    /**
     * @var array
     */
    protected $dates = ['fecha_desde', 'fecha_hasta'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var
     */
    protected $presenter = CronogramaPresenter::class;
}
