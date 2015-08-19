<?php

namespace Ghi\Core\Models;

use Ghi\Core\Presenters\ConceptoPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Concepto extends Model
{
    use PresentableTrait;

    const FACTURABLE = 1;
    const MEDIBLE    = 3;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'conceptos';

    /**
     * @var string
     */
    protected $primaryKey = 'id_concepto';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

    /**
     * @var string
     */
    protected $presenter = ConceptoPresenter::class;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $casts = [
        'id_concepto' => 'integer',
        'activo' => 'boolean',
    ];

    /**
     * Cronogramas relacionados con este concepto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cronogramas()
    {
        return $this->hasMany(Cronograma::class, 'id_concepto', 'id_concepto');
    }

    /**
     * Programas relacionados con este concepto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programas()
    {
        return $this->hasMany(Programa::class, 'id_concepto', 'id_concepto');
    }

    /**
     * Cantidad pendiente a programar de acuerdo a lo presupuestado y programado de este concepto
     *
     * @return mixed
     */
    public function cantidadPendientePrograma()
    {
        $cantidad_programada = $this->cronogramas()->sum('cantidad');

        return $this->cantidad_presupuestada - $cantidad_programada;
    }

    /**
     * Indica si un concepto es una actividad (medible o facturable)
     *
     * @return bool
     */
    public function esActividad()
    {
        if ($this->concepto_medible == static::MEDIBLE ||
            $this->concepto_medible == static::FACTURABLE) {
            return true;
        }
        return false;
    }

    /**
     * Obtiene el numero de profundidad en la jerarquia de este concepto
     *
     * @return float
     */
    public function getNumeroNivel()
    {
        return mb_strlen($this->nivel) / 4;
    }

    /**
     * Devuelve el nivel que corresponde al ancestro de este concepto
     *
     * @return string
     */
    public function getNivelAncestro()
    {
        return substr($this->nivel, 0, strlen($this->nivel) - 4);
    }

    /**
     * Indica si este concepto es un material
     *
     * @return bool
     */
    public function esMaterial()
    {
        if ($this->id_material) {
            return true;
        }
        return false;
    }

    /**
     * Indica si este concepto tiene descendientes
     *
     * @return bool
     */
    public function tieneDescendientes()
    {
        return static::where('id_obra', $this->id_obra)
            ->where('nivel', '<>', $this->nivel)
            ->whereRaw("LEFT(nivel, LEN('{$this->nivel}')) = '{$this->nivel}'")
            ->exists();
    }
}
