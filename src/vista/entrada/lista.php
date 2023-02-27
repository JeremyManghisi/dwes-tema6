<?php

$entradas = $datosParaVista['datos'];
if ($entradas !== null && !empty($entradas)) {
    echo "<div>";
    echo "<hr>";
    foreach ($entradas as $entrada) {
        $id = $entrada->getId();
        $texto = $entrada->getTexto();
        $imagen = $entrada->getImagen();
        $autor = $entrada->getAutorAutentico();
        $meGusta = $entrada->getMegustas();

        echo "<div>";
        echo "<p> Escrito por $autor </p>";
        if ($imagen !== null) {
            echo "<img src='$imagen' width=100 height=100>";
        }
        echo "<p> $texto </p>";
        echo "<a href='index.php?controlador=entrada&accion=detalle&id=$id'>Detalles</a>";
        echo "<br>";
        //Si hay sesión y el autor de la entrada fue quien está logeado, puede eliminarla
        if ($sesion->haySesion() && $autor == $_SESSION['usuario']['nombre']) {
            echo "<a href='index.php?controlador=entrada&accion=eliminar&id=$id'>Eliminar</a>";
        }
        //Si hay sesión y el autor de la entrada no es el mismo que está logeado, se muestra botón me gusta
        if ($sesion->haySesion() && $autor != $_SESSION['usuario']['nombre']) {
            echo "<a href='index.php?controlador=megusta&accion=darMeGustaLista&id=$id' role='button'><i class='bi bi-heart'>($meGusta)</i></a>";
        }
        if (!$sesion->haySesion()) {
            echo "<i class='bi bi-heart'>($meGusta)</i>";
        }
        echo "</p>";
        echo "</div>";
        echo "</hr>";
    }
    echo "</div>";
} else {
    echo "<p> No hay entradas </p>";
}
