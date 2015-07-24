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
     * Obtiene los descendientes de un concepto
     *
     * @param $id
     * @return Collection|Concepto
     */
    public function getDescendantsOf($id);

    /**
     * Obtiene los conceptos raiz del presupuesto de obra
     *
     * @return Collection|Concepto
     */
    public function getRootLevels();

    /**
     * Obtiene los ancestros de un concepto
     *
     * @param $id
     * @return Concepto|Collection
     */
    public function getAncestorsOf($id);

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
     * @return
     */
    public function search($search, array $filters);
}
