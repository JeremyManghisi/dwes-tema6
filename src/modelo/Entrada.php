<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Entrada extends Modelo
{
    private array $errores = [];
    private array $comentarios = [];

    public function __construct(
        public string|null $texto,
        public int|null $id = null,
        public string|null $imagen = null,
        public int|null $autor = null,
        public string|null $autorAutentico = null,
        public int|null $creado = null,
        private int|null $megustas = null
    ) {
        $this->errores = [
            'texto' => $texto === null || empty($texto) ? 'El texto no puede estar vacío' : null,
            'imagen' => null
        ];
    }
    public static function crearEntrada($post)
    {
        if (isset($post['texto'])) {
            //Se pone limite de 128 caracteres
            $texto = mb_substr(htmlspecialchars(trim($post['texto'])), 0, 128);
        }
        $entrada = new Entrada(
            texto: isset($texto) ? $texto : null,
        );

        if (
            $_FILES && isset($_FILES['imagen']) &&
            $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
            $_FILES['imagen']['size'] > 0
        ) {

            $nombreFichero = $_FILES['imagen']['tmp_name'];
            $permitido = ['image/png', 'image/jpeg'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_fichero = finfo_file($finfo, $nombreFichero);

            if (!in_array($mime_fichero, $permitido)) {
                $entrada->errores['imagen'] = "Error: La extensión no está permitida";
                return $entrada;
            } else {
                $entrada->imagen = "./assets/img" . time() . basename($_FILES['imagen']['name']);
            }
        }
        return $entrada;
    }

    public static function guardarImagen(string $ruta):bool
    {
        $fichero = $_FILES['imagen']['tmp_name'];
        return move_uploaded_file($fichero, $ruta);
    }


    public function getTexto(): string
    {
        return $this->texto ? $this->texto : '';
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getImagen(): string|null
    {
        return $this->imagen;
    }

    public function setImagen(string $img)
    {
        $this->imagen = $img;
    }

    public function getAutor(): int|null
    {
        return $this->autor;
    }

    public function getAutorAutentico(): string|null
    {
        return $this->autorAutentico;
    }

    public function getCreado(): int|null
    {
        return $this->creado;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }

    public function esValida(): bool
    {
        $esValida = true;
        $errores = $this->getErrores();
        if ($errores['texto'] != null || $errores['imagen'] != null) {
            $esValida = false;
        }

        return $esValida;
    }

    public function getMegustas(): int
    {
        return $this->megustas;
    }
    public function setMegustas(int $id){
        $this->megustas = $id;
    }
    public function getComentarios(): array
    {
        return $this->comentarios;
    }
    public function setComentarios(array|null $comentarios){
        $this->comentarios['comentarios'] = $comentarios;
    }
}
