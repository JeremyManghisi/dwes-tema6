<?

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\Megusta;
use dwesgram\modelo\MegustaBd;

class MegustaControlador extends Controlador
{

    public function darMeGustaLista(): array|null
    {
        //Si no hay usuario logueado no puede dar me gusta
        if (!$this->autenticado()) {
            return null;
        }

        $entradaId = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        $usuarioId = $_SESSION['usuario']['id'];
        $entrada  = EntradaBd::getEntrada($entradaId);

        //Si el usuario es el autor de la entrada no puede dar me gusta
        if ($entrada->getAutor() == $usuarioId) {
            $this->vista = "errores/403";
            return null;
        }
        //Se crea un nuevo me gusta con los datos adquiridos
        $meGusta = new Megusta(entradaId: $entradaId, usuarioId: $usuarioId);

        //Si se da me gusta, al darle otra vez se quitará
        if (MeGustaBd::comprobarMeGusta($meGusta->getEntradaId(), $meGusta->getUsuarioId())) {
            $id = MegustaBd::quitarMeGusta($meGusta);
            $this->vista = "entrada/lista";
            return EntradaBd::getEntradas();
        }

        //Se inserta en la base de datos
        $id = MegustaBd::darMeGusta($meGusta);

        $this->vista = "entrada/lista";
        return EntradaBd::getEntradas();
    }

    public function darMeGustaDetalle(): Entrada|null
    {
        //Si no hay usuario logueado no puede dar me gusta
        if (!$this->autenticado()) {
            return null;
        }

        $entradaId = $_GET && isset($_GET['id']) ? $_GET['id'] : null;
        $usuarioId = $_SESSION['usuario']['id'];
        $entrada  = EntradaBd::getEntrada($entradaId);

        //Si el usuario es el autor de la entrada no puede dar me gusta
        if ($entrada->getAutor() == $usuarioId) {
            $this->vista = "errores/403";
            return null;
        }
        //Se crea un nuevo me gusta con los datos adquiridos
        $meGusta = new Megusta(entradaId: $entradaId, usuarioId: $usuarioId);

        //Si se da me gusta, al darle otra vez se quitará
        if (MeGustaBd::comprobarMeGusta($meGusta->getEntradaId(), $meGusta->getUsuarioId())) {
            $id = MegustaBd::quitarMeGusta($meGusta);
            $this->vista = "entrada/detalle";
            return EntradaBd::getEntrada($entradaId);
        }

        //Se inserta en la base de datos
        $id = MegustaBd::darMeGusta($meGusta);

        $this->vista = "entrada/detalle";
        return EntradaBd::getEntrada($entradaId);
    }
}
