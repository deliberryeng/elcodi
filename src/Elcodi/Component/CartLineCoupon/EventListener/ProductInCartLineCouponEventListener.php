<?php

namespace Elcodi\Component\CartLineCoupon\EventListener;

use Elcodi\Component\CartLineCoupon\Event\CartLineCouponOnCheckEvent;
use Elcodi\Component\Coupon\Exception\CouponProductNotInCartLineException;
    

/**
 * Class ProductInCartLineCouponEventListener
 *
 */
class ProductInCartLineCouponEventListener
{
    
    /**
     * Construct
     *
     */
    public function __construct()
    {
    }

    /**
     * Check if product is in cartLine
     *
     * @param CartLineCouponOnCheckEvent $event Event
     *
     */
    public function checkProductInCartLine(CartLineCouponOnCheckEvent $event)
    {
        $productCouponIds = $event
            ->getCartLine()
            ->getProduct()
            ->getCoupons()
            ->map(function($entity) { return $entity->getId(); })
        ;
        $couponId = $event
            ->getCoupon()
            ->getId();

        $found = false;
        if (in_array($couponId, $productCouponIds))
        {
            $found = true;
        }

        if (!$found){
            throw new CouponProductNotInCartLineException();
        }
    }
}
?>
