<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class WineDto
{
    public string $type;

    public function __construct(
        #[Assert\NotBlank(message: 'O campo name é obrigatório')]
        #[Assert\Length(min: 5, max: 255,  minMessage: 'Campo name muito curto', maxMessage: 'Campo name muito longo')]
        public string $name,

        #[Assert\NotBlank(message: 'Informe o tipo de uva ou blend')]
        public string $grape,

        #[Assert\NotBlank(message: 'Informe o país de origem')]
        public string $country,

        #[Assert\PositiveOrZero(message: 'A graduação alcoólica precisa ser maior ou igual a zero')]
        public float $alcoholPerc,

        #[Assert\Positive(message: 'O volume em ML precisa ser maior que zero')]
        public int $volumeMl,

        #[Assert\Positive(message: 'O peso precisa ser maior que zero')]
        public float $weightKg,

        #[Assert\PositiveOrZero(message: 'A quantidade do estoque precisa ser maior ou igual a zero')]
        public int $stock,

        #[Assert\Positive(message: 'O preço precisa ser positivo')]
        public float $price,
    ) {
        $this->type = 'wine';
    }
}
