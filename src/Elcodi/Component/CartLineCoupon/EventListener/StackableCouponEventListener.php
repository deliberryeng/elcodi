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

use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnApplyEvent;
use Elcodi\Component\CartLineCoupon\Exception\CouponNotStackableException;
use Elcodi\Component\CartLineCoupon\Repository\CartLineCouponRepository;

/**
 * Class StackableCouponEventListener
 */
class StackableCouponEventListener
{
    /**
     * @var CartLineCouponRepository
     *
     * CartLineCoupon Repository
     */
    protected $cartLineCouponRepository;

    /**
     * Construct method
     *
     * @param CartLineCouponRepository $cartLineCouponRepository Repository where to find cartLinecoupons
     */
    public function __construct(CartLineCouponRepository $cartLineCouponRepository)
    {
        $this->cartLineCouponRepository = $cartLineCouponRepository;
    }

    /**
     * Check if this coupon can be applied when other coupons had previously been applied
     *
     * @param CartLineCouponOnApplyEvent $event Event
     *
     * @throws CouponNotStackableException
     */
    public function checkStackableCoupon(CartLineCouponOnApplyEvent $event)
    {
        $cartLineCoupons = $this
            ->cartLineCouponRepository
            ->findBy([
                'cartLine' => $event->getCartLine(),
            ]);

        /*
         * If there are no previously applied coupons we can skip the check
         */
        if (0 == count($cartLineCoupons)) {
            return;
        }

        $coupon = $event->getCoupon();

        $appliedCouponsCanBeStacked = array_reduce(
            $cartLineCoupons,
            function ($previousCouponsAreStackable, CartLineCouponInterface $cc) {

                return $previousCouponsAreStackable && $cc->getCoupon()->getStackable();
            },
            true
        );

        /*
         * Checked coupon can be stackable and everithing that was
         * previosuly applied is also stackable
         */
        if ($coupon->getStackable() && $appliedCouponsCanBeStacked) {
            return;
        }

        throw new CouponNotStackableException();
    }
}
