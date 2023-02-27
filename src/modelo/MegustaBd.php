<?

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Megusta;

class MegustaBd
{

    use BaseDatos;

    public static function darMeGusta(Megusta $megusta): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("insert into megusta (entrada, usuario) values (?, ?)");
            $entradaId = $megusta->getEntradaId();
            $usuarioId = $megusta->getUsuarioId();
            $sentencia->bind_param('ii', $entradaId, $usuarioId);
            $sentencia->execute();
            return $conexion->insert_id;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function quitarMeGusta(Megusta $megusta): bool
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("delete from megusta where entrada = ? and usuario = ?");
            $entradaId = $megusta->getEntradaId();
            $usuarioId = $megusta->getUsuarioId();
            $sentencia->bind_param('ii', $entradaId, $usuarioId);
            return $sentencia->execute();
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function comprobarMeGusta($entradaId, $usuarioId): bool|null
    {
        try {

            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select * from megusta where entrada = ? and usuario = ?");
            $sentencia->bind_param("ii", $entradaId, $usuarioId);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            if ($resultado->num_rows == 0) {
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
