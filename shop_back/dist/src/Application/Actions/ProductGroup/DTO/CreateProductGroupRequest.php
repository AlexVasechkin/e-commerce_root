<?php

namespace App\Application\Actions\ProductGroup\DTO;

use App\Entity\Contracts\PutProductGroupInterface;

class CreateProductGroupRequest
    implements PutProductGroupInterface
{
    private string $name;

    private bool $isToHomepage;

    private int $homepageSort;

    public function __construct(
        string $name,
        bool $isToHomepage = false,
        int $homepageSort = 500
    ) {
        $this->name = $name;
        $this->isToHomepage = $isToHomepage;
        $this->homepageSort = $homepageSort;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isToHomepage(): bool
    {
        return $this->isToHomepage;
    }

    public function getHomepageSort(): int
    {
        return $this->homepageSort;
    }
}
