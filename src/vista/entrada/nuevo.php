<?php
$entrada = $datosParaVista['datos'] && $datosParaVista['datos'] != null ? $datosParaVista['datos'] : null;
$errores = $entrada ? $entrada->getErrores() : null;
$texto = $entrada ? $entrada->getTexto() : "";
?>

<div class="container">
    <h1>Nueva entrada</h1>
    <form action="index.php?controlador=entrada&accion=nuevo" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="texto" class="form-label">
                Adjúntale un texto bonito a tu imagen, con un máximo de 128 caracteres
            </label>
            <textarea class="form-control" name="texto" id="texto" rows="3" placeholder="Escribe aquí el texto"><?= $texto ?></textarea>
            <?php
            if ($errores && isset($errores['texto'])) {
                echo "<p>{$errores['texto']}</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="imagen">Selecciona una imagen para acompañar a tu entrada</label>
            <input class="form-control" type="file" name="imagen" id="imagen">
            <?php
            if ($errores && isset($errores['imagen'])) {
                echo "<p>{$errores['imagen']}</p>";
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Publicar</button>
    </form>
</div>