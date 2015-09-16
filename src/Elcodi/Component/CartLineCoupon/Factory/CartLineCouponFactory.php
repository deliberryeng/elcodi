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

namespace Elcodi\Component\CartLineCoupon\Factory;

use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\Currency\Factory\Abstracts\AbstractPurchasableFactory;

/**
 * Class CartLineCoupon
 */
class CartLineCouponFactory extends AbstractPurchasableFactory
{
    /**
     * Creates an instance of an entity.
     *
     * This method must return always an empty instance for related entity
     *
     * @return CartLineCoupon New CartLineCoupon instance
     */
    public function create()
    {
        $zeroPrice = $this->createZeroAmountMoney();

        $classNamespace = $this->getEntityNamespace();
        $cartLineCoupon = new $classNamespace();
        $cartLineCoupon->setAmount($zeroPrice);

        return $cartLineCoupon;
    }
}
