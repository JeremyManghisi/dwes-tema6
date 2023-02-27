<?php
namespace dwesgram\controlador;

use dwesgram\modelo\Usuario as UsuarioModelo;
use dwesgram\modelo\UsuarioBd;
use dwesgram\controlador\Controlador;

class UsuarioControlador extends Controlador
{

    public function login(): UsuarioModelo|array|string|null
    {
        if ($this->autenticado()) {
            header('Location: index.php');
            return null;
        }
        
        if (!$_POST) {
            $this->vista = 'usuario/login';
            return null;
        }

        $nombre = $_POST && isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : null;
        $clave = $_POST && isset($_POST['clave']) ? htmlspecialchars($_POST['clave']) : null;
        $usuario = UsuarioBd::getUsuarioPorNombre($nombre);

        if ($usuario && password_verify($clave, $usuario->getClave())) {
            $_SESSION['usuario'] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'avatar' => $usuario->getAvatar()
            ];
            header('location: index.php');
            return null;
        }else {
            $this->vista = 'usuario/login';
            return  [
                'nombre' => $nombre,
                'error' => 'Error: Usuario y/o contraseña introducido no válidos.'
            ];    
        }
        
        
    }

    public function registro(): UsuarioModelo|string|null
    {
        //Si ya hay usuario logeado
        if ($this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }
        //Si no hay post
        if (!$_POST) {
            $this->vista = 'usuario/registro';
            return null;
        }

        //Si llega post, creamos el usuario
        $usuario = UsuarioModelo::crearUsuario($_POST);

        if ($usuario->getAvatar() != "assets/img/pordefecto.png") {
            $seHaMovido = move_uploaded_file($_FILES['avatar']['tmp_name'], $usuario->getAvatar());
            if (!$seHaMovido) {
                $this->vista = "errores/500";
                return null;
            }
        }

        if (!$usuario->esValido()) {
            $this->vista = 'usuario/registro';
            return $usuario;
        }

        $id = UsuarioBd::insertar($usuario);
        if ($id !== null) {
            $this->vista = 'usuario/mensaje';
            return "Te has registrado correctamente";
        } else {
            $this->vista = "errores/500";
            return null;
        }
        
        $_SESSION['usuario'] = [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre()
        ];
    }

    public function logout(): void
    {
        if (!$this->autenticado()) {
            header('Location: index.php');
            return;
        }
        session_destroy();
        header('Location: index.php');
    }

}