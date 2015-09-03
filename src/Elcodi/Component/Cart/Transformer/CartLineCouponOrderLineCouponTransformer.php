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
 * @author Eva Perales <eva.perales@gmail.com>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Component\Cart\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\OrderLineCouponInterface;
use Elcodi\Component\CartLineCoupon\Factory\OrderLineCouponFactory;

/**
 * Class CartLineCouponOrderLineCouponTransformer
 *
 * Api Methods:
 *
 * * createOrderLineCouponsByCartLineCoupons(OrderLineInterface, Collection) : Collection
 * * createOrderLineCouponFromCartLineCoupon(OrderLineInterface, CartLineCouponInterface) : OrderLineCouponInterface
 */
class CartLineCouponOrderLineCouponTransformer
{

    /**
     * @var OrderLineCouponFactory.php
     *
     * OrderLineCoupon factory
     */
    protected $orderLineCouponFactory;

    /**
     * Construct method
     *
     * @param OrderLineCouponFactory $orderLineCouponFactory OrderLineCouponFactory
     */
    public function __construct(
        OrderLineCouponFactory $orderLineCouponFactory
    ) {
        $this->orderLineCouponFactory = $orderLineCouponFactory;
    }

    /**
     * Given a set of CartLinesCoupon, return a set of OrderLinesCoupons 
     *
     * @param OrderLineInterface $orderLine       OrderLine
     * @param Collection         $cartLineCoupons Set of CartLineCoupons
     *
     * @return Collection Set of OrderLineCoupons
     */
    public function createOrderLineCouponsByCartLineCoupons(
        OrderLineInterface $orderLine,
        Collection $cartLineCoupons
    ) {
        $orderLineCoupons = new ArrayCollection();

        /**
         * @var CartLineCouponInterface $cartLine
         */
        foreach ($cartLineCoupons as $cartLineCoupon) {
            $orderLineCoupon = $this
                ->createOrderLineCouponByCartLineCoupon(
                    $orderLine,
                    $cartLineCoupon
                );

            $orderLineCoupons->add($orderLineCoupon);
        }

        return $orderLineCoupons;
    }

    /**
     * Given a cart line coupon, creates a new order line coupon
     *
     * @param OrderLineInterface      $orderLine      OrderLine
     * @param CartLineCouponInterface $cartLineCoupon Cart Line Coupon
     *
     * @return OrderLineCouponInterface OrderLineCoupon created
     */
    public function createOrderLineCouponByCartLineCoupon(
        OrderLineInterface $orderLine,
        CartLineCouponInterface $cartLineCoupon
    ) {
        $orderLineCoupon = $this->orderLineCouponFactory->create();

        /**
         * @var OrderLineCouponInterface $orderLineCoupon
         */
        $coupon = $cartLineCoupon->getCoupon();
        $orderLineCoupon
            ->setOrderLine($orderLine)
            ->setCoupon($coupon)
            ->setAmount($cartLineCoupon->getAmount())
            ->setCode($coupon->getCode())
            ->setName($coupon->getName());

        return $orderLineCoupon;
    }
}
