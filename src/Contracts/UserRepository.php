<?php

namespace Ghi\Core\Contracts;

use Ghi\Core\Models\UsuarioCadeco;
use Ghi\Core\Models\Obra;

interface UserRepository
{
    /**
     * Obtiene un usuario por su id
     *
     * @param $id
     * @return User
     */
    public function getById($id);

    /**
     * Obtiene un usuario por su nombre de usuario
     *
     * @param $nombre
     * @return User
     */
    public function getByNombreUsuario($nombre);

    /**
     * Obtiene el usuario cadeco asociado al usuario de intranet
     *
     * @param $id_usuario
     * @return UsuarioCadeco
     */
    public function getUsuarioCadeco($id_usuario);

    /**
     * Obtiene las obras de un usuario cadeco
     *
     * @param $id_usuario
     * @return \Illuminate\Database\Eloquent\Collection|Obra
     */
    public function getObras($id_usuario);
}
