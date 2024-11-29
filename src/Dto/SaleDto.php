<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class SaleDto
{
    public function __construct(
        #[Assert\Positive(message: 'A distância, em quilômetros, precisa ser maior que zero')]
        public int $distance,

        #[Assert\NotBlank(message: 'O campo date, referente a data da venda, deve ser preenchido')]
        #[Assert\Date(message: 'Informe uma data válida')]
        public string $date,
    ) {}
}
