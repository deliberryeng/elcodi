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

namespace Elcodi\Component\CartLineCoupon\EventDispatcher;

use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\CartLineCoupon\ElcodiCartLineCouponEvents;
use Elcodi\Component\CartLineCoupon\Event\OrderLineCouponOnApplyEvent;
use Elcodi\Component\Core\EventDispatcher\Abstracts\AbstractEventDispatcher;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class OrderLineCouponEventDispatcher
 */
class OrderLineCouponEventDispatcher extends AbstractEventDispatcher
{
    /**
     * Dispatch event just before a coupon is applied into an OrderLine
     *
     * @param OrderLineInterface  $orderLine   OrderLine where to apply the coupon
     * @param CouponInterface $coupon Coupon to be applied
     *
     * @return $this Self object
     */
    public function dispatchOrderLineCouponOnApplyEvent(
        OrderLineInterface $cart,
        CouponInterface $coupon
    ) {
        $event = new OrderLineCouponOnApplyEvent(
            $cart,
            $coupon
        );

        $this->eventDispatcher->dispatch(
            ElcodiCartLineCouponEvents::ORDER_LINE_COUPON_ONAPPLY,
            $event
        );
    }
}
