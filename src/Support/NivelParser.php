<?php

namespace Ghi\Core\Support;

class NivelParser
{
    protected $digitosPorSegmento = 4;

    /**
     * Separa un nivel en sus subniveles
     *
     * @param $nivel
     * @param string $separador
     * @return array
     */
    public function extraeNiveles($nivel, $separador = '.')
    {
        $numero_segmentos = $this->cuentaSegmentos($nivel, $separador);

        $niveles = [];

        for ($i = 1; $i <= $numero_segmentos; $i++) {
            $niveles[] = $this->extraeNivel($nivel, $i);
        }

        return $niveles;
    }

    /**
     * Extrae un nivel de otro nivel
     *
     * @param $nivel
     * @param $profundidad
     * @return string
     */
    protected function extraeNivel($nivel, $profundidad)
    {
        return substr($nivel, 0, $this->digitosPorSegmento * $profundidad);
    }

    /**
     * Extrae los segmentos de un nivel como un arreglo
     *
     * @param $nivel
     * @param string $separador
     * @return array
     */
    public function extraeSegmentos($nivel, $separador = '.')
    {
        return explode($separador, $nivel);
    }

    /**
     * Cuenta el numero de segmentos de un nivel
     *
     * @param $nivel
     * @param $separador
     * @return int
     */
    protected function cuentaSegmentos($nivel, $separador = '.')
    {
        return count(explode($separador, $nivel)) - 1;
    }

    /**
     * Calcula la profundidad de un nivel (numero de segmentos)
     *
     * @param $nivel
     * @param string $separador
     * @return int
     */
    public function calculaProfundidad($nivel, $separador = '.')
    {
        return $this->cuentaSegmentos($nivel, $separador);
    }

    /**
     * Convierte un numero entero a su representacion de nivel
     *
     * @param int $numero
     * @return string
     */
    protected function convierteNumeroANivel($numero)
    {
        $ceros = '';

        if (strlen($numero) == 1) {
            $ceros = '00';
        }

        if (strlen($numero) == 2) {
            $ceros = '0';
        }
        return $ceros.$numero.'.';
    }
}
