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

namespace Elcodi\Component\CartLineCoupon\Event\Abstracts;

use Symfony\Component\EventDispatcher\Event;

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class AbstractCartLineCouponEvent
 */
abstract class AbstractCartLineCouponEvent extends Event
{
    /**
     * @var CartLineInterface
     *
     * CartLine
     */
    protected $cartLine;

    /**
     * @var CouponInterface
     *
     * Coupon
     */
    protected $coupon;

    /**
     * @var CartLineCouponInterface
     *
     * CartLineCoupon
     */
    protected $cartLineCoupon;

    /**
     * Construct method
     *
     * @param CartLineInterface   $cartLine   CartLine
     * @param CouponInterface $coupon Coupon
     */
    public function __construct(CartLineInterface $cartLine, CouponInterface $coupon)
    {
        $this->cartLine = $cartLine;
        $this->coupon = $coupon;
    }

    /**
     * Set CartLineCoupon
     *
     * @param CartLineCouponInterface $cartLineCoupon CartLineCoupon
     *
     * @return $this Self object
     */
    public function setCartLineCoupon(CartLineCouponInterface $cartLineCoupon)
    {
        $this->cartLineCoupon = $cartLineCoupon;

        return $this;
    }

    /**
     * Return cartLine
     *
     * @return CartLineInterface $cartLine
     */
    public function getCartLine()
    {
        return $this->cartLine;
    }

    /**
     * Return Coupon
     *
     * @return CouponInterface Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Get CartLineCoupon
     *
     * @return CartLineCouponInterface|null CartLine Coupon
     */
    public function getCartLineCoupon()
    {
        return $this->cartLineCoupon;
    }
}
