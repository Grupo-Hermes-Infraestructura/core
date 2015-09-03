<?php

namespace Ghi\Core\Repositories;

use Ghi\Core\Models\Concepto;
use Ghi\Core\Contracts\Context;
use Ghi\Core\Contracts\ConceptoRepository;
use Ghi\Core\Support\NivelParser;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Collection;

class EloquentConceptoRepository extends BaseRepository implements ConceptoRepository
{
    /**
     * @var NivelParser
     */
    private $nivelParser;

    /**
     * @var array
     */
    private $filterFields = ['concepto_medible', 'clave'];

    public function __construct(Context $context, Repository $config, NivelParser $nivelParser)
    {
        parent::__construct($context, $config);

        $this->nivelParser = $nivelParser;
    }

    /**
     * Obtiene un concepto por su id
     *
     * @param $id
     * @return Concepto
     */
    public function getById($id)
    {
        return Concepto::findOrFail($id);
    }

    /**
     * Obtiene todos los conceptos de una obra
     *
     * @return Collection|Concepto
     */
    public function getAll()
    {
        return Concepto::where('id_obra', $this->context->getId())
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Obtiene los conceptos raiz del presupuesto de obra
     *
     * @return Collection|Concepto
     */
    public function getNivelesRaiz()
    {
        return Concepto::where('id_obra', $this->context->getId())
           ->whereRaw('LEN(nivel) = 4')
           ->orderBy('nivel')
           ->get();
    }

    /**
     * Obtiene los descendientes de un concepto
     *
     * @param $id_concepto
     * @return Collection|Concepto
     */
    public function getDescendientes($id_concepto)
    {
        if (is_null($id_concepto)) {
            return $this->getNivelesRaiz();
        }

        $concepto = $this->getById($id_concepto);

        return $concepto->getDescendientes();
    }

    /**
     * Obtiene los ancestros de un concepto
     *
     * @param $id_concepto
     * @return Concepto|Collection
     */
    public function getAncestros($id_concepto)
    {
        $concepto = $this->getById($id_concepto);
        $niveles  = $this->nivelParser->separaEnNiveles($concepto->nivel);

        return Concepto::where('id_obra', $this->context->getId())
            ->whereIn('nivel', $niveles)
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Obtiene el ancestro inmediato de un concepto
     *
     * @param $id_concepto
     * @return Concepto
     */
    public function getAncestro($id_concepto)
    {
        $concepto = $this->getById($id_concepto);

        return $concepto->getDescendientes();
    }

    /**
     * Obtiene una lista de todos los niveles del presupuesto de obra
     * hasta llegar a los niveles de conceptos medibles
     *
     * @return array
     */
    public function getConceptosList()
    {
        return Concepto::selectRaw("id_obra, id_material, nivel, id_concepto, REPLICATE(' | ', LEN(nivel)/4) + '->' + descripcion as descripcion")
            ->where('id_obra', $this->context->getId())
            ->whereNull('id_material')
            ->whereExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from('conceptos as medibles')
                    ->whereRaw('conceptos.id_obra = medibles.id_obra')
                    ->whereRaw('LEFT(medibles.nivel, LEN(conceptos.nivel)) = conceptos.nivel')
                    ->where('concepto_medible', '>', '0');
            })
            ->orderBy('nivel')
            ->lists('descripcion', 'id_concepto')
            ->all();
    }

    /**
     * Obtiene todos los conceptos que son medibles/facturables
     *
     * @return Collection|Concepto
     */
    public function getMedibles()
    {
        return Concepto::where('id_obra', $this->context->getId())
            ->whereIn('concepto_medible', [Concepto::MEDIBLE, Concepto::FACTURABLE])
            ->orderBy('nivel')
            ->get();
    }

    /**
     * Realiza una busqueda de conceptos por descripcion o clave
     *
     * @param $search
     * @param array $filters
     * @return Collection|Concepto
     */
    public function search($search, array $filters)
    {
        $filters = $this->parseFilters($filters);

        return Concepto::where('id_obra', $this->context->getId())
            ->where(function ($query) use ($search) {
                $query->where('descripcion', 'LIKE', '%' . $search . '%')
                    ->orWhere('clave_concepto', 'LIKE', '%' . $search . '%');
            })
            ->where(function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    $query->{$filter['method']}($filter['field'], $filter['value']);
                }
            })
            ->get();
    }

    /**
     * Genera los filtros adecuados para la busqueda de conceptos
     *
     * @param array $filters
     * @return array
     */
    private function parseFilters(array $filters)
    {
        $filterFields = [];

        foreach ($this->filterFields as $field) {
            if (! array_key_exists($field, $filters)) {
                continue;
            }

            if ($field == 'concepto_medible') {
                $filterFields[] = [
                    'field' => $field,
                    'method' => 'whereIn',
                    'value' => [Concepto::MEDIBLE, Concepto::FACTURABLE],
                ];
                continue;
            }

            $filterFields[] = [
                'field' => $field,
                'method' => 'where',
                'value' => $filters[$field],
            ];
        }
        return $filterFields;
    }

    /**
     * Obtiene la cantidad por programar de un concepto
     *
     * @param $id_concepto
     * @return float
     */
    public function cantidadPorProgramar($id_concepto)
    {
        $concepto            = $this->getById($id_concepto);
        $cantidad_pendiente  = $concepto->cantidad_presupuestada;
        $cantidad_programada = $concepto->cronogramas()->sum('cantidad');

        $cantidad_pendiente -= $cantidad_programada;

        return $cantidad_pendiente;
    }

    /**
     * Genera el cronograma de trabajo para un concepto en un periodo de tiempo
     *
     * @param $id_obra
     * @param $id_concepto
     * @param array $periodos
     * @return mixed
     * @throws CantidadPendienteSuperadaException
     */
    public function programaConcepto($id_obra, $id_concepto, array $periodos)
    {
        $concepto = $this->findById($id_concepto);

        $cantidad_por_programar = $this->cantidadPorProgramar($id_obra, $id_concepto);

        $cantidad_a_programar = 0;

        $cronogramas = [];

        foreach ($periodos as $periodo) {
            $cantidad_a_programar += $periodo['cantidad'];

            $cronogramas[] = new Cronograma([
                'fecha_desde' => $periodo['fecha_inicial'],
                'fecha_hasta' => $periodo['fecha_termino'],
                'cantidad' => $periodo['cantidad'],
                'avance' => 0,
            ]);
        }

        $cantidad_a_programar = number_format($cantidad_a_programar, 4, '.', '');

        if ($cantidad_a_programar > $cantidad_por_programar) {
            throw new CantidadPendienteSuperadaException($id_concepto);
        }

        return $concepto->cronogramas()->saveMany($cronogramas);
    }

    /**
     * Obtiene un cronograma por su id
     *
     * @param $id
     * @return Cronograma
     */
    public function getCronogramaById($id)
    {
        return Cronograma::where('id_cronograma', $id)->first();
    }

    /**
     * Elimina un cronograma por su id
     *
     * @param $id
     * @return bool
     */
    public function deleteCronograma($id)
    {
        $cronograma = $this->getCronogramaById($id);

        return $cronograma->delete();
    }
}
