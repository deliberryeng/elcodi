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

use Elcodi\Component\CartLineCoupon\Event\OrderLineCouponOnApplyEvent;
use Elcodi\Component\CartLineCoupon\Factory\OrderLineCouponFactory;
use Elcodi\Component\CartLineCoupon\Repository\CartLineCouponRepository;
use Elcodi\Component\Coupon\EventDispatcher\CouponEventDispatcher;

/**
 * Class OrderLineCouponManagerEventListener
 *
 * This eventListener is subscribed into OpenCoupon events.
 */
class OrderLineCouponManagerEventListener
{
    /**
     * @var ObjectManager
     *
     * orderLineCouponObjectManager
     */
    protected $orderLineCouponObjectManager;

    /**
     * @var CouponEventDispatcher
     *
     * CouponEventDispatcher
     */
    protected $couponEventDispatcher;

    /**
     * @var OrderLineCouponFactory
     *
     * orderLineCouponFactory
     */
    protected $orderLineCouponFactory;

    /**
     * @var CartLineCouponRepository
     *
     * cartLineCouponRepository
     */
    protected $cartLineCouponRepository;

    /**
     * construct method
     *
     * @param ObjectManager            $orderLineCouponObjectManager OrderLineCoupon ObjectManager
     * @param CouponEventDispatcher    $couponEventDispatcher        CouponEventDispatcher
     * @param OrderLineCouponFactory   $orderLineCouponFactory       OrderLineCoupon factory
     * @param CartLineCouponRepository $cartLineCouponRepository     CartLineCoupon Repository
     */
    public function __construct(
        ObjectManager $orderLineCouponObjectManager,
        CouponEventDispatcher $couponEventDispatcher,
        OrderLineCouponFactory $orderLineCouponFactory,
        CartLineCouponRepository $cartLineCouponRepository
    ) {
        $this->orderLineCouponObjectManager = $orderLineCouponObjectManager;
        $this->couponEventDispatcher        = $couponEventDispatcher;
        $this->orderLineCouponFactory       = $orderLineCouponFactory;
        $this->cartLineCouponRepository     = $cartLineCouponRepository;
    }

    /**
     * Event subscribed on OrderLineCoupon applied into an orderLine.
     *
     * Just should create a new OrderLineCoupon instance, persist and flush it
     *
     * Also notifies to CouponBundle that a simple coupon has been
     * used by an OrderLine.
     *
     * @param OrderLineCouponOnApplyEvent $event Event
     */
    public function convertToOrderLineCoupons(OrderLineCouponOnApplyEvent $event)
    {
        $cartLine  = $event->getCartLine();
        $orderLine = $event->getOrderLine();
        $coupon    = $event->getCoupon();

        $cartLineCoupon = $this->cartLineCouponRepository
            ->findByCartLineAndCoupon($cartLine, $coupon);

        $orderLineCoupon = $this
            ->orderLineCouponFactory
            ->create()
            ->setOrderLine($orderLine)
            ->setCoupon($coupon)
            ->setAmount($cartLineCoupon->getAmount())
            ->setName($coupon->getName())
            ->setCode($coupon->getCode());

        $this
            ->orderLineCouponObjectManager
            ->persist($orderLineCoupon);
    }
}
