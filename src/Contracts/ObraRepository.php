<?php

namespace Ghi\Core\Contracts;

use Ghi\Core\Models\Obra;

interface ObraRepository
{
    /**
     * Obtiene una obra por su id
     *
     * @param $id
     * @return Obra
     */
    public function getById($id);
}
