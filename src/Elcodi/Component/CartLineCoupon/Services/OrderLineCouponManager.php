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

namespace Elcodi\Component\CartLineCoupon\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Elcodi\Component\Cart\Entity\Interfaces\OrderLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\OrderLineCouponInterface;
use Elcodi\Component\CartLineCoupon\Repository\OrderLineCouponRepository;

/**
 * Class OrderLineCoupon Manager
 *
 * This class aims to be a bridge between Coupons and OrderLines.
 * Manages all coupons instances inside OrderLines
 *
 * Public methods:
 *
 * getOrderLineCoupons(OrderLineInterface)
 */
class OrderLineCouponManager
{
    /**
     * @var OrderLineCouponRepository
     *
     * orderLineCouponRepository
     */
    protected $orderLineCouponRepository;

    /**
     * construct method
     *
     * @param OrderLineCouponRepository $orderLineCouponRepository OrderLineCoupon Repository
     */
    public function __construct(OrderLineCouponRepository $orderLineCouponRepository)
    {
        $this->orderLineCouponRepository = $orderLineCouponRepository;
    }

    /**
     * Get OrderLineCoupon instances assigned to current orderLine
     *
     * @param OrderLineInterface $orderLine OrderLine
     *
     * @return Collection OrderLineCoupons
     */
    public function getOrderLineCoupons(OrderLineInterface $orderLine)
    {
        return new ArrayCollection(
            $this
                ->orderLineCouponRepository
                ->createQueryBuilder('olc')
                ->where('olc.order_line = :orderLine')
                ->setParameter('orderLine', $orderLine)
                ->getQuery()
                ->getResult()
        );
    }

    /**
     * Get orderLine coupon Objects
     *
     * @param OrderLineInterface $orderLine OrderLine
     *
     * @return Collection Coupons
     */
    public function getCoupons(OrderInterface $orderLine)
    {
        $orderLineCoupons = $this
            ->orderLineCouponRepository
            ->createQueryBuilder('olc')
            ->select(['o', 'olc'])
            ->innerJoin('olc.coupon', 'c')
            ->where('olc.order_line = :orderLine')
            ->setParameter('orderLine', $orderLine)
            ->getQuery()
            ->getResult();

        $coupons = array_map(function (OrderLineCouponInterface $orderLineCoupon) {
            return $orderLineCoupon->getCoupon();
        }, $orderLineCoupons);

        return new ArrayCollection($coupons);
    }
}
