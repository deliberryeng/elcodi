<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Component\CartLineCoupon\Entity;

use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\OrderLineCouponInterface;
use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;
use Elcodi\Component\Currency\Entity\Interfaces\CurrencyInterface;
use Elcodi\Component\Currency\Entity\Interfaces\MoneyInterface;
use Elcodi\Component\Currency\Entity\Money;

/**
 * Class OrderLineCoupon
 */
class OrderLineCoupon implements OrderLineCouponInterface
{
    use IdentifiableTrait;

    /**
     * @var OrderLineInterface
     *
     * OrderLine
     */
    protected $orderLine;

    /**
     * @var CouponInterface
     *
     * Coupon
     */
    protected $coupon;

    /**
     * @var integer
     *
     * Amount
     */
    protected $amount;

    /**
     * @var CurrencyInterface
     *
     * Amount currency
     */
    protected $amountCurrency;

    /**
     * @var string
     *
     * Code
     */
    protected $code;

    /**
     * @var string
     *
     * Name
     */
    protected $name;

    /**
     * Sets OrderLine
     *
     * @param OrderLineInterface $orderLine OrderLine
     *
     * @return $this Self object
     */
    public function setOrderLine(OrderLineInterface $orderLine)
    {
        $this->orderLine = $orderLine;

        return $this;
    }

    /**
     * Get OrderLine
     *
     * @return OrderLineInterface OrderLine
     */
    public function getOrderLine()
    {
        return $this->orderLine;
    }

    /**
     * Sets Coupon
     *
     * @param CouponInterface $coupon Coupon
     *
     * @return $this Self object
     */
    public function setCoupon(CouponInterface $coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get Coupon
     *
     * @return CouponInterface Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set code
     *
     * @param string $code Code
     *
     * @return $this Self object
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string Code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name coupon name
     *
     * @param string $name
     *
     * @return $this Self object
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set amount
     *
     * @param MoneyInterface $amount Price
     *
     * @return $this Self object
     */
    public function setAmount(MoneyInterface $amount)
    {
        $this->amount = $amount->getAmount();
        $this->amountCurrency = $amount->getCurrency();

        return $this;
    }

    /**
     * Get amount
     *
     * @return MoneyInterface Price
     */
    public function getAmount()
    {
        return Money::create(
            $this->amount,
            $this->amountCurrency
        );
    }
}