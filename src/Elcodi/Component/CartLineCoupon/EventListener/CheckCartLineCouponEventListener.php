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

namespace Elcodi\Component\CartLineCoupon\EventListener;

use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnApplyEvent;
use Elcodi\Component\CartLineCoupon\EventDispatcher\CartLineCouponEventDispatcher;

/**
 * Class CheckCartLineCouponEventListener
 */
class CheckCartLineCouponEventListener
{
    /**
     * @var CartLineCouponEventDispatcher
     *
     * Event dispatcher for CartLineCoupon
     */
    protected $cartLineCouponDispatcher;

    /**
     * Construct method
     *
     * @param CartLineCouponEventDispatcher $cartLineCouponDispatcher Event dispatcher for CartLineCoupon
     */
    public function __construct(CartLineCouponEventDispatcher $cartLineCouponDispatcher)
    {
        $this->cartLineCouponDispatcher = $cartLineCouponDispatcher;
    }

    /**
     * Checks if a Coupon is applicable to a CartLine
     *
     * @param CartLineCouponOnApplyEvent $event
     *
     * @return boolean true if the coupon applies, false otherwise
     *
     */
    public function checkCoupon(CartLineCouponOnApplyEvent $event)
    {
        $this
            ->cartLineCouponDispatcher
            ->dispatchCartLineCouponOnCheckEvent(
                $event->getCartLine(),
                $event->getCoupon()
            );
    }
}
