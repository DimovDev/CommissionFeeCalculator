<?php

declare(strict_types=1);

namespace CommissionFeeCalculator\src\Service;

class Math
{
    private int $scale;

    public function __construct(int $scale)
    {
        $this->scale = $scale;
    }

    public function add(string $leftOperand, string $rightOperand): string
    {
        return bcadd($leftOperand, $rightOperand, $this->scale);
    }
}
