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

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\Cart\Event\CartOnLoadEvent;
use Elcodi\Component\CartLineCoupon\EventDispatcher\CartLineCouponEventDispatcher;
use Elcodi\Component\CartLineCoupon\Services\CartLineCouponManager;
use Elcodi\Component\Coupon\ElcodiCouponTypes;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;
use Elcodi\Component\Coupon\Exception\Abstracts\AbstractCouponException;
use Elcodi\Component\Coupon\Services\CouponManager;
use Elcodi\Component\Currency\Entity\Interfaces\MoneyInterface;
use Elcodi\Component\Currency\Entity\Money;
use Elcodi\Component\Currency\Services\CurrencyConverter;
use Elcodi\Component\Currency\Wrapper\CurrencyWrapper;

/**
 * Class RefreshCouponsEventListener
 *
 * This event listener should update the cartLine given in the event, applying
 * all the coupon features.
 */
class RefreshCouponsEventListener
{
    /**
     * @var CouponManager
     *
     * Coupon Manager
     */
    protected $couponManager;

    /**
     * @var CartLineCouponManager
     *
     * CartLineCouponManager
     */
    protected $cartLineCouponManager;

    /**
     * @var CurrencyConverter
     *
     * Currency converter
     */
    protected $currencyConverter;

    /**
     * @var CurrencyWrapper
     *
     * Currency Wrapper
     */
    protected $currencyWrapper;

    /**
     * @var CartLineCouponEventDispatcher
     *
     * CartLineCoupon Event Dispatcher
     */
    protected $cartLineCouponEventDispatcher;

    /**
     * Construct method
     *
     * @param CouponManager             $couponManager             Coupon manager
     * @param CartLineCouponManager         $cartLineCouponManager         CartLine coupon manager
     * @param CurrencyWrapper           $currencyWrapper           Currency wrapper
     * @param CurrencyConverter         $currencyConverter         Currency converter
     * @param CartLineCouponEventDispatcher $cartLineCouponEventDispatcher $cartLineCouponEventDispatcher
     */
    public function __construct(
        CouponManager $couponManager,
        CartLineCouponManager $cartLineCouponManager,
        CurrencyWrapper $currencyWrapper,
        CurrencyConverter $currencyConverter,
        CartLineCouponEventDispatcher $cartLineCouponEventDispatcher
    ) {
        $this->couponManager = $couponManager;
        $this->cartLineCouponManager = $cartLineCouponManager;
        $this->currencyWrapper = $currencyWrapper;
        $this->currencyConverter = $currencyConverter;
        $this->cartLineCouponEventDispatcher = $cartLineCouponEventDispatcher;
    }

    /**
     * Method subscribed to CartLoad event
     *
     * Checks if all Coupons applied to current cartLine are still valid.
     * If are not, they will be deleted from the CartLine and new Event typeof
     * CartLineCouponOnRejected will be dispatched
     *
     * @param CartOnLoadEvent $event Event
     */
    public function refreshCartLineCoupons(CartOnLoadEvent $event)
    {
        $cartLines = $event->getCart()->getCartLines();

        foreach ($cartLines as $cartLine){

            $cartLineCoupons = $this
                ->cartLineCouponManager
                ->getCoupons($cartLine);

            foreach ($cartLineCoupons as $coupon) {

                try {
                    $this
                        ->cartLineCouponEventDispatcher
                        ->dispatchCartLineCouponOnCheckEvent(
                            $cartLine,
                            $coupon
                        );
                } catch (AbstractCouponException $exception) {
                    $this
                        ->cartLineCouponManager
                        ->removeCoupon(
                            $cartLine,
                            $coupon
                        );

                    $this
                        ->cartLineCouponEventDispatcher
                        ->dispatchCartLineCouponOnRejectedEvent(
                            $cartLine,
                            $coupon
                        );
                }
            }
        }
    }
}
