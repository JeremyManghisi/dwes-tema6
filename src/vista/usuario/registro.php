<?php
$usuario = $datosParaVista['datos'] && $datosParaVista['datos'] != null ? $datosParaVista['datos'] : null;
$errores = $usuario ? $usuario->getErrores() : null;
$nombre = $usuario ? $usuario->getNombre() : "";
$email = $usuario ? $usuario->getEmail() : null;
?>
<div class="container">
    <h1>Regístrate</h1>

    <form action="index.php?controlador=usuario&accion=registro" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de usuario</label><br>
            <input type="text" id="nombre" name="nombre" value="<?= $nombre ?>">
            <?php
            if ($errores && isset($errores['nombre'])) {
                echo "<p>{$errores['nombre']}</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label><br>
            <input type="email" id="email" name="email" value="<?= $email ?>">
            <?php
            if ($errores && isset($errores['email'])) {
                echo "<p>{$errores['email']}</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label><br>
            <input type="password" id="clave" name="clave">
            <?php
            if ($errores && isset($errores['clave'])) {
                echo "<p>{$errores['clave']}</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="repiteclave" class="form-label">Repite la contraseña</label><br>
            <input type="password" id="repiteclave" name="repiteclave">
            <?php
            if ($errores && isset($errores['repiteclave'])) {
                echo "<p>{$errores['repiteclave']}</p>";
            }
            ?>
        </div>
        <div class="mb-3">
            <label for="avatar">Puedes elegir un avatar</label><br>
            <input class="form-control" type="file" name="avatar" id="avatar">
            <?php
            if ($errores && isset($errores['avatar'])) {
                echo "<p>{$errores['avatar']}</p>";
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Crear cuenta</button>
    </form>
</div>