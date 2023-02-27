<?php

$eliminado = $datosParaVista['datos'];

if ($eliminado) {
    echo "Entrada eliminada correctamente.";
} else {
    echo "Error: No se ha podido eliminar la entrada.";
}
?>
<p><a href="index.php?controlador=entrada&accion=lista">Volver</a></p>