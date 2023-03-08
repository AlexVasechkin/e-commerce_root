<?php

namespace App\Application\Actions\ProductGroup\DTO;

use App\Entity\Contracts\PutProductGroupInterface;

class CreateProductGroupRequest
    implements PutProductGroupInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
