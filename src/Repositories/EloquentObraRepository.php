<?php

namespace Ghi\Core\Repositories;

use Ghi\Core\Models\Obra;
use Ghi\Core\Contracts\ObraRepository;

class EloquentObraRepository extends BaseRepository implements ObraRepository
{
    /**
     * Obtiene una obra por su id
     *
     * @param $id
     * @return Obra
     */
    public function getById($id)
    {
        return Obra::where('id_obra', $id)->firstOrFail();
    }
}
