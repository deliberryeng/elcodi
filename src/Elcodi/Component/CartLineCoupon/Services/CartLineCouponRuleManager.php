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

use Elcodi\Component\Cart\Entity\Interfaces\CartLineInterface;
use Elcodi\Component\Coupon\Entity\Interfaces\CouponInterface;
use Elcodi\Component\Coupon\ElcodiCouponTypes;
use Elcodi\Component\Rule\Services\Interfaces\RuleManagerInterface;

/**
 * Class CartLineCouponRuleManager
 */
class CartLineCouponRuleManager
{
    /**
     * @var RuleManagerInterface
     *
     * Rule manager
     */
    protected $ruleManager;

    /**
     * Construct method
     *
     * @param RuleManagerInterface $ruleManager Rule manager
     */
    public function __construct(RuleManagerInterface $ruleManager)
    {
        $this->ruleManager = $ruleManager;
    }


    /**
     * Evaluate discount rules to dertermine discount coupon amount
     *
     * @param CartLineInterface $cartLine CartLine
     * @param CouponInterface   $coupon   Coupon
     *
     * @return boolean Coupon is valid
     */
    public function getCouponAmount(
        $cartLine,
        $coupon
    )
    {
        $discount = $coupon->getType() == ElcodiCouponTypes::TYPE_AMOUNT 
		  ? $coupon->getPrice()->getAmount() 
		  : $coupon->getDiscount();
        $rule     = $coupon->getdiscountRule();
        $n        = $coupon->getN();
        $m        = $coupon->getM();
        $qty      = $cartLine->getQuantity();
        $price    = $cartLine->getProductAmount()->getAmount();

        $couponAmount = $cartLine->getCouponAmount();

        try {
            $calculatedDiscount = $this
                ->ruleManager
                ->evaluate(
                    $rule,
                    [
                        'discount' => $discount,
                        'N'        => $n,
                        'M'        => $m,
                        'qty'      => $qty,
                        'price'    => $price,
                    ]
                );
            $couponAmount->setAmount($calculatedDiscount);
        } catch (\Exception $e) {
            // Maybe log something in case of exception?
        }

        return $couponAmount;
    }
}
