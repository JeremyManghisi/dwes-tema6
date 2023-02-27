<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada as ModeloEntrada;
use dwesgram\modelo\EntradaBd;

class EntradaControlador extends Controlador
{

    public function lista(): array|EntradaBd
    {
        $this->vista = 'entrada/lista';
        return EntradaBd::getEntradas();
    }

    public function detalle(): ModeloEntrada|null
    {
        $this->vista = "entrada/detalle";
        if ($_GET && isset($_GET['id'])) {
            $id = htmlspecialchars(trim($_GET['id']));
        }
        return EntradaBd::getEntrada($id);
    }

    public function nuevo(): ModeloEntrada|null
    {
        if (!$this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }

        if (!$_POST) {
            $this->vista = 'entrada/nuevo';
            return null;
        }

        $entrada = ModeloEntrada::crearEntrada($_POST);

        if ($entrada->getImagen() !== null) {
            $imagen = $entrada->getImagen();
            $guardarImagen = ModeloEntrada::guardarImagen($imagen);
            if (!$guardarImagen) {
                $this->vista = 'errores/500';
                return null;
            }
        }

        if ($entrada->esValida()) {
            $id = EntradaBd::insertar($entrada);
            if ($id === null) {
                $this->vista = 'errores/500';
                return null;
            } else {
                $this->vista = 'entrada/detalle';
                return EntradaBd::getEntrada($id);
            }
        } else {
            $this->vista = 'entrada/nuevo';
            return $entrada;
        }
    }

    public function eliminar(): bool|null
    {
        if (!$this->autenticado()) {
            $this->vista = 'errores/403';
            return null;
        }
        $this->vista = 'entrada/eliminar';
        if ($_GET && isset($_GET['id'])) {
            $id = htmlspecialchars(trim($_GET['id']));
            $entrada = EntradaBd::getEntrada($id);
            //Si un usuario que no ha escrito la entrada la intenta eliminar, devuelve error 403
            if (!$this->mismoUsuario($entrada->getAutor())) {
                $this->vista = 'errores/403';
                return null;
            }
            $eliminado = EntradaBd::eliminar($id);
            if ($eliminado && $id != null) {
                $imagen = $entrada->getImagen();
                if (file_exists($imagen) && $imagen != "./assets/img/pordefecto.png") {
                    unlink($imagen);
                }
                return $eliminado;
            } else {
                return false;
            }
        } else {
            return null;
        }

        return false;
    }
}
