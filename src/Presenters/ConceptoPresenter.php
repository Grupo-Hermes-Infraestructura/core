<?php

namespace Ghi\Core\Presenters;

use Laracasts\Presenter\Presenter;

class ConceptoPresenter extends Presenter
{
    /**
     * Muestra la descripcion de un concepto con su clave
     *
     * @return mixed|string
     */
    public function descripcion()
    {
        if ($this->entity->clave_concepto) {
            return '['.$this->entity->clave_concepto.']'.' '.$this->entity->descripcion;
        }
        return $this->entity->descripcion;
    }
}
