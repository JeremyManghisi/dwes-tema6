<?php

namespace dwesgram\modelo;
session_start();
class Sesion
{
    private int|null $id;
    private string|null $nombre;

    public function __construct()
    {
        $this->id = $_SESSION && isset($_SESSION['usuario']) && isset($_SESSION['usuario']['id']) ? htmlspecialchars(trim($_SESSION['usuario']['id'])) : null;
        $this->nombre = $_SESSION && isset($_SESSION['usuario']) && isset($_SESSION['usuario']['nombre']) ? htmlspecialchars(trim($_SESSION['usuario']['nombre'])) : null;
    }

    public function haySesion(): bool
    {
        return $this->id !== null && $this->nombre !== null;
    }

    public function mismoUsuario(int $id): bool
    {
        return $this->id === $id;
    }
    public function getNombre():string|null
    {
        return $this->nombre;
    }

    public function getAvatar()
    {
        $usuario = UsuarioBd::getUsuarioPorNombre($this->nombre);
        return  $usuario instanceof Usuario ? $usuario->getAvatar() : "assets/img/pordefecto.png" ;
    }
}

