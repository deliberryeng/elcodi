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

namespace Elcodi\Component\CartLineCoupon\Repository;

use Doctrine\ORM\EntityRepository;

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;

/**
 * Class CartLineCouponRepository
 */
class CartLineCouponRepository extends EntityRepository
{

    /**
     * Find exact cart line coupon entity by cart line and coupon
     *
     * @param CartLineInterface $cartLine 
     * @param CouponInterface   $coupon
     *
     * @return CartLineCouponInterface
     */
    public function findByCartLineAndCoupon(
        CartLineInterface $cartLine,
        CouponInterface $coupon
    ){
        $queryBuilder = $this->_em
            ->createQueryBuilder();
    
        $result = $queryBuilder
            ->select('CartLineCoupon')
            ->from('Elcodi\Component\CartLineCoupon\Entity\CartLineCoupon', 'CartLineCoupon')
            ->where('CartLineCoupon.cartLine = :cartLineId')
            ->andWhere('CartLineCoupon.coupon = :couponId')
            ->setParameters(array(
                'cartLineId' => $cartLine->getId(),
                'couponId' => $coupon->getId()
            ))
            ->getQuery()
            ->getSingleResult();

        return $result;
    }
}
