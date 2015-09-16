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

use Doctrine\Common\Persistence\ObjectRepository;

use Elcodi\Component\Cart\Event\CartOnLoadEvent;
use Elcodi\Component\CartLineCoupon\Services\CartLineCouponManager;
use Elcodi\Component\Coupon\ElcodiCouponTypes;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;
use Elcodi\Component\Coupon\Exception\Abstracts\AbstractCouponException;

/**
 * Class AutomaticCouponApplicatorEventListener
 */
class AutomaticCouponApplicatorEventListener
{
    /**
     * @var CartLineCouponManager
     *
     * Coupon manager
     */
    protected $cartLineCouponManager;

    /**
     * @var ObjectRepository
     *
     * Coupon Repository
     */
    protected $couponRepository;

    /**
     * Construct method
     *
     * @param CartLineCouponManager $cartLineCouponManager Manager for cart coupon rules
     * @param ObjectRepository  $couponRepository  Repository to get coupons
     */
    public function __construct(
        CartLineCouponManager $cartLineCouponManager,
        ObjectRepository $couponRepository
    ) {
        $this->cartLineCouponManager = $cartLineCouponManager;
        $this->couponRepository      = $couponRepository;
    }

    /**
     * Method subscribed to PreCartLoad event
     *
     * Iterate over all automatic Product Coupons and check if they apply.
     * If any applies, it will be added to the CartLine
     *
     * @param CartOnLoadEvent $event Event
     */
    public function tryAutomaticCoupons(CartOnLoadEvent $event)
    {
        $cart      = $event->getCart();
        $cartLines = $cart->getCartLines();

        if ($cartLines->isEmpty()) {
            return null;
        }

        foreach ($cartLines as $cartLine){
            /**
            * @var CouponInterface[] $productCoupons
            */
            $productCoupons = $cartLine->getProduct()->getCoupons();
            foreach ($productCoupons as $coupon) {
                try {
                    $this
                        ->cartLineCouponManager
                        ->addCoupon($cartLine, $coupon);
                } catch (AbstractCouponException $e) {
                    // Silently tries next coupon on controlled exception
                }
            }
        }
    }

}
