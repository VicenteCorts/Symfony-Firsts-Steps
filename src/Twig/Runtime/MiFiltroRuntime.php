<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class MiFiltroRuntime implements RuntimeExtensionInterface {

    public function __construct() {
        // Inject dependencies if needed
    }

    public function doSomething($value) {
        // ...
    }

    public function multiplicar($numero) {
        $tabla = "<h1>Tabla del $numero</h1>";
        for ($i = 0; $i <= 10; $i++) {
            $tabla .= "$i X $numero = " . ($i * $numero) . "<br/>";
        }

        return $tabla;
    }
}
