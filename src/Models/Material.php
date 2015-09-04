<?php

namespace Ghi\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    const TIPO_MATERIALES            = 1;
    const TIPO_MANO_OBRA_Y_SERVICIOS = 2;
    const TIPO_HERRAMIENTA_Y_EQUIPO  = 4;
    const TIPO_MAQUINARIA            = 8;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'materiales';

    /**
     * @var string
     */
    protected $primaryKey = 'id_material';

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'tipo_material'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Directorio base de almacenamiento de la ficha tecnica
     * 
     * @var string
     */
    protected $directorioBase = '/articulo/fichas';

    /**
     * Lotes de inventario relacionados con este material
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_material', 'id_material');
    }

    /**
     * Clasificador al que pertenece este articulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clasificador()
    {
        return $this->belongsTo(Clasificador::class);
    }

    /**
     * Fotos que tiene este articulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }

    /**
     * Asocia este articulo con un clasificador
     *
     * @param Clasificador $clasificador
     * @return Articulo
     */
    public function asignaClasificador(Clasificador $clasificador)
    {
        return $this->clasificador()->associate($clasificador);
    }

    /**
     * Asocia este articulo con una unidad
     *
     * @param Unidad $unidad
     * @return Articulo
     */
    public function asignaUnidad(Unidad $unidad)
    {
        return $this->unidad = $unidad->codigo;
    }

    /**
     * Almacena la ficha tecnica a este articulo
     *
     * @param UploadedFile $file
     */
    public function agregaFichaTecnica(UploadedFile $file)
    {
        $this->ficha_tecnica_nombre = sprintf("%s-%s", time(), $file->getClientOriginalName());
        $this->ficha_tecnica_path = sprintf("%s/%s", $this->directorioBase, $this->ficha_tecnica_nombre);
        $file->move(public_path() . $this->directorioBase, $this->ficha_tecnica_nombre);
    }

    /**
     * Agrega una foto a este articulo
     *
     * @param Foto $foto
     * @return Articulo
     */
    public function agregaFoto(Foto $foto)
    {
        return $this->fotos()->save($foto);
    }
}
