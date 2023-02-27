<?php
$entrada = $datosParaVista['datos'];
if ($entrada !== null && !empty($entrada)) {
    $autor = $entrada->getAutorAutentico();
    $texto = $entrada->getTexto();
    $imagen = $entrada->getImagen();
    $id = $entrada->getId();
    $meGusta = $entrada->getMegustas();
    $comentarios = $entrada->getComentarios();
    echo "<h2>$autor escribió</h2>";
    echo "<p>$texto</p>";
    if ($imagen !== null) {
        echo "<img src=$imagen width=400 height=400>";
    }

    if ($sesion->haySesion() && $autor == $_SESSION['usuario']['nombre']) {
        echo "<a href='index.php?controlador=entrada&accion=eliminar&id=$id'>Eliminar</a>";
        echo "<i class='bi bi-heart'>($meGusta)</i>";
    }

    if ($sesion->haySesion() && $autor != $_SESSION['usuario']['nombre']) {
        echo "<a href='index.php?controlador=megusta&accion=darMeGustaDetalle&id=$id' role='button'><i class='bi bi-heart'>($meGusta)</i></a>";
    }

    if (!$sesion->haySesion()) {
        echo "<i class='bi bi-heart'>($meGusta)</i>";
    }
?>
    <div>
        <?php
        //Si hay un usuario logeado entonces saldrá el formulario para escribir un comentario
        if ($sesion->haySesion()) {
        ?>
            <form action="index.php?controlador=comentario&accion=nuevo&id=<?= $id ?>" method="post">
                <label for="comentario">Escribe un comentario:</label><br>
                <textarea name="comentario" id="comentario" cols="45" rows="3"></textarea>
                <button type="submit">Enviar comentario</button>
            </form>
        <?php
        }
        ?>
    </div>
    <div>
        <?php
        //Cuando haya comentarios se muestran
        if ($comentarios != null) {
            foreach ($comentarios['comentarios'] as $comentario) {
                echo <<< END
                        <div>
                        <h4>{$comentario['usuario']} comento: </h4>
                        </div>
                        <p>{$comentario['texto']}</p>
                        END;
            }
        }
        ?>
    </div>
    </div>
<?php
} else {
    echo "No existe esta entrada";
}
