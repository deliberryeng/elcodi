<?php

namespace Elcodi\Component\CartCoupon\EventListener;

use Elcodi\Component\CartCoupon\Event\CartCouponOnCheckEvent;
use Elcodi\Component\Coupon\Exception\CouponProductNotInCartException;
    

/**
 * Class ProductInCartCouponEventListener
 *
 */
class ProductInCartCouponEventListener
{
    
    /**
     * Construct
     *
     */
    public function __construct()
    {
    }

    /**
     * Check if product is in cart
     *
     * @param CartCouponOnCheckEvent $event Event
     *
     */
    public function checkProductInCart(CartCouponOnCheckEvent $event)
    {
        $productIds = $event
            ->getCoupon()
            ->getProducts()
            ->map(function($entity) { return $entity->getId(); })
            ->toArray();

        $cartLines = $event
            ->getCart()
            ->getCartLines();

        $found = false;
        foreach ($cartLines as $cartLine){
            if (in_array($cartLine->getProduct()->getId(), $productIds))
            {
                $found = true;
            }
        }

        if (!$found){
            throw new CouponProductNotInCartException();
        }
    }
}
?>
