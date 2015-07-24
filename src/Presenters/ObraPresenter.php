<?php

namespace Ghi\Core\Presenters;

use Laracasts\Presenter\Presenter;

class ObraPresenter extends Presenter
{
    /**
     * @return mixed
     */
    public function nombrePublico()
    {
        return strlen($this->entity->nombre_publico) ? $this->entity->nombre_publico : $this->nombre;
    }

    /**
     * @return mixed
     */
    public function fechaInicial()
    {
        return $this->entity->fecha_inicial->formatLocalized('%d %B %Y');
    }

    /**
     * @return string
     */
    public function fechaFinal()
    {
        if (is_null($this->fecha_final)) {
            return 'Sin fecha';
        }

        return $this->entity->fecha_final->formatLocalized('%d %B %Y');
    }
}
