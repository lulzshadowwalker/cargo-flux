<?php

namespace App\Contracts;

use App\Support\PayableItem;
use Brick\Money\Money;

interface Payable
{
    /**
     * Get the items that are being paid for.
     * 
     * @return array<PayableItem>
     */
    public function items(): array;
    
    /**
     * Get the total price of the items.
     * 
     * @return Money
     */
    public function price(): Money;
}
