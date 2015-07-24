<?php

namespace Ghi\Core\Support;

class NivelParser
{
    protected $numPosicionesNivel = 4;

    /**
     * @param $cadenaNivel
     * @param string $separador
     * @return array
     */
    public function extraeNiveles($cadenaNivel, $separador = '.')
    {
        $numSegmentos = $this->cuentaSegmentos($cadenaNivel, $separador);

        $niveles = [];

        if ($numSegmentos == 1) {
            $niveles[] = $cadenaNivel;
        } else {
            for ($i = 1; $i <= $numSegmentos; $i++) {
                $niveles[] = $this->extraeNivel($cadenaNivel, $i);
            }
        }

        return $niveles;
    }

    /**
     * @param $cadenaNivel
     * @param $separador
     * @return int
     */
    protected function cuentaSegmentos($cadenaNivel, $separador)
    {
        return count(explode($separador, $cadenaNivel)) - 1;
    }

    /**
     * @param $cadenaNivel
     * @param $i
     * @return string
     */
    protected function extraeNivel($cadenaNivel, $i)
    {
        return substr($cadenaNivel, 0, $this->numPosicionesNivel * $i);
    }

    /**
     * @param $cadenaNivel
     * @param string $separador
     * @return int
     */
    public function calculaProfundidad($cadenaNivel, $separador = '.')
    {
        return $this->cuentaSegmentos($cadenaNivel, $separador);
    }
}
