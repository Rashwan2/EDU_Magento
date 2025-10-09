<?php

namespace EDU\InventoryReport\Model;

use \EDU\InventoryReport\Api\CalculatorInterface;

class Calculator implements CalculatorInterface
{
    public function add(int $num1, int $num2): int
    {
        return $num1 + $num2;
    }
}
