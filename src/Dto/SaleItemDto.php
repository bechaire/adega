<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class SaleItemDto
{
    public function __construct(
        #[Assert\Positive(message: 'A quantidade vendida precisa ser maior que zero')]
        public int $quantity,
    ) {}
}
