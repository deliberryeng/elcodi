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
 * @author Eva Perales <eva.perales@gmail.com>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Bundle\CartLineCouponBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Elcodi\Bundle\CoreBundle\DataFixtures\ORM\Abstracts\AbstractFixture;
use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class CartLineCouponData
 */
class CartLineCouponData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /**
         * CartLines
         *
         * @var CartLineCouponInterface $cartLineCoupon
         * @var CartLineInterface       $cartLine
         * @var CouponInterface         $coupon
         */
        $cartLineCouponDirectory = $this->getDirector('cart_line_coupon');

        $cartLine = $this->getReference('full-cart-line');
        $coupon = $this->getReference('coupon-percent');

        $cartLineCoupon = $cartLineCouponDirectory
            ->create()
            ->setCartLine($cartLine)
            ->setCoupon($coupon);

        $cartLineCouponDirectory->save($cartLineCoupon);
        $this->addReference('cart-line-coupon', $cartLineCoupon);
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'Elcodi\Bundle\CartLineBundle\DataFixtures\ORM\CartLineData',
            'Elcodi\Bundle\CouponBundle\DataFixtures\ORM\CouponData',
        ];
    }
}
