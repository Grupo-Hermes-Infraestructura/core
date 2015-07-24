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

    /**
     * {@inheritdoc}
     */
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
     * {@inheritdoc}
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
     * Obtiene los conceptos que son descendientes de otro
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

        $numero_nivel = $concepto->getNumeroNivel() + 1;

        return Concepto::where ('id_obra', $this->context->getId())
            ->where('nivel', 'LIKE', "{$concepto->nivel}%")
            ->whereRaw("LEN (nivel)/4 = {$numero_nivel}")
            ->get();
    }

    /**
     * Obtiene los conceptos que son ancestros de un concepto
     *
     * @param $id_concepto
     * @return Collection|Concepto
     */
    public function getAncestros($id_concepto)
    {
        $concepto = $this->getById($id_concepto);
        $niveles  = $this->nivelParser->extraeNiveles($concepto->nivel);

        return Concepto::where('id_obra', $this->context->getId())
            ->where(function ($query) use ($niveles) {
                foreach ($niveles as $nivel) {
                    $query->orWhere('nivel', $nivel);
                }
        })->orderBy('nivel')->get();
    }

    /**
     * Obtiene el concepto padre inmediato de un concepto
     *
     * @param $id_concepto
     * @return Concepto
     */
    public  function getConceptoPadre($id_concepto)
    {
        $concepto = $this->getById($id_concepto);

        return Concepto::where ('id_obra', $this->context->getId())
            ->whereRaw("nivel = LEFT('{$concepto->nivel}', LEN('{$concepto->nivel}') - 4)")
            ->first();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getMedibles()
    {
        return Concepto::where('id_obra', $this->context->getId())
            ->whereIn('concepto_medible', [Concepto::CONCEPTO_MEDIBLE, Concepto::CONCEPTO_FACTURABLE])
            ->orderBy('nivel')
            ->get();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
                    'value' => [Concepto::CONCEPTO_MEDIBLE, Concepto::CONCEPTO_FACTURABLE],
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
     * @param $id_obra
     * @param $id_concepto
     * @return float
     */
    public  function cantidadPorProgramar($id_obra,$id_concepto)
    {
        $concepto = $this->findById($id_concepto);
        $cantidad_pendiente = $concepto->cantidad_presupuestada;

        $cantidad_programada = $concepto->cronogramas()->sum('cantidad');

        $cantidad_pendiente -= $cantidad_programada;

        return $cantidad_pendiente;
    }

    /**
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

        $cantidad_programar = 0;

        $cronogramas = [];

        foreach ($periodos as $periodo)
        {
            $cantidad_programar += $periodo['cantidad'];

            $cronogramas[] = new Cronograma([
                'fecha_desde' => $periodo['fecha_inicial'],
                'fecha_hasta' => $periodo['fecha_termino'],
                'cantidad' => $periodo['cantidad'],
                'avance' => 0,
            ]);
        }

        $cantidad_programar = number_format($cantidad_programar , 4, '.', '');

        if ($cantidad_programar > $cantidad_por_programar) {
            throw new CantidadPendienteSuperadaException($id_concepto);
        }

        return $concepto->cronogramas()->saveMany($cronogramas);
    }

    /**
     * @param $id_concepto
     * @return Collection|Cronograma
     */
    public  function findCronogramas($id_concepto)
    {
        return Cronograma::where('id_concepto', '=', $id_concepto)
            ->orderBy('fecha_desde')
            ->get();
    }

    /**
     * @param $id_cronograma
     * @return Cronograma
     */
    public function getCronogramaPorId($id_cronograma)
    {
        return Cronograma::where('id_cronograma', $id_cronograma)->first();
    }

    /**
     * Elimina un cronograma por su id
     *
     * @param $id
     * @return bool
     */
    public function deleteCronograma($id)
    {
       $cronograma = $this->getCronogramaPorId($id);

        return $cronograma->delete();
    }
}
