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

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\CartLineCoupon\ElcodiCartLineCouponEvents;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnApplyEvent;
use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnCheckEvent;
use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnRejectedEvent;
use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnRemoveEvent;
use Elcodi\Component\Core\EventDispatcher\Abstracts\AbstractEventDispatcher;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class CartLineCouponEventDispatcher
 */
class CartLineCouponEventDispatcher extends AbstractEventDispatcher
{
    /**
     * Dispatch event to check a coupon applies to a CartLine
     *
     * @param CartLineInterface   $cartLine   CartLine where the coupon should apply
     * @param CouponInterface $coupon Coupon to be checked
     *
     * @return $this Self object
     */
    public function dispatchCartLineCouponOnCheckEvent(
        CartLineInterface $cartLine,
        CouponInterface $coupon
    ) {
        $event = new CartLineCouponOnCheckEvent($cartLine, $coupon);
        $this->eventDispatcher->dispatch(
            ElcodiCartLineCouponEvents::CART_LINE_COUPON_ONCHECK,
            $event
        );
    }

    /**
     * Dispatch event just before a coupon is applied into a CartLine
     *
     * @param CartLineInterface   $cartLine   CartLine where to apply the coupon
     * @param CouponInterface $coupon Coupon to be applied
     *
     * @return $this Self object
     */
    public function dispatchCartLineCouponOnApplyEvent(
        CartLineInterface $cartLine,
        CouponInterface $coupon
    ) {
        $event = new CartLineCouponOnApplyEvent($cartLine, $coupon);
        $this->eventDispatcher->dispatch(
            ElcodiCartLineCouponEvents::CART_LINE_COUPON_ONAPPLY,
            $event
        );
    }

    /**
     * Dispatch event just before a coupon is removed from a CartLine
     *
     * @param CartLineCouponInterface $cartLineCoupon CartLineCoupon to remove
     *
     * @return $this Self object
     */
    public function dispatchCartLineCouponOnRemoveEvent(
        CartLineCouponInterface $cartLineCoupon
    ) {
        $cartLine = $cartLineCoupon->getCartLine();
        $coupon = $cartLineCoupon->getCoupon();

        $event = new CartLineCouponOnRemoveEvent($cartLine, $coupon);
        $event->setCartLineCoupon($cartLineCoupon);
        $this->eventDispatcher->dispatch(
            ElcodiCartLineCouponEvents::CART_LINE_COUPON_ONREMOVE,
            $event
        );
    }

    /**
     * Dispatch event when a coupon application is rejected
     *
     * @param CartLineInterface   $cartLine   CartLine where the coupon should be rejected
     * @param CouponInterface $coupon Rejected coupon
     *
     * @return $this Self object
     */
    public function dispatchCartLineCouponOnRejectedEvent(
        CartLineInterface $cartLine,
        CouponInterface $coupon
    ) {
        $event = new CartLineCouponOnRejectedEvent($cartLine, $coupon);
        $this->eventDispatcher->dispatch(
            ElcodiCartLineCouponEvents::CART_LINE_COUPON_ONREJECTED,
            $event
        );
    }
}
