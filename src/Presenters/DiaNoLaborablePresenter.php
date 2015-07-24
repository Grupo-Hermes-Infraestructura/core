<?php

namespace Ghi\Core\Presenters;

use Laracasts\Presenter\Presenter;

class DiaNoLaborablePresenter extends Presenter
{
    /**
     * Devuelve la fecha en formato d-m-a
     *
     * @return mixed
     */
    public function fecha()
    {
        return $this->entity->fecha->format('d-m-Y');
    }
}
