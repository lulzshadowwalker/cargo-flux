<?php

namespace App\Support;

use Brick\Money\Money;

class PayableItem
{
    public function __construct(
        protected string $name,
        protected Money $price,
        protected int $quantity,
    )
    {
        //
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
