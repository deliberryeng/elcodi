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

use Doctrine\Common\Persistence\ObjectManager;

use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnApplyEvent;
use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnRemoveEvent;
use Elcodi\Component\CartLineCoupon\Factory\CartLineCouponFactory;

/**
 * Class CartLineCouponManagerEventListener
 *
 * These Event Listeners are
 */
class CartLineCouponManagerEventListener
{
    /**
     * @var ObjectManager
     *
     * cartLineCouponObjectManager
     */
    protected $cartLineCouponObjectManager;

    /**
     * @var CartLineCouponFactory
     *
     * cartLineCouponFactory
     */
    protected $cartLineCouponFactory;

    /**
     * Construct method
     *
     * @param ObjectManager         $cartLineCouponObjectManager CartLineCoupon ObjectManager
     * @param CartLineCouponFactory $cartLineCouponFactory       CartLineCoupon Factory
     */
    public function __construct(
        ObjectManager $cartLineCouponObjectManager,
        CartLineCouponFactory $cartLineCouponFactory
    ) {
        $this->cartLineCouponObjectManager = $cartLineCouponObjectManager;
        $this->cartLineCouponFactory       = $cartLineCouponFactory;
    }

    /**
     * Applies Coupon in CartLine, and flushes it
     *
     * @param CartLineCouponOnApplyEvent $event Event
     */
    public function onCartLineCouponApply(CartLineCouponOnApplyEvent $event)
    {
        $cartLine = $event->getCartLine();
        $coupon = $event->getCoupon();

        /**
         * We create a new instance of CartLineCoupon.
         * We also persist and flush relation
         */
        $cartLineCoupon = $this
            ->cartLineCouponFactory
            ->create()
            ->setCartLine($cartLine)
            ->setCoupon($coupon);

        $this
            ->cartLineCouponObjectManager
            ->persist($cartLineCoupon);

        $this
            ->cartLineCouponObjectManager
            ->flush($cartLineCoupon);

        $event->setCartLineCoupon($cartLineCoupon);
    }

    /**
     * Removes Coupon from CartLine, and flushes it
     *
     * @param CartLineCouponOnRemoveEvent $event Event
     */
    public function onCartLineCouponRemove(CartLineCouponOnRemoveEvent $event)
    {
        $cartLineCoupon = $event->getCartLineCoupon();

        $this
            ->cartLineCouponObjectManager
            ->remove($cartLineCoupon);

        $this
            ->cartLineCouponObjectManager
            ->flush($cartLineCoupon);
    }
}
