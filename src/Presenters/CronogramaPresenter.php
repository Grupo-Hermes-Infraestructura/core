<?php

namespace Ghi\Core\Presenters;

use Laracasts\Presenter\Presenter;

class CronogramaPresenter extends Presenter
{
    /**
     * @return mixed
     */
    public function fecha_desde()
    {
        return $this->entity->fecha_desde->format('d-m-Y');
    }

    /**
     * @return mixed
     */
    public function fecha_hasta()
    {
        return $this->entity->fecha_hasta->format('d-m-Y');
    }
}
