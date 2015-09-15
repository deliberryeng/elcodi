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

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;

use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\Cart\Event\OrderLineOnCreatedEvent;
use Elcodi\Component\CartLineCoupon\EventDispatcher\OrderLineCouponEventDispatcher;
use Elcodi\Component\CartLineCoupon\Services\CartLineCouponManager;
use Elcodi\Component\CartLineCoupon\Services\OrderLineCouponManager;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;
use Elcodi\Component\Currency\Entity\Interfaces\MoneyInterface;

/**
 * Class ConvertToOrderLineCouponsEventListener
 */
class ConvertToOrderLineCouponsEventListener
{
    /**
     * @var OrderLineCouponEventDispatcher
     *
     * orderLineCouponEventDispatcher
     */
    protected $orderLineCouponEventDispatcher;

    /**
     * @var CartLineCouponManager
     *
     * CartLineCoupon manager
     */
    protected $cartLineCouponManager;

    /**
     * @var OrderLineCouponManager
     *
     * OrderLineCoupon manager
     */
    protected $orderLineCouponManager;

    /**
     * @var ObjectManager
     *
     * OrderLineCoupon object manager
     */
    protected $orderLineCouponObjectManager;

    /**
     * construct method
     *
     * @param OrderLineCouponEventDispatcher $orderLineCouponEventDispatcher OrderLineCouponEventDispatcher
     * @param CartLineCouponManager          $cartLineCouponManager          CartLineCoupon manager
     * @param OrderLineCouponManager         $orderLineCouponManager         OrderLineCoupon manager
     * @param ObjectManager              $orderLineCouponObjectManager   OrderLineCoupon Object Manager
     */
    public function __construct(
        OrderLineCouponEventDispatcher $orderLineCouponEventDispatcher,
        CartLineCouponManager $cartLineCouponManager,
        OrderLineCouponManager $orderLineCouponManager,
        ObjectManager $orderLineCouponObjectManager
    ) {
        $this->orderLineCouponEventDispatcher = $orderLineCouponEventDispatcher;
        $this->cartLineCouponManager = $cartLineCouponManager;
        $this->orderLineCouponManager = $orderLineCouponManager;
        $this->orderLineCouponObjectManager = $orderLineCouponObjectManager;
    }

    /**
     * A new OrderLine has been created.
     *
     * This method adds Coupon logic in this transformation
     *
     * @param OrderLineOnCreatedEvent $orderLineOnCreatedEvent OrderLineOnCreated Event
     */
    public function convertCouponToOrderLine(OrderLineOnCreatedEvent $orderLineOnCreatedEvent)
    {
        $order = $orderOnCreatedEvent->getOrder();
        $cartLine = $orderOnCreatedEvent->getCartLine();
        $cartLineCouponAmount = $cartLine->getCouponAmount();

        if ($cartLineCouponAmount instanceof MoneyInterface) {
            $orderLine->setCouponAmount($cartLineCouponAmount);
        }

        /**
         * @var CouponInterface[]|Collection $coupons
         */
        $coupons = $this
            ->cartLineCouponManager
            ->getCoupons($cartLine);

        if ($coupons->isEmpty()) {
            return null;
        }

        /**
         * Before applying Coupons to OrderLine, we should remove old references
         * if exist. Otherwise,
         */
        $this->truncateOrderLineCoupons($orderLine);

        /**
         * An event is dispatched for each convertible coupon
         */
        foreach ($coupons as $coupon) {
            $this
                ->orderLineCouponEventDispatcher
                ->dispatchOrderLineCouponOnApplyEvent(
                    $orderLine,
                    $coupon
                );
        }
    }

    /**
     * Purge existing OrderLineCoupons
     *
     * @param OrderLineInterface $orderLine OrderLine where to delete all coupons
     *
     * @return $this Self object
     */
    protected function truncateOrderLineCoupons(OrderLineInterface $orderLine)
    {
        $orderLineCoupons = $this
            ->orderLineCouponManager
            ->getOrderLineCoupons($orderLine);

        if ($orderLineCoupons instanceof Collection) {
            foreach ($orderLineCoupons as $orderLineCoupon) {
                $this
                    ->orderLineCouponObjectManager
                    ->remove($orderLineCoupon);
            }

            $this
                ->orderLineCouponObjectManager
                ->flush($orderLineCoupons->toArray());
        }

        return $this;
    }
}
