<?php

namespace EDU\InventoryReport\Api;

/**
 * @api
 */
interface CalculatorInterface
{
    /**
     * Add two numbers and return the sum.
     * @param int $num1
     * @param int $num2
     * @return int
     */
    public function add(int $num1, int $num2): int;
}
