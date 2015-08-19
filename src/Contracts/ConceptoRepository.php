<?php

namespace Ghi\Core\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ConceptoRepository
{
    /**
     * Obtiene un concepto por su id
     *
     * @param $id
     * @return Concepto
     */
    public function getById($id);

    /**
     * Obtiene todos los conceptos de una obra
     *
     * @return Collection|Concepto
     */
    public function getAll();

    /**
     * Obtiene los conceptos raiz del presupuesto de obra
     *
     * @return Collection|Concepto
     */
    public function getNivelesRaiz();

    /**
     * Obtiene los descendientes de un concepto
     *
     * @param $id_concepto
     * @return Collection|Concepto
     */
    public function getDescendientes($id_concepto);

    /**
     * Obtiene los ancestros de un concepto
     *
     * @param $id_concepto
     * @return Concepto|Collection
     */
    public function getAncestros($id_concepto);

    /**
     * Obtiene el ancestro inmediato de un concepto
     *
     * @param $id_concepto
     * @return Concepto
     */
    public  function getAncestro($id_concepto);

    /**
     * Obtiene una lista de todos los niveles del presupuesto de obra
     * hasta llegar a los niveles de conceptos medibles
     *
     * @return array
     */
    public function getConceptosList();

    /**
     * Obtiene todos los conceptos que son medibles/facturables
     *
     * @return Collection|Concepto
     */
    public function getMedibles();

    /**
     * Realiza una busqueda de conceptos por descripcion o clave
     *
     * @param $search
     * @param array $filters
     * @return Collection|Concepto
     */
    public function search($search, array $filters);

    /**
     * Obtiene la cantidad por programar de un concepto
     *
     * @param $id_concepto
     * @return float
     */
    public function cantidadPorProgramar($id_concepto);

    /**
     * Genera el cronograma de trabajo para un concepto en un periodo de tiempo
     *
     * @param $id_obra
     * @param $id_concepto
     * @param array $periodos
     * @return mixed
     * @throws CantidadPendienteSuperadaException
     */
    public function programaConcepto($id_obra, $id_concepto, array $periodos);

    /**
     * Obtiene un cronograma por su id
     *
     * @param $id
     * @return Cronograma
     */
    public function getCronogramaById($id);

    /**
     * Elimina un cronograma por su id
     *
     * @param $id
     * @return bool
     */
    public function deleteCronograma($id);
}
