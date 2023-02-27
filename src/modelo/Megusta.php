<?

namespace dwesgram\modelo;

class Megusta
{
    public function __construct(
        private int|null $entradaId,
        private int|null $usuarioId
    ) {
    }

    public function getEntradaId(): int|null
    {
        return $this->entradaId;
    }

    public function getUsuarioId(): int|null
    {
        return $this->usuarioId;
    }
}
