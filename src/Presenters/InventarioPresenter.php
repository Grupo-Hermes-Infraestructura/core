<?php

namespace Ghi\Core\Presenters;

use Laracasts\Presenter\Presenter;

class InventarioPresenter extends Presenter
{
    /**
     * Presenta la fecha de entrada en formato dd-mm-aaaa
     *
     * @return mixed
     */
    public function fechaEntrada()
    {
        if (is_null($this->fecha_desde)) {
            return '';
        }

        return $this->entity->fecha_desde->format('d-m-Y');
    }

    /**
     * Presenta la fecha de salida en formato dd-mm-aaaa
     *
     * @return mixed
     */
    public function fechaSalida()
    {
        if (is_null($this->fecha_hasta)) {
            return '';
        }

        return $this->entity->fecha_hasta->format('d-m-Y');
    }
}
