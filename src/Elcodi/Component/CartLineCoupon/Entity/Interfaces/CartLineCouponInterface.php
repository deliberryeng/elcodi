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

namespace Elcodi\Component\CartLineCoupon\Entity\Interfaces;

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\Core\Entity\Interfaces\IdentifiableInterface;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;
use Elcodi\Component\Currency\Entity\Interfaces\MoneyInterface;

/**
 * Interface CartLineCouponInterface
 */
interface CartLineCouponInterface extends IdentifiableInterface
{
    /**
     * Sets CartLine
     *
     * @param CartLineInterface $cart CartLine
     *
     * @return $this Self object
     */
    public function setCartLine(CartLineInterface $cart);

    /**
     * Get CartLine
     *
     * @return CartLineInterface CartLine
     */
    public function getCartLine();

    /**
     * Sets Coupon
     *
     * @param CouponInterface $coupon Coupon
     *
     * @return $this Self object
     */
    public function setCoupon(CouponInterface $coupon);

    /**
     * Get Coupon
     *
     * @return CouponInterface Coupon
     */
    public function getCoupon();

    /**
     * Set amount
     *
     * @param MoneyInterface $amount Price
     *
     * @return $this Self object
     */
    public function setAmount(MoneyInterface $amount);

    /**
     * Get amount
     *
     * @return MoneyInterface Price
     */
    public function getAmount();
}
