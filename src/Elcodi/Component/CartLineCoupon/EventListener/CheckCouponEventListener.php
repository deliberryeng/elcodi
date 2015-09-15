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
use Elcodi\Component\Coupon\Exception\Abstracts\AbstractCouponException;
use Elcodi\Component\Coupon\Exception\CouponIncompatibleException;
use Elcodi\Component\Coupon\Services\CouponManager;

/**
 * Class CheckCouponEventListener
 *
 * @author Berny Cantos <be@rny.cc>
 */
class CheckCouponEventListener
{
    /**
     * @var CouponManager
     */
    protected $couponManager;

    /**
     * Constructor
     *
     * @param CouponManager $couponManager
     */
    public function __construct(CouponManager $couponManager)
    {
        $this->couponManager = $couponManager;
    }

    /**
     * Check if cart meets basic requirements for a coupon
     *
     * @param CartLineCouponOnCheckEvent $event
     *
     * @throws AbstractCouponException
     */
    public function checkCoupon(CartLineCouponOnCheckEvent $event)
    {
        if ($event->getLineCart()->getCart()->getQuantity() === 0) {
            throw new CouponIncompatibleException();
        }

        $coupon = $event->getCoupon();

        $this
            ->couponManager
            ->checkCoupon($coupon);
    }
}
