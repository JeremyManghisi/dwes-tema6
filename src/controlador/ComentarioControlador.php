<?

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\Comentario;
use dwesgram\modelo\ComentarioBd;

class ComentarioControlador extends Controlador
{
    public function nuevo(): Entrada|null
    {
        //Si no hay usuario logueado no puede crear un comentario
        if (!$this->autenticado()) {
            return null;
        }

        $entradaId = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        $texto = $_POST && isset($_POST['comentario']) ? $_POST['comentario'] : "";
        $usuarioId = $_SESSION['usuario']['id'];

        //Se crea un nuevo comentario con los datos adquiridos
        $comentario = new Comentario(entradaId: $entradaId, texto: $texto, usuarioId: $usuarioId);

        //Se inserta en la base de datos si el texto tiene algo escrito, ya que no tendría sentido si no tuviera nada y se inserta
        if (strlen($texto) > 0) {
            $id = ComentarioBd::insertar($comentario);
        }

        $this->vista = "entrada/detalle";
        return EntradaBd::getEntrada($entradaId);
    }
}
