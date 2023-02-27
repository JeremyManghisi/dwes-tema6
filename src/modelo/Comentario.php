<?

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;

class Comentario
{
    public function __construct(
        private int|null $entradaId,
        private string|null $texto,
        private int|null $usuarioId
    ) {
    }

    public function getEntradaId(): int|null
    {
        return $this->entradaId;
    }

    public function getTexto(): String|null
    {
        return $this->texto;
    }

    public function getUsuarioId(): int|null
    {
        return $this->usuarioId;
    }
}
