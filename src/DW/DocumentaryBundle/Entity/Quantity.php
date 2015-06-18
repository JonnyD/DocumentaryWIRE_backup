<?php

namespace DW\DocumentaryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Quantity
{
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    /**
     * @var int $amount
     *
     * @ORM\Column(type = "integer")
     */
    private $amount;

    /**
     * @var Unit $unit
     */
    private $unit;

    /**
     * @param $amount
     * @param Unit $unit
     */
    public function __construct($amount, Unit $unit)
    {
        $this->amount = $amount;
        $this->unit = $unit;
    }

    /**
     * @param Unit $other
     * @return Quantity
     */
    public function convertTo(Unit $other)
    {
        $sourceAmount = $this->amount * $this->unit->getFactor();
        $convertedAmount = $sourceAmount / $other->getFactor();

        return new Quantity($convertedAmount, $other);
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Quantity $other
     * @return Quantity
     */
    public function add(Quantity $other)
    {
        if (!$this->isSameUnit($other->getUnit())) {
            $convert = $other->convertTo($this->unit);
            return $this->add($convert);
        }

        return new Quantity($this->amount + $other->amount, $this->unit);
    }

    /**
     * @param Quantity $other
     * @return Quantity
     */
    public function subtract(Quantity $other)
    {
        if (!$this->isSameUnit($other->getUnit())) {
            $convert = $other->convertTo($this->unit);
            return $this->subtract($convert);
        }

        return new Quantity($this->amount - $other->amount, $this->unit);
    }

    /**
     * @param $multiplier
     * @param int $roundMethod
     * @return Quantity
     */
    public function multiply($multiplier, $roundMethod = Quantity::ROUND_HALF_UP)
    {
        $this->assertOperand($multiplier);
        $this->assertRoundMethod($roundMethod);

        $rounded = round($this->amount * $multiplier, 2, $roundMethod);
        return new Quantity($rounded, $this->unit);
    }

    /**
     * Divide
     *
     * @param $divisor
     * @param int $roundMethod
     * @return Quantity
     */
    public function divide($divisor, $roundMethod = Quantity::ROUND_HALF_UP)
    {
        $this->assertOperand($divisor);
        $this->assertRoundMethod($roundMethod);

        $rounded = (int) round($this->amount / $divisor, 0, $roundMethod);
        return new Quantity($rounded, $this->unit);
    }

    /**
     * @param Quantity $other
     * @return int
     */
    public function compareTo(Quantity $other)
    {
        $this->assertSameUnit($other->getUnit());

        if ($this->amount < $other->amount) {
            return -1;
        } elseif ($this->amount == $other->amount) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * @param Quantity $other
     * @return bool
     */
    public function equals(Quantity $other)
    {
        return $this->isSameUnit($other->getUnit())
        && $this->amount == $other->getAmount();
    }

    /**
     * @param Quantity $other
     * @return bool
     */
    public function isLessThan(Quantity $other)
    {
        return $this->compareTo($other) == -1;
    }

    /**
     * @param Quantity $other
     * @return bool
     */
    public function isLessThanOrEqual(Quantity $other)
    {
        return $this->compareTo($other) >= 0;
    }

    /**
     * @param Quantity $other
     * @return bool
     */
    public function isGreaterThan(Quantity $other)
    {
        return $this->compareTo($other) == 1;
    }

    /**
     * @param Quantity $other
     * @return bool
     */
    public function isGreaterThanOrEqual(Quantity $other)
    {
        return $this->compareTo($other) <= 0;
    }

    /**
     * Is zero
     *
     * @return bool
     */
    public function isZero()
    {
        return $this->amount === 0;
    }
    /**
     * Is positive
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->amount > 0;
    }
    /**
     * Is negative
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->amount < 0;
    }

    /**
     * @param Unit $other
     * @return bool
     */
    public function isSameUnit(Unit $other)
    {
        return $this->unit->equals($other);
    }

    /**
     * @param Unit $other
     */
    private function assertSameUnit(Unit $other)
    {
        if (!$this->isSameUnit($other)) {
            throw new \InvalidArgumentException('Different Units');
        }
    }

    /**
     * Assert operand
     *
     * @param int|float $operand
     */
    private function assertOperand($operand)
    {
        if (!is_int($operand) && !is_float($operand)) {
            throw new \InvalidArgumentException('Operand must be an integer or a float');
        }
    }

    /**
     * Assert round method
     *
     * @param $roundMethod
     */
    private function assertRoundMethod($roundMethod)
    {
        $roundMethodsAllowed = [
            Quantity::ROUND_HALF_DOWN,
            Quantity::ROUND_HALF_EVEN,
            Quantity::ROUND_HALF_ODD,
            Quantity::ROUND_HALF_UP
        ];

        if (!in_array($roundMethod, $roundMethodsAllowed)) {
            throw new \InvalidArgumentException(
                'Rounding mode should be Distance::ROUND_HALF_DOWN
                | Distance::ROUND_HALF_EVEN
                | Distance::ROUND_HALF_ODD
                | Distance::ROUND_HALF_UP');
        }
    }
}