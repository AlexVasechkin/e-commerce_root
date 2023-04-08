<?php

namespace App\Message;

class EsIndexProductMessage
{
    private int $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }
}
