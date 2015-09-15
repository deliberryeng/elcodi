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

namespace Elcodi\Component\CartLineCoupon\Event;

use Symfony\Component\EventDispatcher\Event;

use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\OrderLineCouponInterface;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class OrderLineCouponOnApplyEvent
 */
class OrderLineCouponOnApplyEvent extends Event
{
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
     * @var OrderLineCouponInterface
     *
     * OrderLineCoupon
     */
    protected $orderLineCoupon;

    /**
     * Construct method
     *
     * @param OrderLineInterface  $orderLine  OrderLine
     * @param CouponInterface $coupon Coupon
     */
    public function __construct(
        OrderLineInterface $orderLine,
        CouponInterface $coupon
    ) {
        $this->orderLine = $orderLine;
        $this->coupon = $coupon;
    }

    /**
     * Set OrderLineCoupon
     *
     * @param OrderLineCouponInterface $orderLineCoupon OrderLineCoupon
     *
     * @return $this Self object
     */
    public function setOrderLineCoupon(OrderLineCouponInterface $orderLineCoupon)
    {
        $this->orderLineCoupon = $orderLineCoupon;

        return $this;
    }

    /**
     * Return orderLine
     *
     * @return OrderLineInterface OrderLine
     */
    public function getOrderLine()
    {
        return $this->orderLine;
    }

    /**
     * Return Coupon
     *
     * @return CouponInterface Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Get OrderLineCoupon
     *
     * @return OrderLineCouponInterface OrderLineCoupon
     */
    public function getOrderLineCoupon()
    {
        return $this->orderLineCoupon;
    }
}
