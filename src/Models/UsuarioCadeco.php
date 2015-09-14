<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioCadeco extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * @var string
     */
    protected $primaryKey = 'usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'usuario', 'id_obra'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['firma', 'clave', 'permisos'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Obras asociadas con este usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function obras()
    {
        return $this->belongsToMany(Obra::class, 'usuarios_obras', 'usuario', 'id_obra');
    }

    /**
     * Indica si el usuario tiene acceso a todas las obras
     *
     * @return bool
     */
    public function tieneAccesoATodasLasObras()
    {
        return is_null($this->id_obra);
    }

    /**
     * Crea un nuevo usuario a partir de un usuario de la intranet
     * 
     * @param  User   $user Un usuario de la intranet
     * @return self
     */
    public static function fromUsuarioIntranet(User $user)
    {
        $usuario = new static([
            'usuario' => $user->usuario,
            'clave'   => 'K3.ceUttGjUGvXfcU2ulG',   //123456
            'id_obra' => 1,
            'nombre'  => $user->present()->nombreCompleto,
        ]);

        return $usuario;
    }
}
