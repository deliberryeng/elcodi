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

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\CartLineCoupon\Entity\Interfaces\CartLineCouponInterface;
use Elcodi\Component\CartLineCoupon\EventDispatcher\CartLineCouponEventDispatcher;
use Elcodi\Component\CartLineCoupon\Repository\CartLineCouponRepository;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;
use Elcodi\Component\Coupon\Exception\Abstracts\AbstractCouponException;
use Elcodi\Component\Coupon\Exception\CouponNotAvailableException;
use Elcodi\Component\Coupon\Repository\CouponRepository;
use Elcodi\Component\Coupon\Services\CouponManager;

/**
 * Class CartLineCoupon Manager
 *
 * This class aims to be a bridge between Coupons and CartLines.
 * Manages all coupons instances inside CartLines
 */
class CartLineCouponManager
{
    /**
     * @var CartLineCouponEventDispatcher
     *
     * CartLineCoupon Event dispatcher
     */
    protected $cartLineCouponEventDispatcher;

    /**
     * @var CouponManager
     *
     * CouponManager
     */
    protected $couponManager;

    /**
     * @var CouponRepository
     *
     * Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var CartLineCouponRepository
     *
     * Coupon Repository
     */
    protected $cartLineCouponRepository;

    /**
     * Construct method
     *
     * @param CartLineCouponEventDispatcher $cartLineCouponEventDispatcher
     * @param CouponManager                 $couponManager
     * @param CouponRepository              $couponRepository
     * @param CartLineCouponRepository      $cartLineCouponRepository
     */
    public function __construct(
        CartLineCouponEventDispatcher $cartLineCouponEventDispatcher,
        CouponManager $couponManager,
        CouponRepository $couponRepository,
        CartLineCouponRepository $cartLineCouponRepository
    ) {
        $this->cartLineCouponEventDispatcher = $cartLineCouponEventDispatcher;
        $this->couponManager                 = $couponManager;
        $this->couponRepository              = $couponRepository;
        $this->cartLineCouponRepository      = $cartLineCouponRepository;
    }

    /**
     * Get CartLineCoupon instances assigned to current CartLine
     *
     * @param CartLineInterface $cartLine CartLine
     *
     * @return CartLineCouponInterface[]|Collection CartLineCoupons
     */
    public function getCartLineCoupons(CartLineInterface $cartLine)
    {
        /**
         * If CartLine id is null means that this cartLine has been generated from
         * scratch. This also means that it cannot have any Coupon associated.
         * If is this case, we avoid this lookup.
         */
        if ($cartLine->getId() === null) {
            return new ArrayCollection();
        }

        return new ArrayCollection(
            $this
                ->cartLineCouponRepository
                ->createQueryBuilder('clc')
                ->where('clc.cartLine = :cartLine')
                ->setParameter('cartLine', $cartLine)
                ->getQuery()
                ->getResult()
        );
    }

    /**
     * Get cartLine coupon objects
     *
     * @param CartLineInterface $cartLine CartLine
     *
     * @return Collection Coupons
     */
    public function getCoupons(CartLineInterface $cartLine)
    {
        /**
         * If CartLine id is null means that this cartLine has been generated from
         * scratch. This also means that it cannot have any Coupon associated.
         * If is this case, we avoid this lookup.
         */
        if ($cartLine->getId() === null) {
            return new ArrayCollection();
        }

        $cartLineCoupons = $this
            ->cartLineCouponRepository
            ->createQueryBuilder('clc')
            ->select(['c', 'clc'])
            ->innerJoin('clc.coupon', 'c')
            ->where('clc.cartLine = :cartLine')
            ->setParameter('cartLine', $cartLine)
            ->getQuery()
            ->getResult();

        $coupons = array_map(function (CartLineCouponInterface $cartLineCoupon) {
            return $cartLineCoupon->getCoupon();
        }, $cartLineCoupons);

        return new ArrayCollection($coupons);
    }

    /**
     * Given a coupon code, applies it to cartLine
     *
     * @param CartLineInterface $cartLine   CartLine
     * @param string            $couponCode Coupon code
     *
     * @throws AbstractCouponException
     *
     * @return boolean Coupon has added to CartLine
     */
    public function addCouponByCode(CartLineInterface $cartLine, $couponCode)
    {
        $coupon = $this
            ->couponRepository
            ->findOneBy([
                'code'    => $couponCode,
                'enabled' => true,
            ]);

        if (false === $coupon instanceof CouponInterface) {
            throw new CouponNotAvailableException();
        }

        return $this->addCoupon($cartLine, $coupon);
    }

    /**
     * Adds a Coupon to a CartLine and recalculates the Cart and CartLine Totals
     *
     * @param CartLineInterface $cartLine CartLine
     * @param CouponInterface   $coupon   The coupon to add
     *
     * @throws AbstractCouponException
     *
     * @return $this Self object
     */
    public function addCoupon(CartLineInterface $cartLine, CouponInterface $coupon)
    {
        $this
            ->cartLineCouponEventDispatcher
            ->dispatchCartLineCouponOnApplyEvent(
                $cartLine,
                $coupon
            );

        return $this;
    }

    /**
     * Given a coupon code, removes it from cartLine
     *
     * @param CartLineInterface $cartLine   CartLine
     * @param string            $couponCode Coupon code
     *
     * @return boolean Coupon has been removed from cartLine
     */
    public function removeCouponByCode(CartLineInterface $cartLine, $couponCode)
    {
        $coupon = $this
            ->couponRepository
            ->findOneBy([
                'code' => $couponCode,
            ]);

        if (!($coupon instanceof CouponInterface)) {
            return false;
        }

        return $this->removeCoupon($cartLine, $coupon);
    }

    /**
     * Removes a Coupon from a CartLine, and recalculates Cart and CartLine Totals
     *
     * @param CartLineInterface $cartLine CartLine
     * @param CouponInterface   $coupon   The coupon to remove
     *
     * @return boolean Coupon has been removed from cartLine
     */
    public function removeCoupon(CartLineInterface $cartLine, CouponInterface $coupon)
    {
        $cartLineCoupons = $this
            ->cartLineCouponRepository
            ->findBy([
                'cartLine' => $cartLine,
                'coupon'   => $coupon,
            ]);

        if (empty($cartLineCoupons)) {
            return false;
        }

        foreach ($cartLineCoupons as $cartLineCoupon) {
            $this
                ->cartLineCouponEventDispatcher
                ->dispatchCartLineCouponOnRemoveEvent(
                    $cartLineCoupon
                );
        }

        return true;
    }
}
