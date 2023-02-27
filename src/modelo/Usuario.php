<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Usuario extends Modelo
{
    private array $errores = [];

    public function __construct(
        private string|null $nombre,
        private string|null $clave,
        private string|null $email,
        private int|null $id = null,
        private string|null $avatar = "assets/img/pordefecto.png",
        private int|null $registrado = null
    ) {
        $this->errores = [
            'nombre' => $nombre === null || empty($nombre) ? 'Error: El nombre no puede estar vacío' : null,
            'clave' => $clave === null || empty($clave) ? 'Error: La contraseña no puede estar vacía' : null,
            'email' => $email === null || empty($email) ? 'El email no puede estar vacío' : null,
            'repiteclave' => null,
            'avatar' => null,
        ];
    }

    public static function crearUsuario(array $post): Usuario|null
    {
        $usuario = new Usuario(
            nombre: $post && isset($post['nombre']) ? htmlspecialchars(trim($post['nombre'])) : "",
            email: $post && isset($post['email']) ? htmlspecialchars($post['email']) : "",
            clave: $post && isset($post['clave']) ? htmlspecialchars(trim($post['clave'])) : "",
            registrado: time()
        );
        $repiteClave = $post && isset($post['repiteclave']) ? htmlspecialchars(trim($post['repiteclave'])) : null;

        //Si las contraseñas no son iguales, error
        if ($usuario->clave != $repiteClave) {
            $usuario->errores['repiteclave'] = "Error: Las contraseñas son diferentes";
        }
        //Si la contraseña tiene menos de 8 caracteres, error
        if (mb_strlen($usuario->clave) < 8) {
            $usuario->errores['clave'] = "Error: La contraseña debe tener al menos 8 carácteres";
        }
        //Si el nombre está vacío, error
        if (mb_strlen($usuario->nombre) !== 0) {
            $otro = UsuarioBd::getUsuarioPorNombre($usuario->nombre);
            //Si se pone el mismo usuario que un usuario registrado en la base de datos, error
            if ($otro !== null) {
                $usuario->errores['nombre'] = "Error: El nombre de usuario ya está en uso";
            }
        }

        if (
            $_FILES && isset($_FILES['avatar']) &&
            $_FILES['avatar']['error'] === UPLOAD_ERR_OK &&
            $_FILES['avatar']['size'] > 0
        ) {
            $fichero = $_FILES['avatar']['tmp_name'];

            $permitido = array('image/png', 'image/jpeg');

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fichero);

            if (in_array($mime, $permitido)) {
                $usuario->avatar = "assets/img/" . time() . basename($_FILES['avatar']['name']);
            } else {
                $usuario->errores['avatar'] = "ERROR: Extensión no disponible";
                return $usuario;
            }
        }
        return $usuario;
    }

    public function getNombre(): string|null
    {
        return $this->nombre;
    }
    public function getErrores(): array
    {
        return $this->errores;
    }

    public function getClave(): string|null
    {
        return $this->clave;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    public function setAvatar(String $avatar)
    {
        $this->avatar = $avatar;
    }
    public function getRegistrado(): int|null
    {
        return $this->registrado;
    }

    public function esValido(): bool
    {
        $valido = true;
        $errores = $this->getErrores();
        if ($errores['nombre'] != null || $errores['clave'] != null || $errores['email'] != null || $errores['repiteclave'] != null || $errores['avatar'] != null) {
            $valido = false;
        }

        return $valido;
    }
}
