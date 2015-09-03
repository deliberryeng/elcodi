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

use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnApplyEvent;
use Elcodi\Component\CartLineCoupon\Exception\CouponAlreadyAppliedException;
use Elcodi\Component\CartLineCoupon\Repository\CartLineCouponRepository;

/**
 * Class AvoidDuplicatesEventListener
 */
class AvoidDuplicatesEventListener
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
     * Check if this coupon is already applied to the cartLine
     *
     * @param CartLineCouponOnApplyEvent $event Event
     *
     * @throws CouponAlreadyAppliedException
     */
    public function checkDuplicates(CartLineCouponOnApplyEvent $event)
    {
        $cartLineCoupon = $this
            ->cartLineCouponRepository
            ->findOneBy([
                'cartLine' => $event->getCartLine(),
                'coupon'   => $event->getCoupon(),
            ]);

        if (null !== $cartLineCoupon) {
            throw new CouponAlreadyAppliedException();
        }
    }
}
