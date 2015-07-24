<?php

namespace Ghi\Core\Presenters;

use Ghi\Core\Models\Empresa;
use Laracasts\Presenter\Presenter;

class EmpresaPresenter extends Presenter
{
    /**
     * @return string
     */
    public function tipo()
    {
        if ($this->tipo_empresa == Empresa::TIPO_PROVEEDOR) {
            return 'Proveedor de Materiales';
        }

        if ($this->tipo_empresa == Empresa::TIPO_CONTRATISTA) {
            return 'Contratista';
        }

        if ($this->tipo_empresa == Empresa::TIPO_DESTAJISTA) {
            return 'Destajista';
        }

        if ($this->tipo_empresa == Empresa::TIPO_BANCO) {
            return 'Banco';
        }

        if ($this->tipo_empresa == Empresa::TIPO_CLIENTE) {
            return 'Cliente';
        }
    }
}
