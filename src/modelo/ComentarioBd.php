<?

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Comentario as ModeloComentario;

class ComentarioBd
{

    use BaseDatos;

    public static function insertar(ModeloComentario $comentario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("insert into comentario (comentario, usuario, entrada) values (?, ?, ?) ON DUPLICATE KEY UPDATE comentario = ?");
            $entradaId = $comentario->getEntradaId();
            $texto = $comentario->getTexto();
            $usuarioId = $comentario->getUsuarioId();
            $sentencia->bind_param('siis', $texto, $usuarioId, $entradaId, $texto);
            $sentencia->execute();
            return $conexion->insert_id;
        } catch (\Exception $e) {
            return null;
        }
    }
}
