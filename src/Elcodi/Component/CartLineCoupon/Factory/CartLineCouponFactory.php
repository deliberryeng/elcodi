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

use Elcodi\Component\CartLineCoupon\Entity\CartLineCoupon;
use Elcodi\Component\Core\Factory\Abstracts\AbstractFactory;

/**
 * Class CartLineCoupon
 */
class CartLineCouponFactory extends AbstractFactory
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
        $classNamespace = $this->getEntityNamespace();
        $cartLineCoupon = new $classNamespace();

        return $cartLineCoupon;
    }
}
