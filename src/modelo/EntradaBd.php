<?php

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Entrada;

class EntradaBd
{
    public static function eliminar(int $id): bool|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $sentencia = $conexion->prepare("delete from entrada where id=?");
            $sentencia->bind_param("i", $id);
            $eliminado = $sentencia->execute();

            return $eliminado;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getEntrada(int $id): Entrada|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select * from entrada where id=?");
            $sentencia->bind_param('i', $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();
            if ($fila == null) {
                return null;
            } else {
                //Segundo query para obtener el nombre del autor
                $segundoQuery = $conexion->query("select nombre from usuario us join entrada en where us.id='{$fila['autor']}'");
                if ($segundoQuery !== false) {
                    $fila2 = $segundoQuery->fetch_assoc();
                    $entrada = new Entrada(
                        id: $fila['id'],
                        texto: $fila['texto'],
                        imagen: $fila['imagen'],
                        autor: $fila['autor'],
                        autorAutentico: $fila2['nombre'],
                        creado: $fila['creado']
                    );
                    //Tercer query para obtener el numero de me gusta
                    $tercerQuery = $conexion->query("select entrada, count(usuario) from megusta where entrada = '{$fila['id']}' ");
                    if ($tercerQuery != false) {
                        $fila3 = $tercerQuery->fetch_assoc();
                        $entrada->setMegustas($fila3['count(usuario)']);
                    }
                    //Cuarta query para obtener los comentarios
                    $cuartoQuery = $conexion->query("select usu.nombre, com.usuario, com.comentario from comentario com join usuario usu on usu.id=com.usuario where com.entrada = '{$fila['id']}' order by com.id");
                    if ($cuartoQuery != false) {
                        $comentarios = [];
                        while (($fila4 = $cuartoQuery->fetch_assoc()) != null) {
                            $comentarios[] = ['texto' => $fila4['comentario'], 'usuario' => $fila4['nombre']];
                        }

                        $entrada->setComentarios($comentarios);
                    }
                    return $entrada;
                }
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getEntradas(): Entrada|array|null
    {
        try {
            $resultado = [];
            $conexion = BaseDatos::getConexion();
            $primerQuery = $conexion->query("select * from entrada order by creado desc");
            if ($primerQuery !== false) {
                while (($fila = $primerQuery->fetch_assoc()) != null) {
                    //Segundo query para obtener el nombre del autor
                    $segundoQuery = $conexion->query("select nombre from usuario us join entrada en where us.id='{$fila['autor']}'");

                    if ($segundoQuery !== false) {
                        $fila2 = $segundoQuery->fetch_assoc();

                        $entrada = new Entrada(
                            id: $fila['id'],
                            texto: $fila['texto'],
                            imagen: $fila['imagen'],
                            autor: $fila['autor'],
                            autorAutentico: $fila2['nombre'],
                            creado: $fila['creado']
                        );
                        //Tercer query para obtener el numero de me gusta
                        $tercerQuery = $conexion->query("select entrada, count(usuario) from megusta where entrada = '{$fila['id']}' ");
                        if ($tercerQuery != false) {
                            $fila3 = $tercerQuery->fetch_assoc();
                            $entrada->setMegustas($fila3['count(usuario)']);
                        }
                        //En este caso no se obtienen los comentarios ya que solo los necesitamos mostrar en el getEntrada en la vista detalle
                        $resultado[] = $entrada;
                    }
                }
            }
            return $resultado;
        } catch (\Exception $e) {
            return null;
        }
    }
    public static function insertar(Entrada $entrada): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $texto = $entrada->getTexto();
            $imagen = $entrada->getImagen();
            $autor = $_SESSION['usuario']['id'];
            $sentencia = $conexion->prepare("insert into entrada (texto, imagen, autor) values (?, ?, ?)");
            $sentencia->bind_param("ssi", $texto, $imagen, $autor);
            $sentencia->execute();

            return $conexion->insert_id;
        } catch (\Exception $e) {
            return null;
        }
    }
}
