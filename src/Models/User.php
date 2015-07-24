<?php

namespace Ghi\Core\Models;

use Ghi\Core\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usuario';

    /**
     * @var string
     */
    protected $primaryKey = 'idusuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'correo', 'clave'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['clave', 'remember_token'];

    /**
     * Consider updated_at and created_at timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Presentador de usuario
     *
     * @var
     */
    protected $presenter = UserPresenter::class;

    /**
     * Usuario cadeco relacionado con este usuario de intranet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usuarioCadeco()
    {
        return $this->hasOne(UsuarioCadeco::class, 'usuario', 'usuario');
    }
}
