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
use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\OrderLineCouponInterface;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class OrderLineCouponData
 */
class OrderLineCouponData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /**
         * OrderLines
         *
         * @var OrderLineCouponInterface $orderLineCoupon
         * @var OrderLineInterface       $orderLine
         * @var CouponInterface         $coupon
         */
        $orderLineCouponDirectory = $this->getDirector('order_line_coupon');

        $orderLine = $this->getReference('full-order-line');
        $coupon = $this->getReference('coupon-percent');

        $orderLineCoupon = $orderLineCouponDirectory
            ->create()
            ->setOrderLine($orderLine)
            ->setCoupon($coupon);

        $orderLineCouponDirectory->save($orderLineCoupon);
        $this->addReference('order-line-coupon', $orderLineCoupon);
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
            'Elcodi\Bundle\OrderLineBundle\DataFixtures\ORM\OrderLineData',
            'Elcodi\Bundle\CouponBundle\DataFixtures\ORM\CouponData',
        ];
    }
}
