<?php

namespace Ghi\Core\Models;

use Ghi\Core\Presenters\EmpresaPresenter;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use PresentableTrait;

    const TIPO_PROVEEDOR   = 1;
    const TIPO_CONTRATISTA = 3;
    const TIPO_DESTAJISTA  = 4;
    const TIPO_BANCO       = 8;
    const TIPO_CLIENTE     = 16;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'empresas';

    /**
     * @var string
     */
    protected $primaryKey = 'id_empresa';

    /**
     * @var array
     */
    protected $fillable = ['razon_social', 'rfc'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var
     */
    protected $presenter = EmpresaPresenter::class;

    /**
     * Entradas de equipo de esta empresa
     *
     * @return mixed
     */
    public function entradasEquipo()
    {
        return $this->hasMany(Transaccion::class, 'id_empresa', 'id_empresa')
            ->where('tipo_transaccion', Transaccion::TIPO_ENTRADA_EQUIPO)
            ->where('opciones', Transaccion::OPCIONES_ENTRADA_EQUIPO);
    }
}
