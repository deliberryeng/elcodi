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

namespace Elcodi\Component\CartLineCoupon\Entity;

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\Core\Entity\Traits\IdentifiableTrait;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class CartLineCoupon
 */
class CartLineCoupon implements CartLineCouponInterface
{
    use IdentifiableTrait;

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
     * Sets CartLine
     *
     * @param CartLineInterface $cart CartLine
     *
     * @return $this Self object
     */
    public function setCartLine(CartLineInterface $cartLine)
    {
        $this->cartLine = $cartLine;

        return $this;
    }

    /**
     * Get CartLine
     *
     * @return CartLineInterface CartLine
     */
    public function getCartLine()
    {
        return $this->cartLine;
    }

    /**
     * Sets Coupon
     *
     * @param CouponInterface $coupon Coupon
     *
     * @return $this Self object
     */
    public function setCoupon(CouponInterface $coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get Coupon
     *
     * @return CouponInterface Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }
}
