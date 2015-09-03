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

use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnCheckEvent;
use Elcodi\Component\Coupon\Exception\CouponBelowMinimumPurchaseException;
use Elcodi\Component\Currency\Services\CurrencyConverter;

/**
 * Class MinimumPriceCouponEventListener
 *
 * @author Berny Cantos <be@rny.cc>
 */
class MinimumPriceCouponEventListener
{
    /**
     * @var CurrencyConverter
     *
     * Currency converter
     */
    protected $currencyConverter;

    /**
     * Construct
     *
     * @param CurrencyConverter $currencyConverter
     */
    public function __construct(CurrencyConverter $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * Check if cart meets minimum price requirements for a coupon
     *
     * @param CartLineCouponOnCheckEvent $event Event
     *
     * @throws CouponBelowMinimumPurchaseException Minimum value not reached
     */
    public function checkMinimumPrice(CartLineCouponOnCheckEvent $event)
    {
        $couponMinimumPrice = $event
            ->getCoupon()
            ->getMinimumPurchase();

        if ($couponMinimumPrice->getAmount() === 0) {
            return null;
        }

        $productMoney = $event
            ->getCartLine()->getCart()
            ->getProductAmount();

        if ($couponMinimumPrice->getCurrency() != $productMoney->getCurrency()) {
            $couponMinimumPrice = $this
                ->currencyConverter
                ->convertMoney(
                    $couponMinimumPrice,
                    $productMoney->getCurrency()
                );
        }

        if ($productMoney->isLessThan($couponMinimumPrice)) {
            throw new CouponBelowMinimumPurchaseException();
        }
    }
}
